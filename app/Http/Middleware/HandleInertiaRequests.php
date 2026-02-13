<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Cart; // Add this at the top

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Get cart count for authenticated users
        $cartCount = 0;
        
        if ($request->user()) {
            $cart = Cart::where('user_id', $request->user()->id)
                ->with('items')
                ->where('status', 'active')
                ->latest()
                ->first();
            
            $cartCount = $cart ? $cart->items->count() : 0;
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'error' => fn () => $request->session()->get('error'),
                'success' => fn () => $request->session()->get('success'), // Added success flash
                'info' => fn () => $request->session()->get('info'), // Added info flash
            ],
            'errors' => fn () => $request->session()->get('errors') 
                ? $request->session()->get('errors')->getBag('default')->getMessages()
                : (object) [],
            'cart_count' => $cartCount, // Add cart count here
        ]); 
    }
}