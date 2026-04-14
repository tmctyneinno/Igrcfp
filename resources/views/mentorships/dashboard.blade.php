@extends('layouts.learning-center', ['title' => 'Mentorship Details'])

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Mentorship with {{ $mentorship->mentorProfile->user->name }}</h2>
                    <p class="text-sm text-gray-600 mt-1">Mentee: {{ $mentorship->mentee->name }}</p>
                </div>
                <div class="text-sm text-gray-600">
                    <p>Status: <span class="font-semibold text-gray-900">{{ ucfirst($mentorship->status) }}</span></p>
                    <p>Started: {{ optional($mentorship->started_at)->format('M d, Y') ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <form method="POST" action="{{ route('dashboard.mentorships.complete', $mentorship) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-500 transition">
                        Mark as Completed
                    </button>
                </form>
                <a href="{{ route('dashboard.mentorships.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Update</h3>
            <form method="POST" action="{{ route('dashboard.mentorships.updates.store', $mentorship) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Update Type *</label>
                    <select name="type" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm">
                        <option value="milestone">Milestone</option>
                        <option value="session">Session</option>
                        <option value="note">Note</option>
                        <option value="feedback">Feedback</option>
                    </select>
                    @error('type')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm" value="{{ old('title') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled At</label>
                    <input type="datetime-local" name="scheduled_at" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm" value="{{ old('scheduled_at') }}">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Details</label>
                    <textarea name="content" rows="3" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating (Feedback)</label>
                    <input type="number" name="rating" min="1" max="5" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm" value="{{ old('rating') }}">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800 transition">
                        Save Update
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach(['milestone' => 'Milestones', 'session' => 'Sessions', 'note' => 'Notes', 'feedback' => 'Feedback'] as $type => $label)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $label }}</h3>
                    <div class="space-y-3">
                        @forelse($updates->get($type, collect()) as $update)
                            <div class="rounded-xl border border-slate-200 p-4">
                                <p class="text-sm font-semibold text-gray-900">{{ $update->title ?? ucfirst($type) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $update->created_at->format('M d, Y') }}</p>
                                @if($update->scheduled_at)
                                    <p class="text-xs text-gray-500">Scheduled: {{ $update->scheduled_at->format('M d, Y H:i') }}</p>
                                @endif
                                @if($update->rating)
                                    <p class="text-xs text-gray-500">Rating: {{ $update->rating }}/5</p>
                                @endif
                                <p class="text-sm text-gray-600 mt-2">{{ $update->content }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No {{ strtolower($label) }} yet.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
