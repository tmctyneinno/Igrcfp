<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session as StripeSession;

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

        // Create enrollments with pending payment status
        $enrollments = [];
        foreach ($cart->items as $item) {
            $enrollment = Enrollment::create([
                'user_id' => $request->user()->id,
                'course_id' => $item->course_id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'payment_method' => 'stripe',
                'amount' => $item->price,
                'status' => 'pending_payment',
                'enrollment_date' => now(),
            ]);
            $enrollments[] = $enrollment;
        } 

        // Store enrollment IDs in session for later
        session(['pending_enrollments' => array_map(fn($e) => $e->id, $enrollments)]);
        session(['cart_id' => $cart->id]);

        // If total is 0, enroll directly
        if ($cart->total_amount == 0) {
            foreach ($enrollments as $enrollment) {
                $enrollment->update(['status' => 'enrolled']);
            }
            $cart->update(['status' => 'checked_out']);
            
            return redirect()->route('checkout.success')->with('success', 'Enrollment successful!');
        }

        // Create Stripe Checkout Session
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        foreach ($cart->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->course->title,
                    ],
                    'unit_amount' => $item->price * 100, // Stripe uses cents
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
                'cart_id' => $cart->id,
                'user_id' => $request->user()->id,
            ],
        ]);

        return Inertia::location($checkoutSession->url);
    }

    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $cart = $request->user()->carts()
            ->with('items')
            ->where('status', 'active')
            ->latest()
            ->first();

        $amount = $cart->total_amount * 100; // Convert to cents

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'metadata' => [
                'cart_id' => $cart->id,
                'user_id' => $request->user()->id,
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
        
        try {
            $session = StripeSession::retrieve($sessionId);
            
            // Get pending enrollments from session
            $enrollmentIds = session('pending_enrollments', []);
            $cartId = session('cart_id');
            
            // Update enrollments to enrolled status
            foreach ($enrollmentIds as $enrollmentId) {
                $enrollment = Enrollment::find($enrollmentId);
                if ($enrollment) {
                    $enrollment->update(['status' => 'enrolled']);
                }
            }
            
            // Mark cart as checked out
            if ($cartId) {
                Cart::where('id', $cartId)->update(['status' => 'checked_out']);
            }
            
            // Clear session data
            session()->forget(['pending_enrollments', 'cart_id']);
            
            return redirect()->route('checkout.success')->with('success', 'Payment successful! You are now enrolled.');
            
        } catch (\Exception $e) {
            \Log::error('Stripe success error: ' . $e->getMessage());
            return redirect()->route('checkout.cancel')->with('error', 'There was an error processing your payment.');
        }
    }

    public function stripeCancel(Request $request)
    {
        // Clean up pending enrollments
        $enrollmentIds = session('pending_enrollments', []);
        Enrollment::whereIn('id', $enrollmentIds)->delete();
        
        session()->forget(['pending_enrollments', 'cart_id']);
        
        return redirect()->route('dashboard.cart.index')->with('info', 'Payment was cancelled.');
    }

    public function success(Request $request)
    {
        return Inertia::render('Dashboard/Checkout/Success', [
            'enrollments' => $request->session()->get('enrollments', [])
        ]);
    }

    public function cancel(Request $request)
    {
        return redirect()->route('dashboard.cart.index')->with('info', 'Checkout cancelled.');
    }
}