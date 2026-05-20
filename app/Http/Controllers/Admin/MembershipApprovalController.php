<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipApprovalController extends Controller
{
    public function index()
    {
        $memberships = Membership::with(['user', 'plan.tier'])
            ->where('status', 'pending_approval')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.memberships.approvals.index', compact('memberships'));
    }

    public function approve(Membership $membership)
    {
        if ($membership->status !== 'pending_approval') {
            return redirect()
                ->route('admin.membership-approvals.index')
                ->with('info', 'This membership is no longer pending.');
        }

        $duration = $membership->plan?->duration_months ?? 12;
        $start = now();

        $membership->update([
            'status' => 'active',
            'approved_at' => now(),
            'starts_at' => $start,
            'expires_at' => $start->copy()->addMonths($duration),
        ]);

        return redirect()
            ->route('admin.membership-approvals.index')
            ->with('success', 'Membership approved.');
    }

    public function decline(Membership $membership)
    {
        if ($membership->status !== 'pending_approval') {
            return redirect()
                ->route('admin.membership-approvals.index')
                ->with('info', 'This membership is no longer pending.');
        }

        $membership->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()
            ->route('admin.membership-approvals.index')
            ->with('success', 'Membership declined.');
    }
}
