<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipPlanRequest;
use App\Http\Requests\UpdateMembershipPlanRequest;
use App\Models\MembershipPlan;
use App\Models\MembershipTier;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::with('tier')->orderBy('price')->paginate(15);

        return view('admin.memberships.plans.index', compact('plans'));
    }

    public function create()
    {
        $tiers = MembershipTier::orderBy('sort_order')->get();

        return view('admin.memberships.plans.create', compact('tiers'));
    }

    public function store(StoreMembershipPlanRequest $request)
    {
        MembershipPlan::create([
            'tier_id' => $request->input('tier_id'),
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'currency' => $request->input('currency', 'EUR'),
            'billing_interval' => $request->input('billing_interval', 'yearly'),
            'duration_months' => $request->input('duration_months', 12),
            'benefits' => $this->splitLines($request->input('benefits')),
            'is_active' => (bool) $request->input('is_active', true),
        ]);

        return redirect()
            ->route('admin.membership-plans.index')
            ->with('success', 'Membership plan created successfully.');
    }

    public function edit(MembershipPlan $membership_plan)
    {
        $tiers = MembershipTier::orderBy('sort_order')->get();

        return view('admin.memberships.plans.edit', [
            'plan' => $membership_plan,
            'tiers' => $tiers,
        ]);
    }

    public function update(UpdateMembershipPlanRequest $request, MembershipPlan $membership_plan)
    {
        $membership_plan->update([
            'tier_id' => $request->input('tier_id'),
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'currency' => $request->input('currency', 'EUR'),
            'billing_interval' => $request->input('billing_interval', 'yearly'),
            'duration_months' => $request->input('duration_months', 12),
            'benefits' => $this->splitLines($request->input('benefits')),
            'is_active' => (bool) $request->input('is_active', true),
        ]);

        return redirect()
            ->route('admin.membership-plans.index')
            ->with('success', 'Membership plan updated successfully.');
    }

    public function destroy(MembershipPlan $membership_plan)
    {
        $membership_plan->delete();

        return redirect()
            ->route('admin.membership-plans.index')
            ->with('success', 'Membership plan deleted.');
    }

    private function splitLines(?string $value): ?array
    {
        if (!$value) {
            return null;
        }

        $lines = preg_split('/\r\n|\r|\n/', $value);
        $lines = array_filter(array_map('trim', $lines));

        return $lines ?: null;
    }
}
