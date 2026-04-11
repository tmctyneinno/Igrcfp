@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Submissions: {{ $assessment->title }}</h6>
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
            <li class="fw-medium">Submissions</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
    @endif

    <!-- Statistics -->
    <div class="row gy-4 mb-24">
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1 text-sm">Total</span>
                            <h4 class="mb-0">{{ $statistics['total'] }}</h4>
                        </div>
                        <div class="w-40-px h-40-px bg-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:document-text-outline" class="text-white"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1 text-sm">Pending</span>
                            <h4 class="mb-0">{{ $statistics['pending'] }}</h4>
                        </div>
                        <div class="w-40-px h-40-px bg-warning-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:hourglass-outline" class="text-white"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1 text-sm">Graded</span>
                            <h4 class="mb-0">{{ $statistics['graded'] }}</h4>
                        </div>
                        <div class="w-40-px h-40-px bg-success-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:check-circle-outline" class="text-white"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1 text-sm">Passed</span>
                            <h4 class="mb-0">{{ $statistics['passed'] }}</h4>
                        </div>
                        <div class="w-40-px h-40-px bg-info-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:medal-ribbon-outline" class="text-white"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1 text-sm">Failed</span>
                            <h4 class="mb-0">{{ $statistics['failed'] }}</h4>
                        </div>
                        <div class="w-40-px h-40-px bg-danger-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:close-circle-outline" class="text-white"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1 text-sm">Avg Score</span>
                            <h4 class="mb-0">{{ number_format($statistics['average_score'], 1) }}%</h4>
                        </div>
                        <div class="w-40-px h-40-px bg-purple-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:chart-outline" class="text-white"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assessment Info -->
    <div class="card mb-24">
        <div class="card-body p-20">
            <div class="row">
                <div class="col-md-8">
                    <h6 class="mb-2">{{ $assessment->title }}</h6>
                    <p class="text-secondary-light mb-2">{{ $assessment->course->title ?? 'N/A' }}</p>
                    <div class="d-flex gap-4 text-sm">
                        <span>Total Marks: <strong>{{ $assessment->total_marks }}</strong></span>
                        <span>Passing: <strong>{{ $assessment->passing_score }}%</strong></span>
                        <span>Due: <strong>{{ $assessment->due_date ? $assessment->due_date->format('M d, Y') : 'N/A' }}</strong></span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('admin.projects.show', $assessment) }}" class="btn btn-outline-primary btn-sm">
                        View Project Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions Table -->
    <div class="card">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h6 class="card-title mb-0">Student Submissions ({{ $submissions->total() }})</h6>
        </div>
        <div class="card-body p-24">
            @if($submissions->count() > 0)
                <div class="table-responsive">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Submitted</th>
                                <th>File</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Graded</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $submission->user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($submission->user->name) }}" 
                                             class="w-32-px h-32-px rounded-circle">
                                        <div>
                                            <span class="fw-medium">{{ $submission->user->name }}</span>
                                            <p class="text-sm text-secondary-light mb-0">{{ $submission->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span>{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y') : 'N/A' }}</span>
                                        <p class="text-sm text-secondary-light mb-0">
                                            {{ $submission->submitted_at ? $submission->submitted_at->format('H:i') : '' }}
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    @if($submission->submission_file_path)
                                        <a href="{{ $submission->submission_file_url }}" target="_blank" class="text-primary-600">
                                            <iconify-icon icon="solar:file-text-outline"></iconify-icon>
                                            {{ Str::limit($submission->submission_file_name, 20) }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($submission->percentage !== null)
                                        <span class="fw-semibold {{ $submission->passed ? 'text-success-600' : 'text-danger-600' }}">
                                            {{ number_format($submission->percentage, 1) }}%
                                        </span>
                                        <p class="text-sm text-secondary-light mb-0">
                                            {{ $submission->score }}/{{ $assessment->total_marks }}
                                        </p>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'submitted' => 'warning',
                                            'graded' => 'success',
                                            'late' => 'danger',
                                            'draft' => 'secondary'
                                        ];
                                        $statusLabels = [
                                            'submitted' => 'Pending',
                                            'graded' => 'Graded',
                                            'late' => 'Late',
                                            'draft' => 'Draft'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$submission->status] ?? 'secondary' }}-600 text-white px-12 py-6 radius-8">
                                        {{ $statusLabels[$submission->status] ?? ucfirst($submission->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($submission->graded_at)
                                        <span>{{ $submission->graded_at->format('M d, Y') }}</span>
                                        <p class="text-sm text-secondary-light mb-0">
                                            by {{ $submission->grader->name ?? 'System' }}
                                        </p>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-2 justify-content-center">
                                        <a href="{{ route('admin.projects.submission.view', $submission) }}" 
                                           class="bg-info-focus bg-hover-info-200 text-info-600 w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle"
                                           title="View Details">
                                            <iconify-icon icon="majesticons:eye-line"></iconify-icon>
                                        </a>
                                        @if($submission->status === 'submitted' || $submission->status === 'late')
                                            <button 
                                                onclick="openGradeModal({{ $submission->id }}, '{{ $submission->user->name }}', {{ $assessment->total_marks }})"
                                                class="bg-success-focus bg-hover-success-200 text-success-600 w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle border-0"
                                                title="Grade">
                                                <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $submissions->links('vendor.pagination.custom') }}
            @else
                <div class="text-center py-5">
                    <iconify-icon icon="solar:document-text-outline" class="icon-4x text-muted mb-3"></iconify-icon>
                    <h6 class="text-muted mb-2">No submissions yet</h6>
                    <p class="text-muted">Students haven't submitted any work for this project.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Grade Modal -->
<div class="modal fade" id="gradeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="gradeForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Grade Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Student: <strong id="studentName"></strong></p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Score <span class="text-danger">*</span></label>
                        <input type="number" name="score" id="scoreInput" class="form-control" step="0.01" min="0" required>
                        <small class="text-muted">Max: <span id="maxMarks"></span></small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Feedback</label>
                        <textarea name="feedback" id="feedbackInput" class="form-control" rows="4" placeholder="Provide feedback to the student..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="passed" id="passedCheck" value="1" checked>
                            <label class="form-check-label" for="passedCheck">
                                Mark as Passed
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Grade</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .w-32-px { width: 32px; }
    .h-32-px { height: 32px; }
    .w-40-px { width: 40px; }
    .h-40-px { height: 40px; }
    .p-16 { padding: 16px; }
    .p-20 { padding: 20px; }
    .icon-4x { font-size: 4rem; }
</style>
@endpush

@push('scripts')
<script>
function openGradeModal(submissionId, studentName, maxMarks) {
    const form = document.getElementById('gradeForm');
    form.action = `/admin/projects/submission/${submissionId}/grade`;
    
    document.getElementById('studentName').textContent = studentName;
    document.getElementById('maxMarks').textContent = maxMarks;
    document.getElementById('scoreInput').max = maxMarks;
    document.getElementById('scoreInput').value = '';
    document.getElementById('feedbackInput').value = '';
    
    new bootstrap.Modal(document.getElementById('gradeModal')).show();
}
</script>
@endpush