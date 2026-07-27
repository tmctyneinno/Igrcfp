<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\MembershipTier;
use App\Services\ActivityLoggerService;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembershipController extends Controller
{
   public function index(Request $request)
{
    // Log membership page view
    ActivityLoggerService::log(
        ActivityLog::EVENT_UPDATED,
        'memberships',
        'Viewed membership plans',
        "User viewed membership plans page",
        null,
        [
            'user_id' => $request->user()->id,
            'has_active_membership' => $request->user()->activeMembership() ? true : false
        ],
        ActivityLog::SEVERITY_INFO
    );

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
                'benefits' => is_array($tier->benefits) ? $tier->benefits : json_decode($tier->benefits ?? '[]', true), // Ensure benefits is an array
                'plans' => $tier->plans->map(function ($plan) {
                    return [
                        'id' => $plan->id,
                        'name' => $plan->name,
                        'price' => $plan->price,
                        'currency' => $plan->currency,
                        'billing_interval' => $plan->billing_interval,
                        'benefits' => is_array($plan->benefits) ? $plan->benefits : json_decode($plan->benefits ?? '[]', true), // Ensure benefits is an array
                    ];
                })->values()->all(), // Convert to plain array
            ];
        })->values()->all(); // Convert outer collection to plain array

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

    public function status(Request $request)
    {
        $membership = $request->user()->memberships()
            ->with('plan.tier')
            ->latest()
            ->first();

        // Log membership status view
        if ($membership) {
            ActivityLoggerService::log(
                ActivityLog::EVENT_UPDATED,
                'memberships',
                'Viewed membership status',
                "User checked membership status: {$membership->status}",
                $membership,
                [
                    'user_id' => $request->user()->id,
                    'membership_id' => $membership->id,
                    'status' => $membership->status,
                    'plan_name' => $membership->plan->name ?? 'N/A'
                ],
                ActivityLog::SEVERITY_INFO
            );
        }

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
        // Check if plan is active
        if (!$plan->is_active) {
            // Log attempt to add inactive plan
            ActivityLoggerService::log(
                ActivityLog::EVENT_UPDATED,
                'memberships',
                'Attempted to add inactive membership plan to cart',
                "User attempted to add inactive plan: {$plan->name}",
                $plan,
                [
                    'user_id' => $request->user()->id,
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'reason' => 'plan_inactive'
                ],
                ActivityLog::SEVERITY_WARNING
            );

            return redirect()
                ->route('dashboard.memberships.index')
                ->with('error', 'This membership plan is currently unavailable.');
        }

        // Check if user already has active membership
        if ($request->user()->activeMembership()) {
            $activeMembership = $request->user()->activeMembership();

            // Log attempt when user already has active membership
            ActivityLoggerService::log(
                ActivityLog::EVENT_UPDATED,
                'memberships',
                'Attempted to add plan while having active membership',
                "User with active membership attempted to add plan: {$plan->name}",
                $plan,
                [
                    'user_id' => $request->user()->id,
                    'plan_id' => $plan->id,
                    'active_membership_id' => $activeMembership->id,
                    'reason' => 'already_active_member'
                ],
                ActivityLog::SEVERITY_WARNING
            );

            return redirect()
                ->route('dashboard.memberships.status')
                ->with('info', 'You already have an active membership.');
        }

        // Check if user has pending membership
        $pendingMembership = $request->user()->memberships()
            ->whereIn('status', ['pending_payment', 'pending_approval'])
            ->exists();

        if ($pendingMembership) {
            // Log attempt when user has pending membership
            ActivityLoggerService::log(
                ActivityLog::EVENT_UPDATED,
                'memberships',
                'Attempted to add plan while having pending membership',
                "User with pending membership attempted to add plan: {$plan->name}",
                $plan,
                [
                    'user_id' => $request->user()->id,
                    'plan_id' => $plan->id,
                    'reason' => 'pending_membership_exists'
                ],
                ActivityLog::SEVERITY_WARNING
            );

            return redirect()
                ->route('dashboard.memberships.status')
                ->with('info', 'You already have a pending membership request.');
        }

        // Find or create active cart
        $cart = $request->user()->carts()->firstOrCreate(
            ['status' => 'active'],
            ['session_id' => session()->getId()]
        );

        // Check if cart already has a membership item
        $existingMembershipItem = $cart->items()->where('item_type', 'membership')->first();
        if ($existingMembershipItem) {
            // Log attempt when cart already has membership
            ActivityLoggerService::log(
                ActivityLog::EVENT_UPDATED,
                'memberships',
                'Membership plan already in cart',
                "User attempted to add another membership while one exists in cart",
                $plan,
                [
                    'user_id' => $request->user()->id,
                    'cart_id' => $cart->id,
                    'existing_item_id' => $existingMembershipItem->id,
                    'reason' => 'membership_already_in_cart'
                ],
                ActivityLog::SEVERITY_WARNING
            );

            return redirect()
                ->route('dashboard.memberships.checkout')
                ->with('info', 'You already have a membership plan in your cart.');
        }

        // Add membership to cart
        $cartItem = $cart->items()->create([
            'item_type' => 'membership',
            'membership_plan_id' => $plan->id,
            'price' => $plan->price,
            'quantity' => 1,
        ]);

        $cart->updateTotals();

        // Log successful addition to cart
        ActivityLoggerService::log(
            ActivityLog::EVENT_CREATED,
            'memberships',
            'Membership plan added to cart',
            "User added {$plan->name} membership to cart",
            $plan,
            [
                'user_id' => $request->user()->id,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'plan_price' => $plan->price,
                'cart_id' => $cart->id,
                'cart_item_id' => $cartItem->id,
                'billing_interval' => $plan->billing_interval
            ],
            ActivityLog::SEVERITY_INFO
        );

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
            // Log empty cart checkout attempt
            ActivityLoggerService::log(
                ActivityLog::EVENT_UPDATED,
                'memberships',
                'Checkout attempted with empty membership cart',
                "User attempted checkout with empty membership cart",
                null,
                [
                    'user_id' => $request->user()->id,
                    'reason' => 'empty_cart'
                ],
                ActivityLog::SEVERITY_WARNING
            );

            return redirect()
                ->route('dashboard.memberships.index')
                ->with('error', 'Your membership cart is empty.');
        }

        // Log checkout page view
        ActivityLoggerService::log(
            ActivityLog::EVENT_UPDATED,
            'memberships',
            'Viewed membership checkout',
            "User viewed membership checkout page",
            null,
            [
                'user_id' => $request->user()->id,
                'cart_id' => $cart->id,
                'cart_total' => $cart->total_amount,
                'items_count' => $membershipItems->count(),
                'membership_plans' => $membershipItems->pluck('membershipPlan.name')->toArray()
            ],
            ActivityLog::SEVERITY_INFO
        );

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