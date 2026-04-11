@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Project Details</h6>
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
            <li class="fw-medium">{{ Str::limit($assessment->title, 30) }}</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
    @endif

    <div class="row gy-4">
        <!-- Left Column - Project Details -->
        <div class="col-lg-8">
            <!-- Overview Card -->
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0">Project Overview</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.projects.edit', $assessment) }}" class="btn btn-sm btn-outline-primary">
                            <iconify-icon icon="lucide:edit" class="me-1"></iconify-icon>
                            Edit
                        </a>
                        <a href="{{ route('admin.projects.submissions', $assessment) }}" class="btn btn-sm btn-outline-info">
                            <iconify-icon icon="solar:users-group-rounded-outline" class="me-1"></iconify-icon>
                            Submissions
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start gap-4 mb-4">
                        <span class="badge bg-purple-600 text-white px-12 py-6 radius-8">Project</span>
                        
                        @php
                            $statusColors = ['active' => 'success', 'draft' => 'warning', 'archived' => 'secondary'];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$assessment->status] ?? 'secondary' }}-600 text-white px-12 py-6 radius-8">
                            {{ ucfirst($assessment->status) }}
                        </span>
                    </div>

                    <h5 class="fw-semibold mb-2">{{ $assessment->title }}</h5>
                    <p class="text-secondary-light">{{ $assessment->description ?: 'No description provided' }}</p>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-secondary-light">Course:</td>
                                    <td class="fw-medium">{{ $assessment->course->title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light">Module:</td>
                                    <td class="fw-medium">
                                        @if($assessment->module)
                                            Module {{ $assessment->module->module_number }}: {{ $assessment->module->title }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light">Total Marks:</td>
                                    <td class="fw-medium">{{ $assessment->total_marks ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light">Passing Score:</td>
                                    <td class="fw-medium">{{ $assessment->passing_score ? $assessment->passing_score . '%' : '—' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-secondary-light">Release Date:</td>
                                    <td class="fw-medium">{{ $assessment->release_date ? $assessment->release_date->format('M d, Y H:i') : 'Immediate' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light">Due Date:</td>
                                    <td class="fw-medium">
                                        @if($assessment->due_date)
                                            {{ $assessment->due_date->format('M d, Y H:i') }}
                                            @if($assessment->is_overdue)
                                                <span class="badge bg-danger-600 text-white ms-2">Overdue</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No deadline</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light">Late Submissions:</td>
                                    <td class="fw-medium">
                                        <span class="badge bg-{{ $assessment->allow_late_submissions ? 'success' : 'secondary' }}-600 text-white">
                                            {{ $assessment->allow_late_submissions ? 'Allowed' : 'Not Allowed' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light">Max File Size:</td>
                                    <td class="fw-medium">{{ $assessment->max_file_size ?? 50 }} MB</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Brief Card -->
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Project Brief</h6>
                </div>
                <div class="card-body">
                    @if($assessment->project_brief)
                        <div class="bg-light p-4 rounded-8">
                            {!! $assessment->project_brief !!}
                        </div>
                    @else
                        <p class="text-muted">No project brief provided.</p>
                    @endif
                </div>
            </div>

            <!-- Deliverables Card -->
            @if($assessment->deliverables)
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Deliverables</h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-4 rounded-8">
                        {!! $assessment->deliverables !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Instructions Card -->
            @if($assessment->instructions)
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Instructions</h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-4 rounded-8">
                        {!! $assessment->instructions !!}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Stats & Resources -->
        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary-light">Total Submissions:</span>
                            <span class="fw-medium">{{ $assessment->submissions_count ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary-light">Pending Grading:</span>
                            <span class="fw-medium {{ ($assessment->pending_grading_count ?? 0) > 0 ? 'text-warning' : '' }}">
                                {{ $assessment->pending_grading_count ?? 0 }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary-light">Average Score:</span>
                            <span class="fw-medium">{{ $assessment->average_score ? number_format($assessment->average_score, 1) . '%' : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary-light">Pass Rate:</span>
                            <span class="fw-medium">{{ $assessment->success_rate ? $assessment->success_rate . '%' : '—' }}</span>
                        </div>
                    </div>
                    
                    <div class="border-top pt-3 mt-3">
                        <a href="{{ route('admin.projects.submissions', $assessment) }}" class="btn btn-outline-primary w-100">
                            View All Submissions →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Project File Card -->
            @if($assessment->file_path)
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Project Template</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-8">
                        <div class="w-50-px h-50-px bg-primary-100 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:file-text-outline" class="text-primary-600 icon-2x"></iconify-icon>
                        </div>
                        <div class="flex-grow-1">
                            <p class="fw-medium mb-1">{{ $assessment->file_name }}</p>
                            <p class="text-sm text-secondary-light mb-0">{{ $assessment->formatted_file_size }}</p>
                        </div>
                        <a href="{{ $assessment->file_url }}" class="btn btn-sm btn-outline-primary" target="_blank">
                            <iconify-icon icon="solar:download-outline"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Submissions Card -->
            @if(($assessment->submissions_count ?? 0) > 0)
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Recent Submissions</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($assessment->submissions()->with('user')->latest()->take(5)->get() as $submission)
                        <div class="list-group-item px-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-grow-1">
                                    <p class="fw-medium mb-1">{{ $submission->user->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-secondary-light mb-0">
                                        {{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y') : 'N/A' }}
                                    </p>
                                </div>
                                @if($submission->percentage !== null)
                                    <span class="badge bg-{{ $submission->passed ? 'success' : 'danger' }}-600 text-white">
                                        {{ number_format($submission->percentage, 1) }}%
                                    </span>
                                @else
                                    <span class="badge bg-warning-600 text-white">Pending</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Danger Zone -->
            <div class="card mt-24 border-danger">
                <div class="card-header bg-danger-50">
                    <h6 class="card-title mb-0 text-danger">Danger Zone</h6>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="confirmDelete()">
                        <iconify-icon icon="fluent:delete-24-regular" class="me-1"></iconify-icon>
                        Delete Project
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" action="{{ route('admin.projects.destroy', $assessment) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
    .w-50-px { width: 50px; }
    .h-50-px { height: 50px; }
    .icon-2x { font-size: 2rem; }
    .bg-danger-50 { background-color: #fef2f2; }
    .rounded-8 { border-radius: 8px; }
    .px-12 { padding-left: 12px; padding-right: 12px; }
    .py-6 { padding-top: 6px; padding-bottom: 6px; }
</style>
@endpush

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this project? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush