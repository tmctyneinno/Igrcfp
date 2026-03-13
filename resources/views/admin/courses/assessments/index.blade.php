@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">
            @if(isset($course))
                Assessments for: {{ $course->title }}
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
                <li class="fw-medium">{{ $course->title }}</li>
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
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Filter by Course</label>
                    <select class="form-select" id="courseSelect" onchange="window.location.href=this.value">
                        <option value="{{ route('admin.assessments.all') }}">All Courses</option>
                        @foreach($allCourses as $courseOption)
                            <option value="{{ route('admin.assessments.index', $courseOption->id) }}" 
                                {{ isset($course) && $course->id == $courseOption->id ? 'selected' : '' }}>
                                {{ $courseOption->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('admin.assessments.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2 float-end">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Create New Assessment
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
                    {{ isset($course) ? 'Manage assessments for this course' : 'Manage all course assessments' }}
                </p>
            </div>
            @if(isset($course))
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadAssessmentModal">
                <iconify-icon icon="solar:upload-outline" class="icon"></iconify-icon>
                Upload Assessment
            </button>
            @endif
        </div>

        <div class="card-body p-24">
            @if($assessments->count() > 0)
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Course</th>
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
                                    <span class="badge bg-info-600 text-white px-12 py-6 radius-8">
                                        {{ $assessment->course->title ?? 'N/A' }}
                                    </span>
                                </td>
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
                @if(method_exists($assessments, 'links'))
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <div>
                        <span class="text-muted">Showing {{ $assessments->firstItem() }} to {{ $assessments->lastItem() }} of {{ $assessments->total() }} entries</span>
                    </div>
                    <div>
                        {{ $assessments->links('vendor.pagination.custom') }}
                    </div>
                </div>
                @endif
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

<!-- Upload Assessment Modal (only show if course is selected) -->
@if(isset($course))
<div class="modal fade" id="uploadAssessmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.assessments.upload', $course->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Assessment for {{ $course->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- ... modal content ... -->
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