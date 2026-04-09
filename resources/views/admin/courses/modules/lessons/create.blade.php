@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Add Lesson to: {{ $module->title }}</h6>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ implode('', $errors->all(':message')) }}
        </div>
    @endif
    <form action="{{ route('admin.courses.modules.lessons.store', [$course->slug, $module->id]) }}" method="POST">
        @csrf
        <div class="row gy-4">

            <!-- LEFT SIDE -->
            <div class="col-lg-8">
                
                <!-- Lesson Info -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Lesson Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-12">
                                <label class="form-label">Lesson Title *</label>
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description"
                                    class="form-control rich-editor @error('short_description') is-invalid @enderror"
                                    rows="3">{{ old('short_description') }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Lesson Content</label>
                                <textarea name="content"
                                    class="form-control rich-editor @error('content') is-invalid @enderror"
                                    rows="10">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Video -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Video & Media</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-12">
                                <label class="form-label">Video URL</label>
                                <input type="url" name="video_url"
                                    class="form-control @error('video_url') is-invalid @enderror"
                                    value="{{ old('video_url') }}">
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Duration (minutes)</label>
                                <input type="number" name="duration"
                                    class="form-control @error('duration') is-invalid @enderror"
                                    value="{{ old('duration') }}">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-4">

                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Settings</h6>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order"
                                class="form-control"
                                value="{{ old('sort_order', $module->lessons->count() + 1) }}">
                        </div>

                        <div class="form-check mb-2">
                            <input type="hidden" name="is_free" value="0">
                            <input type="checkbox" name="is_free" value="1" class="form-check-input"
                                {{ old('is_free') ? 'checked' : '' }}>
                            <label class="form-check-label">Free Preview</label>
                        </div>

                        <div class="form-check mb-3">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" class="form-check-input"
                                {{ old('is_published', true) ? 'checked' : '' }}>
                            <label class="form-check-label">Published</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Create Lesson</button>

                    </div>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')

<!-- ✅ CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const editors = [];
    const textareas = document.querySelectorAll('.rich-editor');

    if (typeof ClassicEditor === 'undefined') {
        console.error('CKEditor not loaded');
        return;
    }

    textareas.forEach((textarea) => {
        ClassicEditor.create(textarea)
            .then(editor => {
                editors.push({ textarea, editor });
            })
            .catch(error => {
                console.error('Editor error:', error);
            });
    });

    // ✅ Attach submit safely to ALL forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function () {
            editors.forEach(item => {
                item.textarea.value = item.editor.getData();
            });
        });
    });

});
</script>

@endpush