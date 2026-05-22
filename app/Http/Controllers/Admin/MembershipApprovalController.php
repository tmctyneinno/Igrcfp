<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Notification;
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

        Notification::create([
            'user_id' => $membership->user_id,
            'type' => Notification::TYPE_MEMBERSHIP_APPROVED,
            'title' => 'Membership Approved',
            'message' => "Your {$membership->plan?->name} membership has been approved.",
            'data' => ['membership_id' => $membership->id],
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

        Notification::create([
            'user_id' => $membership->user_id,
            'type' => Notification::TYPE_MEMBERSHIP_DECLINED,
            'title' => 'Membership Declined',
            'message' => "Your {$membership->plan?->name} membership request has been declined.",
            'data' => ['membership_id' => $membership->id],
        ]);

        return redirect()
            ->route('admin.membership-approvals.index')
            ->with('success', 'Membership declined.');
    }
}
