@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Assessments Management</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Assessments</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row gy-4 mb-24">
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Total Assessments</span>
                            <h4 class="mb-0">{{ $statistics['total'] ?? 0 }}</h4>
                        </div>
                        <div class="w-40-px h-40-px bg-purple-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:document-text-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Active</span>
                            <h4 class="mb-0">{{ $statistics['active'] ?? 0 }}</h4>
                        </div>
                        <div class="w-40-px h-40-px bg-green-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:check-read-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Submissions</span>
                            <h4 class="mb-0">{{ $statistics['submissions'] ?? 0 }}</h4>
                        </div>
                        <div class="w-40-px h-40-px bg-blue-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:upload-square-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Pending Grading</span>
                            <h4 class="mb-0">{{ $statistics['pending_grading'] ?? 0 }}</h4>
                        </div>
                        <div class="w-40-px h-40-px bg-yellow-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:clock-circle-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Filter Dropdown -->
    <div class="card h-100 p-0 radius-12 mb-24">
        <div class="card-body p-24">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Select Course</label>
                    <select class="form-select" id="courseSelect" onchange="window.location.href=this.value">
                        <option value="">Choose a course</option>
                        @foreach($courses as $courseOption)
                            <option value="{{ route('admin.assessments.course', $courseOption->id) }}" 
                                {{ isset($course) && $course->id == $courseOption->id ? 'selected' : '' }}>
                                {{ $courseOption->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('admin.assessments.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2 float-end">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Create New Assessment
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(isset($course))
    <!-- Course Assessments -->
    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div>
                <h5 class="mb-1">{{ $course->title }}</h5>
                <p class="text-secondary-light text-sm mb-0">Manage assessments for this course</p>
            </div>
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadAssessmentModal">
                <iconify-icon icon="solar:upload-outline" class="icon"></iconify-icon>
                Upload Assessment
            </button>
        </div>

        <div class="card-body p-24">
            @if($assessments->count() > 0)
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Assessment Title</th>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Due Date</th>
                                <th>Total Marks</th>
                                <th>Submissions</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assessments as $index => $assessment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div>
                                        <span class="fw-semibold">{{ $assessment->title }}</span>
                                        <p class="text-secondary-light text-sm mb-0">{{ Str::limit($assessment->description, 50) }}</p>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $assessment->type == 'exam' ? 'purple' : 
                                        ($assessment->type == 'assignment' ? 'blue' : 'green')
                                    }}-600 text-white px-12 py-6 radius-8">
                                        {{ ucfirst($assessment->type) }}
                                    </span>
                                </td>
                                <td>{{ $assessment->duration }} mins</td>
                                <td>{{ $assessment->due_date ? date('M d, Y', strtotime($assessment->due_date)) : 'N/A' }}</td>
                                <td>{{ $assessment->total_marks }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span>{{ $assessment->submissions_count ?? 0 }}/{{ $assessment->total_students ?? 0 }}</span>
                                        @if(($assessment->submissions_count ?? 0) > 0)
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewSubmissions({{ $assessment->id }})">
                                                View
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $assessment->status == 'active' ? 'success' : 'warning' }}-600 text-white px-12 py-6 radius-8">
                                        {{ ucfirst($assessment->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <a href="{{ route('admin.assessments.show', $assessment->id) }}" 
                                           class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none"
                                           title="View">
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.assessments.edit', $assessment->id) }}"
                                           class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none"
                                           title="Edit">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
                                        <form action="{{ route('admin.assessments.destroy', $assessment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0"
                                                    onclick="return confirm('Are you sure you want to delete this assessment?')"
                                                    title="Delete">
                                                <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <div>
                        <span class="text-muted">Showing {{ $assessments->firstItem() }} to {{ $assessments->lastItem() }} of {{ $assessments->total() }} entries</span>
                    </div>
                    <div>
                        {{ $assessments->links('vendor.pagination.custom') }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <iconify-icon icon="solar:document-text-outline" class="icon-4x text-muted mb-3"></iconify-icon>
                    <h6 class="text-muted mb-2">No assessments found for this course</h6>
                    <p class="text-muted mb-4">Upload your first assessment to get started</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadAssessmentModal">
                        <iconify-icon icon="ic:baseline-plus" class="icon"></iconify-icon>
                        Upload Assessment
                    </button>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Upload Assessment Modal -->
<div class="modal fade" id="uploadAssessmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ isset($course) ? route('admin.assessments.upload', $course->id) : '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload New Assessment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row gy-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Assessment Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g., Final Examination - Module 1" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Brief description of the assessment"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Assessment Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="">Select type</option>
                                <option value="exam">Timed Online Exam</option>
                                <option value="assignment">Assignment</option>
                                <option value="quiz">Quiz</option>
                                <option value="project">Project</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="active" selected>Active</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Duration (minutes)</label>
                            <input type="number" name="duration" class="form-control" placeholder="e.g., 90">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Total Marks</label>
                            <input type="number" name="total_marks" class="form-control" placeholder="e.g., 100">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Weight (%)</label>
                            <input type="number" name="weight" class="form-control" placeholder="e.g., 25">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Due Date</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Due Time</label>
                            <input type="time" name="due_time" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Upload File</label>
                            <div class="upload-file-area">
                                <input type="file" name="assessment_file" class="form-control" accept=".pdf,.doc,.docx,.xlsx,.zip">
                                <p class="text-sm mt-1 mb-0 text-muted">
                                    Supported formats: PDF, DOCX, XLSX, ZIP. Max size: 50MB
                                </p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_timed" id="isTimed" value="1" checked>
                                <label class="form-check-label" for="isTimed">
                                    Timed Assessment (students have limited time to complete)
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="needs_manual_marking" id="needsManualMarking" value="1">
                                <label class="form-check-label" for="needsManualMarking">
                                    Requires Manual Marking (for Diploma/Advanced Diploma)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Assessment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Submissions Modal -->
<div class="modal fade" id="submissionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assessment Submissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Candidate ID</th>
                                <th>Submitted Date</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="submissionsTableBody">
                            <!-- Loaded dynamically via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .upload-file-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s;
    }
    .upload-file-area:hover {
        border-color: #0A1F44;
        background: #f1f5f9;
    }
    .upload-file-area input {
        opacity: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
function viewSubmissions(assessmentId) {
    // Fetch submissions via AJAX
    fetch(`/admin/assessments/${assessmentId}/submissions`)
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.submissions.forEach(submission => {
                html += `
                    <tr>
                        <td>${submission.student_name}</td>
                        <td>${submission.candidate_id}</td>
                        <td>${new Date(submission.submitted_at).toLocaleDateString()}</td>
                        <td>${submission.score || 'Pending'}</td>
                        <td>
                            <span class="badge bg-${submission.status === 'graded' ? 'success' : 'warning'}">
                                ${submission.status}
                            </span>
                        </td>
                        <td>
                            <a href="/admin/assessments/submission/${submission.id}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('submissionsTableBody').innerHTML = html;
            
            var submissionsModal = new bootstrap.Modal(document.getElementById('submissionsModal'));
            submissionsModal.show();
        });
}

// Auto-submit course filter
document.getElementById('courseSelect')?.addEventListener('change', function() {
    if (this.value) {
        window.location.href = this.value;
    }
});
</script>
@endpush