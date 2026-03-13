@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">{{ isset($assessment) ? 'Edit' : 'Create' }} Assessment</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.assessments.index') }}" class="hover-text-primary">Assessments</a>
            </li>
            <li>-</li>
            <li class="fw-medium">{{ isset($assessment) ? 'Edit' : 'Create' }}</li>
        </ul>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body p-24">
            <form action="{{ isset($assessment) ? route('admin.assessments.update', $assessment->id) : route('admin.assessments.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @if(isset($assessment)) @method('PUT') @endif

                <div class="row gy-4">
                    <!-- Basic Information -->
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3">Basic Information</h6>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Assessment Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $assessment->title ?? '') }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Course <span class="text-danger">*</span></label>
                        <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" 
                                    {{ old('course_id', $assessment->course_id ?? '') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4">{{ old('description', $assessment->description ?? '') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Assessment Settings -->
                    <div class="col-12 mt-4">
                        <h6 class="fw-semibold mb-3">Assessment Settings</h6>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Type</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="exam" {{ old('type', $assessment->type ?? '') == 'exam' ? 'selected' : '' }}>Timed Online Exam</option>
                            <option value="assignment" {{ old('type', $assessment->type ?? '') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                            <option value="quiz" {{ old('type', $assessment->type ?? '') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                            <option value="project" {{ old('type', $assessment->type ?? '') == 'project' ? 'selected' : '' }}>Project</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="draft" {{ old('status', $assessment->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ old('status', $assessment->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="archived" {{ old('status', $assessment->status ?? '') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Duration (minutes)</label>
                        <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" 
                               value="{{ old('duration', $assessment->duration ?? '') }}">
                        @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Total Marks</label>
                        <input type="number" name="total_marks" class="form-control @error('total_marks') is-invalid @enderror" 
                               value="{{ old('total_marks', $assessment->total_marks ?? '') }}">
                        @error('total_marks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Dates -->
                    <div class="col-12 mt-4">
                        <h6 class="fw-semibold mb-3">Schedule</h6>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Due Date</label>
                        <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" 
                               value="{{ old('due_date', isset($assessment) ? date('Y-m-d', strtotime($assessment->due_date)) : '') }}">
                        @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Due Time</label>
                        <input type="time" name="due_time" class="form-control @error('due_time') is-invalid @enderror" 
                               value="{{ old('due_time', isset($assessment) ? date('H:i', strtotime($assessment->due_date)) : '') }}">
                        @error('due_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Release Date</label>
                        <input type="date" name="release_date" class="form-control @error('release_date') is-invalid @enderror" 
                               value="{{ old('release_date', isset($assessment) ? date('Y-m-d', strtotime($assessment->release_date)) : '') }}">
                        @error('release_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Release Time</label>
                        <input type="time" name="release_time" class="form-control @error('release_time') is-invalid @enderror" 
                               value="{{ old('release_time', isset($assessment) ? date('H:i', strtotime($assessment->release_date)) : '') }}">
                        @error('release_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- File Upload -->
                    <div class="col-12 mt-4">
                        <h6 class="fw-semibold mb-3">Assessment File</h6>
                    </div>

                    <div class="col-12">
                        <div class="upload-file-area p-5 text-center border rounded-3 bg-light">
                            <iconify-icon icon="solar:cloud-upload-outline" class="icon-3x text-muted mb-3"></iconify-icon>
                            <h6 class="mb-2">Drag and drop your file here</h6>
                            <p class="text-muted mb-3">or</p>
                            <label class="btn btn-outline-primary">
                                Browse Files
                                <input type="file" name="assessment_file" class="d-none" accept=".pdf,.doc,.docx,.xlsx,.zip">
                            </label>
                            <p class="text-sm text-muted mt-3">
                                Supported formats: PDF, DOCX, XLSX, ZIP. Max size: 50MB
                            </p>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="col-12 mt-4">
                        <h6 class="fw-semibold mb-3">Additional Options</h6>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_timed" id="isTimed" value="1" 
                                {{ old('is_timed', $assessment->is_timed ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isTimed">
                                Timed Assessment
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="needs_manual_marking" id="needsManualMarking" value="1" 
                                {{ old('needs_manual_marking', $assessment->needs_manual_marking ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="needsManualMarking">
                                Requires Manual Marking
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="allow_late_submissions" id="allowLateSubmissions" value="1" 
                                {{ old('allow_late_submissions', $assessment->allow_late_submissions ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allowLateSubmissions">
                                Allow Late Submissions
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-12 mt-5">
                        <hr>
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('admin.assessments.index') }}" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                {{ isset($assessment) ? 'Update Assessment' : 'Create Assessment' }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .upload-file-area {
        position: relative;
        transition: all 0.3s;
        cursor: pointer;
    }
    .upload-file-area:hover {
        border-color: #0A1F44 !important;
        background: #e9ecef !important;
    }
</style>
@endpush