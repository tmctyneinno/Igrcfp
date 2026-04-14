@extends('layouts.learning-center', ['title' => 'Mentor Discovery'])

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Find a Mentor</h2>
            <p class="mt-2 text-gray-600">Browse active mentors and apply for guidance tailored to your goals.</p>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by name or domain"
                class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            <input type="text" name="region" value="{{ $filters['region'] ?? '' }}" placeholder="Region"
                class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            <input type="text" name="country" value="{{ $filters['country'] ?? '' }}" placeholder="Country"
                class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            <select name="availability" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">Availability</option>
                <option value="taking" @selected(($filters['availability'] ?? '') === 'taking')>Taking mentees</option>
                <option value="not_taking" @selected(($filters['availability'] ?? '') === 'not_taking')>Not taking</option>
            </select>
            <button type="submit" class="md:col-span-4 inline-flex items-center justify-center rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 transition">
                Apply Filters
            </button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($mentors as $mentor)
                <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-lg font-semibold text-blue-900">
                                {{ strtoupper(substr($mentor->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $mentor->user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $mentor->title ?? 'Mentor' }}</p>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600">
                            <p><span class="font-semibold text-gray-800">Domain:</span> {{ $mentor->domain ?? 'General' }}</p>
                            <p><span class="font-semibold text-gray-800">Region:</span> {{ $mentor->region ?? 'Global' }}</p>
                            <p><span class="font-semibold text-gray-800">Country:</span> {{ $mentor->country ?? 'N/A' }}</p>
                            <p><span class="font-semibold text-gray-800">Availability:</span>
                                {{ $mentor->availability_status === 'taking' ? 'Taking mentees' : 'Not taking' }}
                            </p>
                            <p><span class="font-semibold text-gray-800">Rating:</span> {{ number_format($mentor->rating, 1) }}</p>
                            <p><span class="font-semibold text-gray-800">Completed:</span> {{ $mentor->completed_mentorships_count }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">
                            {{ $mentor->remainingCapacity() }} slots left
                        </span>
                        <a href="{{ route('dashboard.mentors.show', $mentor) }}" class="text-sm font-semibold text-blue-900 hover:text-blue-700">
                            View Profile
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-slate-300 p-10 text-center text-gray-500">
                    No mentors match your filters yet.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $mentors->links() }}
        </div>
    </div>
@endsection
