<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipTierRequest;
use App\Http\Requests\UpdateMembershipTierRequest;
use App\Models\MembershipTier;

class MembershipTierController extends Controller
{
    public function index()
    {
        $tiers = MembershipTier::orderBy('sort_order')->paginate(15);

        return view('admin.memberships.tiers.index', compact('tiers'));
    }

    public function create()
    {
        return view('admin.memberships.tiers.create');
    }

    public function store(StoreMembershipTierRequest $request)
    {
        MembershipTier::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'benefits' => $this->splitLines($request->input('benefits')),
            'is_active' => (bool) $request->input('is_active', true),
            'sort_order' => $request->input('sort_order', 0),
        ]);

        return redirect()
            ->route('admin.membership-tiers.index')
            ->with('success', 'Membership tier created successfully.');
    }

    public function edit(MembershipTier $membership_tier)
    {
        return view('admin.memberships.tiers.edit', [
            'tier' => $membership_tier,
        ]);
    }

    public function update(UpdateMembershipTierRequest $request, MembershipTier $membership_tier)
    {
        $membership_tier->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'benefits' => $this->splitLines($request->input('benefits')),
            'is_active' => (bool) $request->input('is_active', true),
            'sort_order' => $request->input('sort_order', 0),
        ]);

        return redirect()
            ->route('admin.membership-tiers.index')
            ->with('success', 'Membership tier updated successfully.');
    }

    public function destroy(MembershipTier $membership_tier)
    {
        $membership_tier->delete();

        return redirect()
            ->route('admin.membership-tiers.index')
            ->with('success', 'Membership tier deleted.');
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
