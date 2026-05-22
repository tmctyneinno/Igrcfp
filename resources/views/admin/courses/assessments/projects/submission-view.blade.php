@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Submission Details</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.projects.index') }}" class="hover-text-primary">Projects</a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.projects.submissions', $submission->assessment) }}" class="hover-text-primary">
                    {{ Str::limit($submission->assessment->title, 30) }}
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Submission #{{ $submission->id }}</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
    @endif

    <div class="row gy-4">
        <!-- Left Column - Student & Submission Info -->
        <div class="col-lg-5">
            <!-- Student Info Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Student Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-16">
                        <img src="{{ $submission->user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($submission->user->name) }}" 
                             class="w-25-px h-25-px rounded-circle">
                        <div>
                            <h6 class="mb-1">{{ $submission->user->name }}</h6>
                            <p class="text-secondary-light mb-0">{{ $submission->user->email }}</p>
                        </div>
                    </div>
                    
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-secondary-light">Student ID:</td>
                            <td class="fw-medium">{{ $submission->user->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Course:</td>
                            <td class="fw-medium">{{ $submission->assessment->course->title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Module:</td>
                            <td class="fw-medium">
                                @if($submission->assessment->module)
                                    Module {{ $submission->assessment->module->module_number }}: {{ $submission->assessment->module->title }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Submission Info Card -->
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Submission Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-secondary-light">Status:</td>
                            <td>
                                <span class="badge {{ $submission->status_badge }} text-white px-12 py-6 radius-8">
                                    {{ $submission->status_text }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Submitted:</td>
                            <td class="fw-medium">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Attempt:</td>
                            <td class="fw-medium">#{{ $submission->attempt_number ?? 1 }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">IP Address:</td>
                            <td class="fw-medium">{{ $submission->ip_address ?? 'N/A' }}</td>
                        </tr>
                    </table>

                    @if($submission->submission_notes)
                    <div class="mt-16">
                        <label class="form-label fw-semibold">Student Notes:</label>
                        <div class="bg-light p-12 rounded-8">
                            <p class="mb-0">{{ $submission->submission_notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Grading Card (if graded) -->
            @if($submission->status == 'graded')
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Grading Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-16">
                        <div class="display-4 fw-bold {{ $submission->passed ? 'text-success-600' : 'text-danger-600' }}">
                            {{ number_format($submission->percentage, 1) }}%
                        </div>
                        <p class="text-secondary-light mb-0">
                            Score: {{ $submission->score }} / {{ $submission->assessment->total_marks }}
                        </p>
                        <p class="mt-2">
                            <span class="badge {{ $submission->passed ? 'bg-success-600' : 'bg-danger-600' }} text-white px-16 py-8 radius-8">
                                {{ $submission->passed ? 'PASSED' : 'FAILED' }}
                            </span>
                            <span class="ms-2 text-secondary-light">Passing: {{ $submission->assessment->passing_score }}%</span>
                        </p>
                    </div>
                    
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-secondary-light">Graded By:</td>
                            <td class="fw-medium">{{ $submission->grader->name ?? 'System' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Graded At:</td>
                            <td class="fw-medium">{{ $submission->graded_at ? $submission->graded_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                        </tr>
                    </table>
                    
                    @if($submission->feedback)
                    <div class="mt-16">
                        <label class="form-label fw-semibold">Feedback:</label>
                        <div class="bg-light p-12 rounded-8">
                            <p class="mb-0">{{ $submission->feedback }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - File & Grading Form -->
        <div class="col-lg-7">
            <!-- Submitted File Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Submitted File</h6>
                </div>
                <div class="card-body">
                    @if($submission->submission_file_path)
                        <div class="d-flex align-items-center gap-3 p-4 bg-light rounded-8 mb-4">
                            <div class="w-60-px h-60-px bg-primary-100 rounded-circle d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:file-text-outline" class="text-primary-600 icon-2x"></iconify-icon>
                            </div>
                            <div class="flex-grow-1">
                                <p class="fw-medium mb-1">{{ $submission->submission_file_name }}</p>
                                <p class="text-sm text-secondary-light mb-0">
                                    {{ round($submission->submission_file_size / 1024, 2) }} KB
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ $submission->submission_file_url }}" target="_blank" class="btn btn-outline-primary">
                                    <iconify-icon icon="solar:eye-outline" class="me-1"></iconify-icon>
                                    View
                                </a>
                                <a href="{{ $submission->submission_file_url }}" download class="btn btn-outline-success">
                                    <iconify-icon icon="solar:download-outline" class="me-1"></iconify-icon>
                                    Download
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">No file submitted.</p>
                    @endif
                </div>
            </div>

            <!-- Grade Submission Form -->
            @if($submission->status == 'submitted' || $submission->status == 'late')
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Grade This Submission</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.projects.submission.grade', $submission) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Score <span class="text-danger">*</span></label>
                            <input type="number" name="score" class="form-control" step="0.01" 
                                   min="0" max="{{ $submission->assessment->total_marks }}" required>
                            <small class="text-muted">Max: {{ $submission->assessment->total_marks }}</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Feedback</label>
                            <textarea name="feedback" class="form-control" rows="5" placeholder="Provide feedback to the student..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="passed" id="passedCheck" value="1" checked>
                                <label class="form-check-label" for="passedCheck">
                                    Mark as Passed
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">Submit Grade</button>
                            <a href="{{ route('admin.projects.submissions', $submission->assessment) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Project Brief Reference -->
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Project Reference</h6>
                </div>
                <div class="card-body">
                    <h6 class="fw-semibold">{{ $submission->assessment->title }}</h6>
                    <p class="text-secondary-light mb-3">{{ Str::limit(strip_tags($submission->assessment->project_brief), 200) }}</p>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.projects.show', $submission->assessment) }}" class="text-primary-600">
                            View Full Project Brief →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .w-60-px { width: 60px; }
    .h-60-px { height: 60px; }
    .icon-2x { font-size: 2rem; }
    .p-12 { padding: 12px; }
    .mb-16 { margin-bottom: 16px; }
    .mt-16 { margin-top: 16px; }
    .px-12 { padding-left: 12px; padding-right: 12px; }
    .py-6 { padding-top: 6px; padding-bottom: 6px; }
    .px-16 { padding-left: 16px; padding-right: 16px; }
    .py-8 { padding-top: 8px; padding-bottom: 8px; }
    .rounded-8 { border-radius: 8px; }
</style>
@endpush