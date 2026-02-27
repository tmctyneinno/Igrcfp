@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Add Lesson to: {{ $module->title }}</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.courses.index') }}" class="hover-text-primary">Courses</a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.courses.show', $course->id) }}" class="hover-text-primary">{{ Str::limit($course->title, 20) }}</a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.courses.modules.edit', [$course->id, $module->id]) }}" class="hover-text-primary">
                    {{ Str::limit($module->title, 20) }}
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Add Lesson</li>
        </ul>
    </div>

    <form action="{{ route('admin.courses.modules.lessons.store', [$course->slug, $module->id]) }}" method="POST">
        @csrf
        <div class="row gy-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Lesson Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Lesson Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" 
                                          rows="3">{{ old('short_description') }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Lesson Content</label>
                                <textarea id="content" name="content" class="form-control rich-editor @error('content') is-invalid @enderror" 
                                          rows="10">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Video & Media</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Video URL</label>
                                <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror" 
                                       placeholder="https://www.youtube.com/watch?v=..." value="{{ old('video_url') }}">
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Video Embed Code</label>
                                <textarea name="video_embed_code" class="form-control @error('video_embed_code') is-invalid @enderror" 
                                          rows="3" placeholder="<iframe src='...'></iframe>">{{ old('video_embed_code') }}</textarea>
                                @error('video_embed_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Duration (minutes)</label>
                                <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" 
                                       value="{{ old('duration') }}" min="1">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Lesson Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                       value="{{ old('sort_order', $module->lessons->count() + 1) }}">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_free" id="is_free" value="1" 
                                           {{ old('is_free') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_free">
                                        Free Preview Lesson
                                    </label>
                                    <p class="text-sm text-muted mb-0">Allow non-enrolled users to preview</p>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" 
                                           {{ old('is_published', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        Published
                                    </label>
                                    <p class="text-sm text-muted mb-0">Unpublished lessons are hidden from students</p>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border-top pt-3 mt-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                            Create Lesson
                                        </button>
                                        <a href="{{ route('admin.courses.modules.edit', [$course->id, $module->id]) }}" 
                                           class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Module Info</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Module:</strong> {{ $module->title }}</p>
                        <p><strong>Course:</strong> {{ $course->title }}</p>
                        <p><strong>Existing Lessons:</strong> {{ $module->lessons->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection