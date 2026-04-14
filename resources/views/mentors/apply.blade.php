@extends('layouts.learning-center', ['title' => 'Apply to Become a Mentor'])

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Apply to Become a Mentor</h2>
            <p class="text-sm text-gray-600 mb-6">Share your expertise so we can match you with mentees.</p>

            @if($existingApplication)
                <div class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-gray-700">
                    Latest application status: <span class="font-semibold">{{ ucfirst($existingApplication->status) }}</span>
                    @if($existingApplication->admin_feedback)
                        <p class="mt-2 text-gray-600">Admin feedback: {{ $existingApplication->admin_feedback }}</p>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('dashboard.mentors.apply-to-become.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                        @error('title')
                            <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Domain *</label>
                        <input type="text" name="domain" value="{{ old('domain') }}"
                            class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                        @error('domain')
                            <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Region *</label>
                        <input type="text" name="region" value="{{ old('region') }}"
                            class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                        @error('region')
                            <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                        <input type="text" name="country" value="{{ old('country') }}"
                            class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                        @error('country')
                            <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bio *</label>
                    <textarea name="bio" rows="4" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>{{ old('bio') }}</textarea>
                    @error('bio')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expertise Summary *</label>
                    <textarea name="expertise_summary" rows="4" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>{{ old('expertise_summary') }}</textarea>
                    @error('expertise_summary')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Availability Status *</label>
                        <select name="availability_status" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="taking" @selected(old('availability_status') === 'taking')>Taking mentees</option>
                            <option value="not_taking" @selected(old('availability_status') === 'not_taking')>Not taking</option>
                        </select>
                        @error('availability_status')
                            <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Mentees *</label>
                        <input type="number" name="max_mentees" value="{{ old('max_mentees', 3) }}"
                            class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                        @error('max_mentees')
                            <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Languages</label>
                    <input type="text" name="languages" value="{{ old('languages') }}" placeholder="Comma-separated"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Skills</label>
                    <input type="text" name="skills" value="{{ old('skills') }}" placeholder="Comma-separated"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Certifications</label>
                    <input type="text" name="certifications" value="{{ old('certifications') }}" placeholder="Comma-separated"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800 transition">
                        Submit Application
                    </button>
                    <a href="{{ route('dashboard.mentors.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                        Back to Mentors
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
