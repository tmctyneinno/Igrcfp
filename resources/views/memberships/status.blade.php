@extends('layouts.learning-center', ['title' => 'Membership Status'])

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Your Membership</h2>
            <p class="mt-2 text-gray-600">Track your membership approval, renewal, and next steps.</p>
        </div>

        @if(!$membership)
            <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No membership found</h3>
                <p class="text-gray-600 mb-6">Select a plan to unlock mentorship access and member benefits.</p>
                <a href="{{ route('dashboard.memberships.index') }}" class="inline-flex items-center px-6 py-3 rounded-lg bg-blue-900 text-white font-semibold hover:bg-blue-800 transition">
                    View Membership Plans
                </a>
            </div>
        @else
            @php
                $status = $membership->status;
                $statusLabel = $membership->statusLabel();
                $statusClasses = match ($status) {
                    'active' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
                    'pending_approval' => 'border-amber-200 bg-amber-50 text-amber-800',
                    'cancelled' => 'border-rose-200 bg-rose-50 text-rose-800',
                    'expired' => 'border-gray-200 bg-gray-50 text-gray-700',
                    default => 'border-slate-200 bg-white text-gray-700',
                };
            @endphp

            <div class="rounded-2xl border {{ $statusClasses }} p-5 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-xs uppercase tracking-widest">Status</p>
                        <h3 class="text-2xl font-semibold">{{ $statusLabel }}</h3>
                    </div>
                    <div class="text-sm">
                        @if($membership->status === 'pending_approval')
                            Your membership is awaiting admin approval. We will notify you once it is approved.
                        @elseif($membership->status === 'active')
                            Your membership is active. You can apply for mentorship and manage your mentor profile.
                        @elseif($membership->status === 'expired')
                            Your membership has expired. Renew to restore access.
                        @elseif($membership->status === 'cancelled')
                            This membership was cancelled. Contact support if this is unexpected.
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Plan Details</h4>
                    <p class="text-sm text-gray-600">Plan: <span class="font-semibold text-gray-900">{{ $membership->plan?->name }}</span></p>
                    <p class="text-sm text-gray-600">Tier: <span class="font-semibold text-gray-900">{{ $membership->plan?->tier?->name }}</span></p>
                    <p class="text-sm text-gray-600">Billing: <span class="font-semibold text-gray-900">{{ $membership->plan?->billing_interval }}</span></p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h4>
                    <p class="text-sm text-gray-600">Purchased: <span class="font-semibold text-gray-900">{{ optional($membership->purchased_at)->format('M d, Y') ?? 'N/A' }}</span></p>
                    <p class="text-sm text-gray-600">Approved: <span class="font-semibold text-gray-900">{{ optional($membership->approved_at)->format('M d, Y') ?? 'Pending' }}</span></p>
                    <p class="text-sm text-gray-600">Expires: <span class="font-semibold text-gray-900">{{ optional($membership->expires_at)->format('M d, Y') ?? 'N/A' }}</span></p>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('dashboard.mentors.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800 transition">
                    Discover Mentors
                </a>
                <a href="{{ route('dashboard.mentorships.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                    Mentorship Dashboard
                </a>
            </div>
        @endif
    </div>
@endsection
