<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Enrollment; 
use App\Models\Transaction; 
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\DB;
 
class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->carts()
            ->with(['items.course'])
            ->where('status', 'active')
            ->latest()
            ->first();
  
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('dashboard.cart.index')->with('error', 'Your cart is empty.');
        }

        return Inertia::render('Dashboard/Checkout/Index', [
            'cart' => [
                'id' => $cart->id,
                'item_count' => $cart->item_count,
                'total_amount' => $cart->total_amount,
                'items' => $cart->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'course_id' => $item->course_id,
                        'title' => $item->course->title,
                        'price' => $item->price,
                    ];
                })
            ],
            'user' => [
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'stripe_key' => env('STRIPE_KEY'),
        ]);
    }

    public function process(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'terms_accepted' => 'required|accepted',
    ]);

    $cart = $request->user()->carts()
        ->with('items.course')
        ->where('status', 'active')
        ->latest()
        ->first();

    if (!$cart || $cart->items->isEmpty()) {
        return redirect()->route('dashboard.cart.index')->with('error', 'Your cart is empty.');
    }

    // Check if user is already enrolled in any of the courses
    $existingEnrollments = [];
    foreach ($cart->items as $item) {
        $existing = Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $item->course_id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->first();
        
        if ($existing) {
            $existingEnrollments[] = $item->course->title;
        }
    }
    
    if (!empty($existingEnrollments)) {
        return redirect()->route('dashboard.cart.index')->with('error', 
            'You are already enrolled in: ' . implode(', ', $existingEnrollments)
        );
    }

    // Use database transaction to ensure data integrity
    DB::beginTransaction();

    try {
        \Log::info('Starting checkout process', [
            'user_id' => $request->user()->id,
            'cart_id' => $cart->id,
            'cart_total' => $cart->total_amount,
            'items_count' => $cart->items->count()
        ]);

        // Create enrollments
        $enrollments = [];
        foreach ($cart->items as $item) {
            \Log::info('Processing cart item', [
                'course_id' => $item->course_id,
                'course_title' => $item->course->title ?? 'N/A',
                'price' => $item->price
            ]);

            // Check if course exists and is valid
            if (!$item->course) {
                throw new \Exception('Course not found for cart item: ' . $item->id);
            }

            // Check for existing pending enrollment
            $existingPending = Enrollment::where('user_id', $request->user()->id)
                ->where('course_id', $item->course_id)
                ->where('status', 'pending_payment')
                ->first();
            
            if ($existingPending) {
                \Log::info('Found existing pending enrollment, reusing', ['enrollment_id' => $existingPending->id]);
                $enrollments[] = $existingPending;
                continue;
            }

            // Create new enrollment
            try {
                $enrollmentData = [
                    'user_id' => $request->user()->id,
                    'course_id' => $item->course_id,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'payment_method' => $cart->total_amount == 0 ? 'free' : 'stripe',
                    'amount' => $item->price,
                    'status' => 'pending_payment',
                    'enrollment_date' => now(),
                    'progress' => 0,
                ];
                
                \Log::info('Creating enrollment with data', $enrollmentData);
                
                $enrollment = Enrollment::create($enrollmentData);
                
                \Log::info('Enrollment created successfully', ['enrollment_id' => $enrollment->id]);
                
                $enrollments[] = $enrollment;
            } catch (\Exception $e) {
                \Log::error('Failed to create enrollment', [
                    'error' => $e->getMessage(),
                    'course_id' => $item->course_id,
                    'user_id' => $request->user()->id
                ]);
                throw $e;
            }
        }

        // Store enrollment IDs in session
        $enrollmentIds = array_map(function($e) { 
            return $e->id; 
        }, $enrollments);
        
        session(['pending_enrollments' => $enrollmentIds]);
        session(['cart_id' => $cart->id]);

        \Log::info('Enrollments created/retrieved', [
            'enrollment_ids' => $enrollmentIds,
            'cart_total' => $cart->total_amount
        ]);

        // If total is 0, enroll directly (free course)
        if ($cart->total_amount == 0) {
            \Log::info('Processing free course enrollment');
            
            foreach ($enrollments as $enrollment) {
                $updateResult = $enrollment->update([
                    'status' => 'enrolled',
                    'payment_method' => 'free',
                ]);
                \Log::info('Updated enrollment status', [
                    'enrollment_id' => $enrollment->id,
                    'update_result' => $updateResult,
                    'new_status' => $enrollment->fresh()->status
                ]);
            }
            
            // Mark cart as checked out
            $cart->update(['status' => 'checked_out']);
            
            // Clear session data
            session()->forget(['pending_enrollments', 'cart_id']);
            
            DB::commit();
            
            \Log::info('Free course enrollment completed successfully', [
                'user_id' => $request->user()->id,
                'enrollment_ids' => $enrollmentIds,
            ]);
            
            return redirect()->route('checkout.success')->with('success', 'Enrollment successful! You can now start learning.');
        }

        // For paid courses, proceed to Stripe
        DB::commit();
        
        \Log::info('Proceeding to Stripe checkout', ['cart_total' => $cart->total_amount]);

        // Check if Stripe keys are configured
        if (!env('STRIPE_SECRET') || !env('STRIPE_KEY')) {
            \Log::error('Stripe keys not configured');
            throw new \Exception('Payment system not properly configured.');
        }

        // Create Stripe Checkout Session
        Stripe::setApiKey(env('STRIPE_SECRET')); 

        $lineItems = [];
        foreach ($cart->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'gbp',
                    'product_data' => [
                        'name' => $item->course->title,
                    ],
                    'unit_amount' => (int)($item->price * 100),
                ],
                'quantity' => 1,
            ];
        }

        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('payment.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.stripe.cancel'),
            'customer_email' => $validated['email'],
            'metadata' => [
                'cart_id' => (string) $cart->id,
                'user_id' => (string) $request->user()->id,
                'enrollment_ids' => implode(',', $enrollmentIds),
            ],
        ]);

        return Inertia::location($checkoutSession->url);

    } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        \Log::error('Database error in checkout: ' . $e->getMessage(), [
            'sql' => $e->getSql(),
            'bindings' => $e->getBindings(),
            'user_id' => $request->user()->id,
            'trace' => $e->getTraceAsString()
        ]);
        
        $errorMessage = 'Database error occurred. ';
        if (str_contains($e->getMessage(), 'Duplicate entry')) {
            $errorMessage = 'You already have an enrollment for this course.';
        } elseif (str_contains($e->getMessage(), 'Column not found')) {
            $errorMessage = 'Database schema issue. Please contact support.';
        } else {
            $errorMessage .= 'Please try again.';
        }
        
        return redirect()->route('dashboard.cart.index')->with('error', $errorMessage);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Checkout process error: ' . $e->getMessage(), [
            'user_id' => $request->user()->id,
            'cart_id' => $cart->id ?? null,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        $errorMessage = 'An error occurred during checkout. ';
        if (str_contains($e->getMessage(), 'Duplicate entry')) {
            $errorMessage = 'You already have a pending enrollment for this course.';
        } elseif (str_contains($e->getMessage(), 'Stripe')) {
            $errorMessage = 'Payment system error. Please try again later.';
        } elseif (str_contains($e->getMessage(), 'SQLSTATE')) {
            $errorMessage = 'Database error. Please try again.';
        } else {
            $errorMessage .= 'Error: ' . $e->getMessage();
        }
        
        return redirect()->route('dashboard.cart.index')->with('error', $errorMessage);
    }
}

    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $cart = $request->user()->carts()
            ->with('items')
            ->where('status', 'active')
            ->latest()
            ->first();

        $amount = (int)($cart->total_amount * 100); // Convert to cents and ensure integer

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'eur',
            'metadata' => [
                'cart_id' => (string) $cart->id,
                'user_id' => (string) $request->user()->id,
            ],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function stripeSuccess(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return redirect()->route('checkout.cancel')->with('error', 'Invalid session.');
        }
        
        DB::beginTransaction();
        
        try {
            $session = StripeSession::retrieve($sessionId);
            
            // Get enrollment IDs from session or metadata
            $enrollmentIds = session('pending_enrollments', []);
            
            // If not in session, try to get from metadata
            if (empty($enrollmentIds) && isset($session->metadata->enrollment_ids)) {
                $enrollmentIds = explode(',', $session->metadata->enrollment_ids);
            }
            
            $cartId = session('cart_id') ?? $session->metadata->cart_id ?? null;
            
            // Update enrollments to enrolled status and create transactions
            if (!empty($enrollmentIds)) {
                foreach ($enrollmentIds as $enrollmentId) {
                    $enrollment = Enrollment::find($enrollmentId);
                    if ($enrollment) {
                        $enrollment->update([
                            'status' => 'enrolled',
                            'payment_method' => 'stripe',
                            'name' => $enrollment->name ?? $session->customer_details->name ?? null,
                            'email' => $enrollment->email ?? $session->customer_details->email ?? null,
                        ]);
                        
                        // Create transaction record
                        Transaction::create([
                            'user_id' => $enrollment->user_id,
                            'enrollment_id' => $enrollment->id,
                            'transaction_id' => $session->payment_intent ?? $sessionId,
                            'payment_method' => 'stripe',
                            'amount' => $enrollment->amount,
                            'currency' => 'usd',
                            'status' => 'completed',
                            'payment_details' => [
                                'session_id' => $sessionId,
                                'payment_intent' => $session->payment_intent,
                                'customer_email' => $session->customer_details->email ?? $session->customer_email,
                            ],
                            'reference' => $sessionId,
                            'session_id' => $sessionId,
                            'paid_at' => now(),
                        ]);
                    }
                }
            } else {
                // Fallback: If no enrollment IDs found, create enrollments from cart items
                if ($cartId) {
                    $cart = Cart::with('items.course')->find($cartId);
                    if ($cart) {
                        foreach ($cart->items as $item) {
                            $enrollment = Enrollment::updateOrCreate(
                                [
                                    'user_id' => $session->metadata->user_id,
                                    'course_id' => $item->course_id,
                                ],
                                [
                                    'name' => $session->customer_details->name ?? 'Student',
                                    'email' => $session->customer_details->email ?? $session->customer_email,
                                    'payment_method' => 'stripe',
                                    'amount' => $item->price,
                                    'status' => 'enrolled',
                                    'enrollment_date' => now(),
                                ]
                            );
                            
                            // Create transaction record
                            Transaction::create([
                                'user_id' => $enrollment->user_id,
                                'enrollment_id' => $enrollment->id,
                                'transaction_id' => $session->payment_intent ?? $sessionId,
                                'payment_method' => 'stripe',
                                'amount' => $item->price,
                                'currency' => 'usd',
                                'status' => 'completed',
                                'payment_details' => [
                                    'session_id' => $sessionId,
                                    'payment_intent' => $session->payment_intent,
                                    'customer_email' => $session->customer_details->email ?? $session->customer_email,
                                ],
                                'reference' => $sessionId,
                                'session_id' => $sessionId,
                                'paid_at' => now(),
                            ]);
                        }
                    }
                }
            }
            
            // Mark cart as checked out
            if ($cartId) {
                Cart::where('id', $cartId)->update(['status' => 'checked_out']);
            }
            
            // Clear session data
            session()->forget(['pending_enrollments', 'cart_id']);
            
            DB::commit();

             // Get the first enrollment ID
            $firstEnrollmentId = !empty($enrollmentIds) ? $enrollmentIds[0] : null;
            
            if ($firstEnrollmentId) {
                return redirect()->route('checkout.success', ['enrollment' => $firstEnrollmentId])
                    ->with('success', 'Payment successful! You are now enrolled.');
            } else {
                return redirect()->route('checkout.success')
                    ->with('success', 'Payment successful! You are now enrolled.');
            }
              
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Stripe success error: ' . $e->getMessage());
            return redirect()->route('checkout.cancel')->with('error', 'There was an error processing your payment.');
        }
    }

    public function stripeCancel(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Clean up pending enrollments
            $enrollmentIds = session('pending_enrollments', []);
            
            if (!empty($enrollmentIds)) {
                Enrollment::whereIn('id', $enrollmentIds)->delete();
            }
            
            session()->forget(['pending_enrollments', 'cart_id']);
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Stripe cancel error: ' . $e->getMessage());
        }
        
        return redirect()->route('dashboard.cart.index')->with('info', 'Payment was cancelled.');
    }

    public function success(Request $request, $enrollment = null)
    {
        // Get the user's recent enrollments
        $enrollments = Enrollment::with('course')
            ->where('user_id', $request->user()->id)
            ->where('status', 'enrolled')
            ->latest()
            ->take(5)
            ->get();
            // If specific enrollment ID provided, get that one
        $specificEnrollment = null;
        if ($enrollment) {
            $specificEnrollment = Enrollment::with('course')
                ->where('id', $enrollment)
                ->where('user_id', $request->user()->id)
                ->first();
        }

        return Inertia::render('Dashboard/Checkout/Success', [
            'enrollments' => $enrollments->map(function($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'course_title' => $enrollment->course->title ?? 'Unknown Course',
                    'amount' => $enrollment->amount,
                    'enrollment_date' => $enrollment->enrollment_date->format('Y-m-d'),
                ];
            }),
            'specificEnrollment' => $specificEnrollment ? [
                'id' => $specificEnrollment->id,
                'course_title' => $specificEnrollment->course->title ?? 'Unknown Course',
                'course_slug' => $specificEnrollment->course->slug ?? null,
                'amount' => $specificEnrollment->amount,
                'enrollment_date' => $specificEnrollment->enrollment_date->format('Y-m-d'),
            ] : null,
        ]);
    }

    public function cancel(Request $request)
    {
        return redirect()->route('dashboard.cart.index')->with('info', 'Checkout cancelled.');
    }
}