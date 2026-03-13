@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">
            @if(isset($course))
                Assessments: {{ $course->title }}
            @else
                All Assessments
            @endif
        </h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Assessments</li>
            @if(isset($course))
                <li>-</li>
                <li class="fw-medium">{{ Str::limit($course->title, 30) }}</li>
            @endif
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
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Total</span>
                            <h4 class="mb-0">{{ $statistics['total'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-purple-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:document-text-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Quizzes</span>
                            <h4 class="mb-0">{{ $statistics['quizzes'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-green-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:quiz-game" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Module</span>
                            <h4 class="mb-0">{{ $statistics['module_assessments'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-blue-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:clipboard-list" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Final Exams</span>
                            <h4 class="mb-0">{{ $statistics['final_exams'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-red-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:document" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Diploma</span>
                            <h4 class="mb-0">{{ $statistics['diploma'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-purple-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:medal-ribbon" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Submissions</span>
                            <h4 class="mb-0">{{ $statistics['submissions'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-info-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:upload-square-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="card h-100 p-0 radius-12 mb-24">
        <div class="card-body p-24">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Course</label>
                    <select class="form-select" id="courseFilter" onchange="filterByCourse(this.value)">
                        <option value="">All Courses</option>
                        @foreach($courses as $courseOption)
                            <option value="{{ $courseOption->id }}" 
                                {{ (isset($course) && $course->id == $courseOption->id) ? 'selected' : '' }}>
                                {{ $courseOption->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Type</label>
                    <select class="form-select" id="levelFilter" onchange="filterByLevel(this.value)">
                        <option value="">All Types</option>
                        <option value="quiz">Quizzes</option>
                        <option value="module_assessment">Module Assessments</option>
                        <option value="final_exam">Final Exams</option>
                        <option value="diploma">Diploma</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="statusFilter" onchange="filterByStatus(this.value)">
                        <option value="">All</option>
                        <option value="active">Active</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search assessments..." 
                           value="{{ request('search') }}" onkeyup="debounceSearch(this.value, 500)">
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('admin.assessments.create') }}" class="btn btn-primary text-sm px-18 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl"></iconify-icon>
                        New Assessment
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Assessments List -->
    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div>
                <h5 class="mb-1">
                    @if(isset($course))
                        {{ $course->title }} - Assessments
                    @else
                        All Assessments
                    @endif
                </h5>
                <p class="text-secondary-light text-sm mb-0">
                    {{ $assessments->total() }} assessments found
                </p>
            </div>
            @if(isset($course))
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadAssessmentModal">
                <iconify-icon icon="solar:upload-outline" class="icon"></iconify-icon>
                Quick Upload
            </button>
            @endif
        </div>

        <div class="card-body p-24">
            @if($assessments->count() > 0)
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th width="50">
                                    <div class="form-check style-check d-flex align-items-center">
                                        <input class="form-check-input radius-4 border border-neutral-400" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>#</th>
                                <th>Title</th>
                                <th>Course</th>
                                <th>Module</th>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Due Date</th>
                                <th>Questions</th>
                                <th>Submissions</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assessments as $index => $assessment)
                            <tr>
                                <td>
                                    <div class="form-check style-check d-flex align-items-center">
                                        <input class="form-check-input radius-4 border border-neutral-400 assessment-checkbox" 
                                               type="checkbox" name="assessment_ids[]" value="{{ $assessment->id }}">
                                    </div>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div>
                                        <span class="fw-semibold">{{ $assessment->title }}</span>
                                        <p class="text-secondary-light text-sm mb-0">{{ Str::limit($assessment->description, 40) }}</p>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info-600 text-white px-12 py-6 radius-8">
                                        {{ $assessment->course?->short_title ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($assessment->module)
                                        <span class="text-sm">Module {{ $assessment->module->module_number }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $typeColors = [
                                            'quiz' => 'bg-green-600',
                                            'module_assessment' => 'bg-blue-600',
                                            'final_exam' => 'bg-red-600',
                                            'diploma' => 'bg-purple-600'
                                        ];
                                        $typeLabels = [
                                            'quiz' => 'Quiz',
                                            'module_assessment' => 'Module',
                                            'final_exam' => 'Final Exam',
                                            'diploma' => 'Diploma'
                                        ];
                                        $color = $typeColors[$assessment->assessment_level] ?? 'bg-gray-600';
                                        $label = $typeLabels[$assessment->assessment_level] ?? ucfirst($assessment->assessment_level);
                                    @endphp
                                    <span class="badge {{ $color }} text-white px-12 py-6 radius-8">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td>
                                    @if($assessment->is_timed)
                                        <span class="d-flex align-items-center gap-1">
                                            <iconify-icon icon="solar:clock-circle-outline" class="text-primary-600"></iconify-icon>
                                            {{ $assessment->duration }} mins
                                        </span>
                                    @else
                                        <span class="text-muted">Untimed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($assessment->due_date)
                                        <div>
                                            <span class="text-sm">{{ $assessment->due_date->format('M d, Y') }}</span>
                                            @if($assessment->due_date < now())
                                                <span class="badge bg-danger-600 text-white px-8 py-4 radius-4 ms-1">Overdue</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $assessment->question_count ?: $assessment->questions_count ?? 0 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span>{{ $assessment->submissions_count }}</span>
                                        @if($assessment->pending_grading_count > 0)
                                            <span class="badge bg-warning-600 text-white px-8 py-4 radius-4">
                                                {{ $assessment->pending_grading_count }} pending
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-success-600',
                                            'draft' => 'bg-warning-600',
                                            'archived' => 'bg-secondary-600'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$assessment->status] ?? 'bg-secondary-600' }} text-white px-12 py-6 radius-8">
                                        {{ ucfirst($assessment->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <a href="{{ route('admin.assessments.show', $assessment->id) }}" 
                                           class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                           title="View">
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.assessments.edit', $assessment->id) }}"
                                           class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                           title="Edit">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.assessments.submissions', $assessment->id) }}"
                                           class="bg-primary-focus text-primary-600 bg-hover-primary-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                           title="Submissions">
                                            <iconify-icon icon="solar:users-group-rounded-outline" class="menu-icon"></iconify-icon>
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

                <!-- Bulk Actions -->
                <div class="d-flex align-items-center gap-3 mt-3">
                    <select class="form-select form-select-sm w-auto" id="bulkAction">
                        <option value="">Bulk Actions</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="bulkAction()">Apply</button>
                </div>

                <!-- Pagination -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <div class="text-muted">
                        Showing {{ $assessments->firstItem() }} to {{ $assessments->lastItem() }} of {{ $assessments->total() }} entries
                    </div>
                    <div>
                        {{ $assessments->withQueryString()->links('vendor.pagination.custom') }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <iconify-icon icon="solar:document-text-outline" class="icon-4x text-muted mb-3"></iconify-icon>
                    <h6 class="text-muted mb-2">No assessments found</h6>
                    <p class="text-muted mb-4">Create your first assessment to get started</p>
                    <a href="{{ route('admin.assessments.create') }}" class="btn btn-primary">
                        <iconify-icon icon="ic:baseline-plus" class="icon"></iconify-icon>
                        Create Assessment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Upload Assessment Modal -->
@if(isset($course))
<div class="modal fade" id="uploadAssessmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.assessments.upload', $course->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Quick Upload Assessment for {{ $course->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row gy-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Assessment Type <span class="text-danger">*</span></label>
                            <select name="assessment_level" class="form-select" required>
                                <option value="quiz">Quiz</option>
                                <option value="module_assessment">Module Assessment</option>
                                <option value="final_exam">Final Exam</option>
                                <option value="diploma">Diploma Project</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="active" selected>Active</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Duration (mins)</label>
                            <input type="number" name="duration" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Total Marks</label>
                            <input type="number" name="total_marks" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Passing Score (%)</label>
                            <input type="number" name="passing_score" class="form-control" min="1" max="100">
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
                            <input type="file" name="assessment_file" class="form-control" accept=".pdf,.doc,.docx,.xlsx,.zip">
                            <p class="text-sm mt-1 mb-0 text-muted">Max size: 50MB</p>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Project Brief (for Diploma)</label>
                            <textarea name="project_brief" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_timed" id="isTimed" value="1" checked>
                                <label class="form-check-label" for="isTimed">Timed Assessment</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="requires_identity_verification" id="requiresVerification" value="1">
                                <label class="form-check-label" for="requiresVerification">Requires Identity Verification</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="needs_manual_marking" id="needsManualMarking" value="1">
                                <label class="form-check-label" for="needsManualMarking">Requires Manual Marking</label>
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
@endif
@endsection

@push('styles')
<style>
    .icon-4x {
        font-size: 4rem;
    }
    .w-45-px {
        width: 45px;
    }
    .h-45-px {
        height: 45px;
    }
</style>
@endpush

@push('scripts')
<script>
// Filter functions
function filterByCourse(courseId) {
    if (courseId) {
        window.location.href = '{{ route("admin.assessments.course", "") }}/' + courseId;
    } else {
        window.location.href = '{{ route("admin.assessments.all") }}';
    }
}

function filterByLevel(level) {
    const url = new URL(window.location.href);
    if (level) {
        url.searchParams.set('level', level);
    } else {
        url.searchParams.delete('level');
    }
    window.location.href = url.toString();
}

function filterByStatus(status) {
    const url = new URL(window.location.href);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}

let searchTimeout;
function debounceSearch(value, delay) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const url = new URL(window.location.href);
        if (value) {
            url.searchParams.set('search', value);
        } else {
            url.searchParams.delete('search');
        }
        window.location.href = url.toString();
    }, delay);
}

// Select all checkboxes
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.assessment-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
});

// Bulk action
function bulkAction() {
    const action = document.getElementById('bulkAction').value;
    const selected = document.querySelectorAll('.assessment-checkbox:checked');
    
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    if (selected.length === 0) {
        alert('Please select at least one assessment');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm('Are you sure you want to delete ' + selected.length + ' assessments?')) {
            return;
        }
        
        const ids = Array.from(selected).map(cb => cb.value);
        
        fetch('{{ route("admin.assessments.bulk-delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ assessment_ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}
</script>
@endpush