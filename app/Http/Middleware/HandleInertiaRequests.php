<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Cart;
use App\Models\MentorshipMessage;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $cartCount = 0;
        $notificationCount = 0;
        
        if ($request->user()) {
            $cart = Cart::where('user_id', $request->user()->id)
                ->with('items')
                ->where('status', 'active')
                ->latest()
                ->first();
            
            $cartCount = $cart ? $cart->items->count() : 0;

            $userId = $request->user()->id;
            $notificationCount = MentorshipMessage::query()
                ->where('user_id', '!=', $userId)
                ->whereNull('read_at')
                ->where(function ($query) use ($userId) {
                    $query->whereHas('mentorship', function ($mentorshipQuery) use ($userId) {
                        $mentorshipQuery->where('mentee_id', $userId)
                            ->orWhereHas('mentorProfile', function ($mentorQuery) use ($userId) {
                                $mentorQuery->where('user_id', $userId);
                            });
                    });
                })
                ->count();
        }

        // Get flash data
        $flashModules = $request->session()->get('modules');
        $flashEnrollment = $request->session()->get('enrollment');

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null,
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'error' => fn () => $request->session()->get('error'),
                'success' => fn () => $request->session()->get('success'),
                'info' => fn () => $request->session()->get('info'),
                'warning' => fn () => $request->session()->get('warning'),
                'modules' => $flashModules,  // Direct assignment, not closure
                'enrollment' => $flashEnrollment,  // Direct assignment
            ],
            'errors' => fn () => $request->session()->get('errors') 
                ? $request->session()->get('errors')->getBag('default')->getMessages()
                : (object) [],
            'cart_count' => $cartCount,
            'notification_count' => $notificationCount,
        ]);
    }
}
