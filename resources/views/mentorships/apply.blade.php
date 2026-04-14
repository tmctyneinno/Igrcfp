@extends('layouts.learning-center', ['title' => 'Apply for Mentorship'])

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Apply to {{ $mentor->user->name }}</h2>
            <p class="text-sm text-gray-600 mb-6">Share your goals and availability to start the mentorship process.</p>

            <form method="POST" action="{{ route('dashboard.mentors.apply.store', $mentor) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Goals *</label>
                    <textarea name="goals" rows="4" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>{{ old('goals') }}</textarea>
                    @error('goals')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Duration</label>
                        <input type="text" name="preferred_duration" value="{{ old('preferred_duration') }}"
                            class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" placeholder="e.g. 3 months">
                        @error('preferred_duration')
                            <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Availability</label>
                        <input type="text" name="availability" value="{{ old('availability') }}"
                            class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" placeholder="e.g. Weekends">
                        @error('availability')
                            <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Communication Method</label>
                    <input type="text" name="communication_method" value="{{ old('communication_method') }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" placeholder="e.g. Zoom, Email">
                    @error('communication_method')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="3" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800 transition">
                        Submit Application
                    </button>
                    <a href="{{ route('dashboard.mentors.show', $mentor) }}" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                        Back to Profile
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
