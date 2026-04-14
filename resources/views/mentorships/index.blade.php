@extends('layouts.learning-center', ['title' => 'Mentorship Management'])

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Mentorship Dashboard</h2>
            <p class="mt-2 text-gray-600">Track your applications and active mentorships.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Applications (Mentee)</h3>
                <div class="space-y-4">
                    @forelse($menteeApplications as $application)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <p class="text-sm text-gray-600">Mentor: <span class="font-semibold text-gray-900">{{ $application->mentorProfile->user->name }}</span></p>
                            <p class="text-xs text-gray-500 mt-1">Status: {{ ucfirst($application->status) }}</p>
                            <p class="text-xs text-gray-500">Submitted: {{ $application->created_at->format('M d, Y') }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No applications submitted yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Mentorships (Mentee)</h3>
                <div class="space-y-4">
                    @forelse($menteeMentorships as $mentorship)
                        <div class="rounded-xl border border-slate-200 p-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Mentor: <span class="font-semibold text-gray-900">{{ $mentorship->mentorProfile->user->name }}</span></p>
                                <p class="text-xs text-gray-500 mt-1">Status: {{ ucfirst($mentorship->status) }}</p>
                            </div>
                            <a href="{{ route('dashboard.mentorships.show', $mentorship) }}" class="text-sm font-semibold text-blue-900 hover:text-blue-700">
                                View
                            </a>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No active mentorships yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        @if($mentorProfile)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Applications to You (Mentor)</h3>
                <div class="space-y-4">
                    @forelse($mentorApplications as $application)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <p class="text-sm text-gray-600">Mentee: <span class="font-semibold text-gray-900">{{ $application->mentee->name }}</span></p>
                            <p class="text-xs text-gray-500 mt-1">Goals: {{ \Illuminate\Support\Str::limit($application->goals, 120) }}</p>
                            <p class="text-xs text-gray-500">Status: {{ ucfirst($application->status) }}</p>

                            @if($application->status === 'pending')
                                <form method="POST" action="{{ route('dashboard.mentorships.decide', $application) }}" class="mt-4 space-y-3">
                                    @csrf
                                    <textarea name="mentor_feedback" rows="2" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" placeholder="Optional feedback"></textarea>
                                    <div class="flex flex-wrap gap-3">
                                        <button type="submit" name="decision" value="accepted" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-500">
                                            Accept
                                        </button>
                                        <button type="submit" name="decision" value="declined" class="px-4 py-2 rounded-lg bg-rose-600 text-white text-sm font-semibold hover:bg-rose-500">
                                            Decline
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No applications yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Active Mentorships (Mentor)</h3>
                <div class="space-y-4">
                    @forelse($mentorMentorships as $mentorship)
                        <div class="rounded-xl border border-slate-200 p-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Mentee: <span class="font-semibold text-gray-900">{{ $mentorship->mentee->name }}</span></p>
                                <p class="text-xs text-gray-500 mt-1">Status: {{ ucfirst($mentorship->status) }}</p>
                            </div>
                            <a href="{{ route('dashboard.mentorships.show', $mentorship) }}" class="text-sm font-semibold text-blue-900 hover:text-blue-700">
                                View
                            </a>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No active mentorships yet.</p>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
@endsection
