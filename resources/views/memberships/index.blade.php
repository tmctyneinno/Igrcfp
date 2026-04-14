@extends('layouts.learning-center', ['title' => 'Membership Plans'])

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Membership Plans</h2>
            <p class="mt-2 text-gray-600">
                Choose the membership tier that fits your career stage and unlock mentorship access.
            </p>
        </div>

        @if($activeMembership)
            <div class="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                You already have an active membership. Visit your
                <a href="{{ route('dashboard.memberships.status') }}" class="font-semibold underline">membership status</a>
                to review expiry and approvals.
            </div>
        @endif

        <div class="space-y-10">
            @foreach($tiers as $tier)
                <div>
                    <div class="mb-4 flex flex-col gap-1">
                        <span class="text-s uppercase tracking-widest text-gray-400">Tier</span>
                        <h3 class="text-2xl font-semibold text-gray-900">{{ $tier->name }}</h3>
                        @if($tier->description)
                            <p class="text-gray-600 text-sm">{{ $tier->description }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($tier->plans as $plan)
                            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 flex flex-col justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h4>
                                    <div class="mt-2 flex items-baseline gap-2">
                                        <span class="text-2xl font-bold text-blue-900">{{ $plan->currency }} {{ number_format($plan->price, 2) }}</span>
                                        <span class="text-xs text-gray-500">/{{ $plan->billing_interval }}</span>
                                    </div>

                                    <ul class="mt-4 space-y-2 text-sm text-gray-600 list-disc list-inside">
                                        @foreach((array) ($plan->benefits ?? $tier->benefits ?? []) as $benefit)
                                            <li>{{ $benefit }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                <form method="POST" action="{{ route('dashboard.memberships.add-to-cart', $plan) }}" class="mt-6">
                                    @csrf
                                    <button type="submit" class="w-full rounded-lg bg-blue-900 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition">
                                        Purchase Plan
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-300 p-6 text-gray-500">
                                No plans available for this tier yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
