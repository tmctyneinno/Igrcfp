<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Enrollment; 
use App\Models\Membership;
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
            ->with(['items.course', 'items.membershipPlan']) // Add membershipPlan
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
                    // Handle course items
                    if ($item->item_type === 'course' && $item->course) {
                        return [
                            'id' => $item->id,
                            'type' => 'course',
                            'course_id' => $item->course_id,
                            'title' => $item->course->title,
                            'price' => $item->price,
                        ];
                    }
                    
                    // Handle membership items
                    if ($item->item_type === 'membership' && $item->membershipPlan) {
                        return [
                            'id' => $item->id,
                            'type' => 'membership',
                            'membership_plan_id' => $item->membership_plan_id,
                            'title' => $item->membershipPlan->name,
                            'price' => $item->price,
                            'duration_months' => $item->membershipPlan->duration_months,
                        ];
                    }
                    
                    return null;
                })->filter() // Remove null items
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
            ->with(['items.course', 'items.membershipPlan']) // Add membershipPlan
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('dashboard.cart.index')->with('error', 'Your cart is empty.');
        }

        // Separate course and membership items
        $courseItems = $cart->items->filter(function($item) {
            return $item->item_type === 'course' && $item->course;
        });
        
        $membershipItems = $cart->items->filter(function($item) {
            return $item->item_type === 'membership' && $item->membershipPlan;
        });

        // Check if user is already enrolled in any of the courses
        $existingEnrollments = [];
        foreach ($courseItems as $item) {
            $existing = Enrollment::where('user_id', $request->user()->id)
                ->where('course_id', $item->course_id)
                ->whereIn('status', ['enrolled', 'completed'])
                ->first();
            
            if ($existing) {
                $existingEnrollments[] = $item->course->title;
            }
        }
        
        // Check if user already has an active membership
        $existingMembership = null;
        foreach ($membershipItems as $item) {
            $existingMembership = Membership::where('user_id', $request->user()->id)
                ->where('status', 'active')
                ->first();
            
            if ($existingMembership) {
                return redirect()->route('dashboard.cart.index')->with('error', 
                    'You already have an active membership. Please manage your existing membership first.'
                );
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
                'items_count' => $cart->items->count(),
                'course_items' => $courseItems->count(),
                'membership_items' => $membershipItems->count()
            ]);

            $enrollments = [];
            $memberships = [];

            // Process course enrollments
            foreach ($courseItems as $item) {
                \Log::info('Processing course item', [
                    'course_id' => $item->course_id,
                    'course_title' => $item->course->title ?? 'N/A',
                    'price' => $item->price
                ]);

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
                $enrollment = Enrollment::create([
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
                ]);
                
                \Log::info('Course enrollment created', ['enrollment_id' => $enrollment->id]);
                $enrollments[] = $enrollment;
            }

            // Process membership items
            foreach ($membershipItems as $item) {
                \Log::info('Processing membership item', [
                    'membership_plan_id' => $item->membership_plan_id,
                    'plan_name' => $item->membershipPlan->name ?? 'N/A',
                    'price' => $item->price
                ]);

                if (!$item->membershipPlan) {
                    throw new \Exception('Membership plan not found for cart item: ' . $item->id);
                }

                // Check for existing pending membership
                $existingPendingMembership = Membership::where('user_id', $request->user()->id)
                    ->where('status', 'pending')
                    ->first();
                
                if ($existingPendingMembership) {
                    \Log::info('Found existing pending membership, reusing', ['membership_id' => $existingPendingMembership->id]);
                    $memberships[] = $existingPendingMembership;
                    continue;
                }

                // Create new membership
                $membership = Membership::create([
                    'user_id' => $request->user()->id,
                    'membership_plan_id' => $item->membership_plan_id,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'start_date' => now(),
                    'end_date' => now()->addMonths($item->membershipPlan->duration_months),
                    'status' => 'pending',
                    'payment_method' => $cart->total_amount == 0 ? 'free' : 'stripe',
                    'amount' => $item->price,
                ]);
                
                \Log::info('Membership created', ['membership_id' => $membership->id]);
                $memberships[] = $membership;
            }

            // Store IDs in session
            $enrollmentIds = array_map(function($e) { return $e->id; }, $enrollments);
            $membershipIds = array_map(function($m) { return $m->id; }, $memberships);
            
            session([
                'pending_enrollments' => $enrollmentIds,
                'pending_memberships' => $membershipIds,
                'cart_id' => $cart->id
            ]);

            \Log::info('Items created', [
                'enrollment_ids' => $enrollmentIds,
                'membership_ids' => $membershipIds,
                'cart_total' => $cart->total_amount
            ]);

            // If total is 0, activate directly (free items)
            if ($cart->total_amount == 0) {
                \Log::info('Processing free items');
                
                // Activate course enrollments
                foreach ($enrollments as $enrollment) {
                    $enrollment->update([
                        'status' => 'enrolled',
                        'payment_method' => 'free',
                    ]);

                    Transaction::updateOrCreate(
                        ['enrollment_id' => $enrollment->id],
                        [
                            'user_id' => $enrollment->user_id,
                            'transaction_id' => 'FREE-' . $enrollment->id,
                            'payment_method' => 'free',
                            'amount' => 0,
                            'currency' => 'gbp',
                            'status' => 'completed',
                            'payment_details' => [
                                'checkout_type' => 'free_course',
                                'cart_id' => $cart->id,
                            ],
                            'reference' => 'FREE-' . $enrollment->id,
                            'paid_at' => now(),
                        ]
                    );
                }
                
                // Activate memberships
                foreach ($memberships as $membership) {
                    $membership->update([
                        'status' => 'active',
                        'payment_method' => 'free',
                    ]);

                    Transaction::create([
                        'user_id' => $membership->user_id,
                        'membership_id' => $membership->id,
                        'transaction_id' => 'FREE-MEM-' . $membership->id,
                        'payment_method' => 'free',
                        'amount' => 0,
                        'currency' => 'gbp',
                        'status' => 'completed',
                        'payment_details' => [
                            'checkout_type' => 'free_membership',
                            'cart_id' => $cart->id,
                        ],
                        'reference' => 'FREE-MEM-' . $membership->id,
                        'paid_at' => now(),
                    ]);
                }
                
                // Mark cart as checked out
                $cart->update(['status' => 'checked_out']);
                
                // Clear session data
                session()->forget(['pending_enrollments', 'pending_memberships', 'cart_id']);
                
                DB::commit();
                
                \Log::info('Free items completed successfully', [
                    'user_id' => $request->user()->id,
                    'enrollment_ids' => $enrollmentIds,
                    'membership_ids' => $membershipIds,
                ]);
                
                $firstEnrollmentId = !empty($enrollmentIds) ? $enrollmentIds[0] : null;

                return redirect()
                    ->route('checkout.success', $firstEnrollmentId ? ['enrollment' => $firstEnrollmentId] : [])
                    ->with('success', 'Enrollment successful! You can now start learning.');
            }

            // For paid items, proceed to Stripe
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
                $productName = '';
                if ($item->item_type === 'course' && $item->course) {
                    $productName = $item->course->title;
                } elseif ($item->item_type === 'membership' && $item->membershipPlan) {
                    $productName = $item->membershipPlan->name . ' Membership';
                }
                
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'gbp',
                        'product_data' => [
                            'name' => $productName,
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
                    'membership_ids' => implode(',', $membershipIds),
                ],
            ]);

            return Inertia::location($checkoutSession->url);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout process error: ' . $e->getMessage(), [
                'user_id' => $request->user()->id,
                'cart_id' => $cart->id ?? null,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('dashboard.cart.index')->with('error', 'An error occurred during checkout: ' . $e->getMessage());
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
                    'course_slug' => $enrollment->course->slug ?? null,
                    'amount' => $enrollment->amount,
                    'payment_method' => $enrollment->payment_method,
                    'enrollment_date' => $enrollment->enrollment_date->format('Y-m-d'),
                ];
            }),
            'specificEnrollment' => $specificEnrollment ? [
                'id' => $specificEnrollment->id,
                'course_title' => $specificEnrollment->course->title ?? 'Unknown Course',
                'course_slug' => $specificEnrollment->course->slug ?? null,
                'amount' => $specificEnrollment->amount,
                'payment_method' => $specificEnrollment->payment_method,
                'enrollment_date' => $specificEnrollment->enrollment_date->format('Y-m-d'),
            ] : null,
        ]);
    }

    public function cancel(Request $request)
    {
        return redirect()->route('dashboard.cart.index')->with('info', 'Checkout cancelled.');
    }

}