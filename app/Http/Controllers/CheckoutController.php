<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
            ]
        ]);
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:card,bank,paypal',
            'terms_accepted' => 'required|accepted',
        ]);

        $cart = $request->user()->carts()
            ->with('items')
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('dashboard.cart.index')->with('error', 'Your cart is empty.');
        }

        // Create enrollments for each course in cart
        $enrollments = [];
        foreach ($cart->items as $item) {
            $enrollment = Enrollment::create([
                'user_id' => $request->user()->id,
                'course_id' => $item->course_id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'payment_method' => $validated['payment_method'],
                'amount' => $item->price,
                'status' => $item->price > 0 ? 'pending_payment' : 'enrolled',
                'enrollment_date' => now(),
            ]);
            $enrollments[] = $enrollment;
        }

        // Mark cart as checked out
        $cart->update(['status' => 'checked_out']);

        // If total is 0 (all free courses), redirect to success
        if ($cart->total_amount == 0) {
            return redirect()->route('dashboard.checkout.success', ['enrollments' => $enrollments]);
        }

        // Redirect to payment processing (implement based on your payment gateway)
        return redirect()->route('dashboard.payment.process', [
            'cart_id' => $cart->id,
            'amount' => $cart->total_amount
        ]);
    }

    public function success(Request $request)
    {
        return Inertia::render('Dashboard/Checkout/Success', [
            'enrollments' => $request->enrollments ?? []
        ]);
    }

    public function cancel()
    {
        return redirect()->route('dashboard.cart.index')->with('info', 'Checkout cancelled.');
    }
}