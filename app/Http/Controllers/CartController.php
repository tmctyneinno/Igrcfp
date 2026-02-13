<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->carts()
            ->with(['items.course'])
            ->where('status', 'active')
            ->latest()
            ->first();

        return Inertia::render('Dashboard/Cart/Index', [
            'cart' => $cart ? [
                'id' => $cart->id,
                'item_count' => $cart->item_count,
                'total_amount' => $cart->total_amount,
                'items' => $cart->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'course' => $item->course ? [
                            'id' => $item->course->id,
                            'title' => $item->course->title,
                            'slug' => $item->course->slug,
                            'image_url' => $item->course->image_url,
                            'level' => $item->course->level,
                            'duration' => $item->course->duration,
                        ] : null
                    ];
                })
            ] : null
        ]);
    }

    public function add(Request $request, Course $course)
    {
        if (!$course->status) {
            return redirect()->back()->with('error', 'Course not available.');
        }

        $cart = $request->user()->carts()->firstOrCreate(
            ['status' => 'active'],
            ['session_id' => session()->getId()]
        );

        // Check if already in cart
        if ($cart->items()->where('course_id', $course->id)->exists()) {
            return redirect()->route('dashboard.cart.index')->with('info', 'Course already in cart.');
        }

        $cart->items()->create([
            'course_id' => $course->id,
            'price' => $course->discount_price ?? $course->price,
        ]);

        $cart->updateTotals();

        // Get updated count for response
        $cartCount = $cart->items()->count();

        return redirect()->route('dashboard.cart.index')->with([
            'success' => 'Course added to cart.',
            'cart_count' => $cartCount
        ]);
    }

    public function remove(Request $request, $itemId)
    {
        $cart = $request->user()->carts()->where('status', 'active')->first();
        
        if ($cart) {
            $cart->items()->where('id', $itemId)->delete();
            $cart->updateTotals();
        }

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function clear(Request $request)
    {
        $cart = $request->user()->carts()->where('status', 'active')->first();
        
        if ($cart) {
            $cart->clear();
        }

        return redirect()->back()->with('success', 'Cart cleared.');
    }
}