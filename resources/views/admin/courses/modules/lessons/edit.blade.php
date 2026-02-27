{{-- resources/views/admin/courses/modules/lessons/edit.blade.php --}}

@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Edit Lesson: {{ $lesson->title }}</h6>
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
                    {{ Str::limit($module->title, 15) }}
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.courses.modules.lessons.index', [$course->id, $module->id]) }}" class="hover-text-primary">
                    Lessons
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Edit</li>
        </ul>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.courses.modules.lessons.update', [$course->id, $module->id, $lesson->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
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
                                       value="{{ old('title', $lesson->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" 
                                          rows="3">{{ old('short_description', $lesson->short_description) }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Content</label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" 
                                          rows="8">{{ old('content', $lesson->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Video URL</label>
                                <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror" 
                                       value="{{ old('video_url', $lesson->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Duration (minutes)</label>
                                <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" 
                                       value="{{ old('duration', $lesson->duration) }}" min="1">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Video Embed Code (Optional)</label>
                                <textarea name="video_embed_code" class="form-control @error('video_embed_code') is-invalid @enderror" 
                                          rows="3" placeholder="<iframe src='...'></iframe>">{{ old('video_embed_code', $lesson->video_embed_code) }}</textarea>
                                @error('video_embed_code')
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
                                       value="{{ old('sort_order', $lesson->sort_order) }}">
                                <small class="text-muted">Order in which lesson appears in the module</small>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_free" id="is_free" value="1" 
                                           {{ old('is_free', $lesson->is_free) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_free">
                                        Free Preview Lesson
                                    </label>
                                    <small class="text-muted d-block">Allow non-enrolled users to preview this lesson</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" 
                                           {{ old('is_published', $lesson->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        Published
                                    </label>
                                    <small class="text-muted d-block">Unpublished lessons are hidden from students</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        Update Lesson
                                    </button>
                                    <a href="{{ route('admin.courses.modules.lessons.index', [$course->id, $module->id]) }}" 
                                       class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Module Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            @if($course->image)
                                <img src="{{ asset('storage/'.$course->image) }}" alt="{{ $course->title }}" 
                                     class="rounded-8" style="width: 40px; height: 40px; object-fit: cover;">
                            @endif
                            <div>
                                <h6 class="mb-0">{{ Str::limit($course->title, 20) }}</h6>
                                <small class="text-muted">{{ $course->code ?? '' }}</small>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Module:</span>
                                <span class="fw-medium">{{ $module->title }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Module Number:</span>
                                <span class="fw-medium">{{ $module->module_number }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Lessons:</span>
                                <span class="fw-medium">{{ $module->lessons->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Created:</span>
                                <span class="fw-medium">{{ $lesson->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Last Updated:</span>
                                <span class="fw-medium">{{ $lesson->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Danger Zone</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Once you delete a lesson, there is no going back. Please be certain.</p>
                        <form action="{{ route('admin.courses.modules.lessons.destroy', [$course->id, $module->id, $lesson->id]) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you absolutely sure you want to delete this lesson? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <iconify-icon icon="mdi:trash"></iconify-icon>
                                Delete Lesson
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection