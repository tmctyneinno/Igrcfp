@extends('layouts.learning-center', ['title' => 'Mentor Profile'])

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center text-2xl font-semibold text-blue-900">
                        {{ strtoupper(substr($mentor->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $mentor->user->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $mentor->title ?? 'Mentor' }}</p>
                        <p class="text-sm text-gray-600">{{ $mentor->domain ?? 'General' }} • {{ $mentor->region ?? 'Global' }}</p>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    <p><span class="font-semibold text-gray-800">Rating:</span> {{ number_format($mentor->rating, 1) }}</p>
                    <p><span class="font-semibold text-gray-800">Completed:</span> {{ $mentor->completed_mentorships_count }}</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Bio</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $mentor->bio ?? 'No bio provided.' }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Expertise Summary</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $mentor->expertise_summary ?? 'No expertise summary provided.' }}</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-2">Languages</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach((array) ($mentor->languages ?? []) as $lang)
                            <span class="text-xs font-semibold bg-slate-100 text-slate-700 px-3 py-1 rounded-full">{{ $lang }}</span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-2">Skills</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach((array) ($mentor->skills ?? []) as $skill)
                            <span class="text-xs font-semibold bg-blue-50 text-blue-800 px-3 py-1 rounded-full">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-2">Certifications</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach((array) ($mentor->certifications ?? []) as $cert)
                            <span class="text-xs font-semibold bg-emerald-50 text-emerald-800 px-3 py-1 rounded-full">{{ $cert }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                @if($mentor->availability_status === 'not_taking')
                    <span class="rounded-lg bg-amber-50 text-amber-800 px-4 py-2 text-sm font-semibold">Not accepting applications</span>
                @elseif($mentor->remainingCapacity() <= 0)
                    <span class="rounded-lg bg-rose-50 text-rose-800 px-4 py-2 text-sm font-semibold">No capacity available</span>
                @else
                    <a href="{{ route('dashboard.mentors.apply', $mentor) }}" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800 transition">
                        Apply for Mentorship
                    </a>
                @endif
                <a href="{{ route('dashboard.mentors.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                    Back to Mentors
                </a>
            </div>
        </div>
    </div>
@endsection
