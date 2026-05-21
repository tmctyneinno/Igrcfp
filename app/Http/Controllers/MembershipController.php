<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\MembershipTier;
use Illuminate\Http\Request;
use Inertia\Inertia;


class MembershipController extends Controller
{
    // public function index(Request $request)
    // {
    //     $tiers = MembershipTier::query()
    //         ->where('is_active', true)
    //         ->with(['plans' => function ($query) {
    //             $query->where('is_active', true)->orderBy('price');
    //         }])
    //         ->orderBy('sort_order')
    //         ->get();

    //     $activeMembership = $request->user()->activeMembership();

    //     return view('memberships.index', [
    //         'tiers' => $tiers,
    //         'activeMembership' => $activeMembership,
    //     ]);
    // }






public function index(Request $request)
    {
        $tiers = MembershipTier::query()
            ->where('is_active', true)
            ->with(['plans' => function ($query) {
                $query->where('is_active', true)->orderBy('price');
            }])
            ->orderBy('sort_order')
            ->get()
            ->map(function ($tier) {
                return [
                    'id' => $tier->id,
                    'name' => $tier->name,
                    'description' => $tier->description,
                    'benefits' => $tier->benefits ?? [],
                    'plans' => $tier->plans->map(function ($plan) {
                        return [
                            'id' => $plan->id,
                            'name' => $plan->name,
                            'price' => $plan->price,
                            'currency' => $plan->currency,
                            'billing_interval' => $plan->billing_interval,
                            'benefits' => $plan->benefits ?? [],
                        ];
                    })->values(),
                ];
            });

        $activeMembership = $request->user()->activeMembership();

        return Inertia::render('Dashboard/Memebership/Show', [
            'auth' => [
                'user' => $request->user(),
            ],
            'tiers' => $tiers,
            'activeMembership' => $activeMembership ? [
                'id' => $activeMembership->id,
                'status' => $activeMembership->status,
            ] : null,
        ]);
    }









    // public function status(Request $request)
    // {
    //     $membership = $request->user()->memberships()
    //         ->with('plan.tier')
    //         ->latest()
    //         ->first();

    //     return view('memberships.status', [
    //         'membership' => $membership,
    //     ]);
    // }



    public function status(Request $request)
        {
            $membership = $request->user()->memberships()
                ->with('plan.tier')
                ->latest()
                ->first();

            return Inertia::render('Dashboard/Memebership/Status', [
                'auth' => ['user' => $request->user()],
                'membership' => $membership ? [
                    'id' => $membership->id,
                    'status' => $membership->status,
                    'status_label' => $membership->statusLabel(),
                    'purchased_at' => $membership->purchased_at?->format('M d, Y'),
                    'approved_at' => $membership->approved_at?->format('M d, Y'),
                    'expires_at' => $membership->expires_at?->format('M d, Y'),
                    'plan' => $membership->plan ? [
                        'name' => $membership->plan->name,
                        'billing_interval' => $membership->plan->billing_interval,
                        'tier' => $membership->plan->tier ? [
                            'name' => $membership->plan->tier->name,
                        ] : null,
                    ] : null,
                ] : null,
            ]);
        }

    public function addToCart(Request $request, MembershipPlan $plan)
    { 
        if (!$plan->is_active) {
            return redirect()
                ->route('dashboard.memberships.index')
                ->with('error', 'This membership plan is currently unavailable.');
        }

        if ($request->user()->activeMembership()) {
            return redirect()
                ->route('dashboard.memberships.status')
                ->with('info', 'You already have an active membership.');
        }

        $pendingMembership = $request->user()->memberships()
            ->whereIn('status', ['pending_payment', 'pending_approval'])
            ->exists();

        if ($pendingMembership) {
            return redirect()
                ->route('dashboard.memberships.status')
                ->with('info', 'You already have a pending membership request.');
        }

        $cart = $request->user()->carts()->firstOrCreate(
            ['status' => 'active'],
            ['session_id' => session()->getId()]
        );

        $existingMembershipItem = $cart->items()->where('item_type', 'membership')->first();
        if ($existingMembershipItem) {
            return redirect()
                ->route('dashboard.memberships.checkout')
                ->with('info', 'You already have a membership plan in your cart.');
        }

        $cart->items()->create([
            'item_type' => 'membership',
            'membership_plan_id' => $plan->id,
            'price' => $plan->price,
            'quantity' => 1,
        ]);

        $cart->updateTotals();

        return redirect()
            ->route('dashboard.memberships.checkout')
            ->with('success', 'Membership plan added to cart.');
    }


    public function checkout(Request $request)
        {
            $cart = $request->user()->carts()
                ->with(['items.membershipPlan.tier'])
                ->where('status', 'active')
                ->latest()
                ->first();

            $membershipItems = $cart?->items->where('item_type', 'membership') ?? collect();

            if ($membershipItems->isEmpty()) {
                return redirect()
                    ->route('dashboard.memberships.index')
                    ->with('error', 'Your membership cart is empty.');
            }

            return Inertia::render('Dashboard/Memebership/Checkout', [
                'auth' => ['user' => $request->user()],
                'cart' => $cart ? [
                    'id' => $cart->id,
                    'total_amount' => $cart->total_amount,
                ] : null,
                'membershipItems' => $membershipItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'price' => $item->price,
                        'plan' => $item->membershipPlan ? [
                            'id' => $item->membershipPlan->id,
                            'name' => $item->membershipPlan->name,
                            'currency' => $item->membershipPlan->currency,
                            'billing_interval' => $item->membershipPlan->billing_interval,
                            'tier' => $item->membershipPlan->tier ? [
                                'name' => $item->membershipPlan->tier->name,
                            ] : null,
                        ] : null,
                    ];
                })->values(),
                'user' => [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ],
            ]);
        }
}
