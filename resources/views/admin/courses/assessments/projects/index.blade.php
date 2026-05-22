@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Project Assessments</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Projects</li>
        </ul>
    </div>
 
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
    @endif

    <!-- Statistics -->
    <div class="row gy-4 mb-24">
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Total Projects</span>
                            <h4 class="mb-0">{{ $statistics['total'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-purple-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:document-text-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Active</span>
                            <h4 class="mb-0">{{ $statistics['active'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-success-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:check-circle-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Draft</span>
                            <h4 class="mb-0">{{ $statistics['draft'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-warning-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:pen-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between">
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

    <!-- Filters -->
    <div class="card mb-24">
        <div class="card-body p-24">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Course</label>
                    <select class="form-select" onchange="filterByCourse(this.value)">
                        <option value="">All Courses</option>
                        @foreach($courses as $courseOption)
                            <option value="{{ $courseOption->id }}" {{ request('course_id') == $courseOption->id ? 'selected' : '' }}>
                                {{ $courseOption->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select" onchange="filterByStatus(this.value)">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search projects..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 ms-auto text-end">
                    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                        New Project
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="card">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h6 class="card-title mb-0">Project Assessments</h6>
        </div>
        <div class="card-body p-24">
            @if($assessments->count() > 0)
                <div class="table-responsive">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Course</th>
                                <th>Module</th>
                                <th>Due Date</th>
                                <th>Submissions</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assessments as $index => $assessment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div>
                                        <span class="fw-semibold">{{ $assessment->title }}</span>
                                        <p class="text-secondary-light text-sm mb-0">{{ Str::limit($assessment->description, 40) }}</p>
                                    </div>
                                </td>
                                <td>{{ $assessment->course->title ?? 'N/A' }}</td>
                                <td>
                                    @if($assessment->module)
                                        Module {{ $assessment->module->module_number }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($assessment->due_date)
                                        <span class="{{ $assessment->is_overdue ? 'text-danger-600' : '' }}">
                                            {{ $assessment->due_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.projects.submissions', $assessment) }}" class="text-primary-600">
                                        {{ $assessment->submissions_count }} submissions
                                    </a>
                                </td>
                                <td>
                                    @php
                                        $statusColors = ['active' => 'success', 'draft' => 'warning', 'archived' => 'secondary'];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$assessment->status] ?? 'secondary' }}-600 text-white px-12 py-6 radius-8">
                                        {{ ucfirst($assessment->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-2 justify-content-center">
                                        <a href="{{ route('admin.projects.show', $assessment) }}" class="bg-info-focus bg-hover-info-200 text-info-600 w-35-px h-35-px d-inline-flex justify-content-center align-items-center rounded-circle" title="View">
                                            <iconify-icon icon="majesticons:eye-line"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.projects.edit', $assessment) }}" class="bg-success-focus bg-hover-success-200 text-success-600 w-35-px h-35-px d-inline-flex justify-content-center align-items-center rounded-circle" title="Edit">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </a>
                                        <form action="{{ route('admin.projects.destroy', $assessment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-danger-focus bg-hover-danger-200 text-danger-600 w-35-px h-35-px d-inline-flex justify-content-center align-items-center rounded-circle border-0" onclick="return confirm('Delete this project?')" title="Delete">
                                                <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $assessments->links('vendor.pagination.custom') }}
            @else
                <div class="text-center py-5">
                    <p class="text-muted">No project assessments found.</p>
                    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary mt-3">Create First Project</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterByCourse(courseId) {
    const url = new URL(window.location.href);
    if (courseId) url.searchParams.set('course_id', courseId);
    else url.searchParams.delete('course_id');
    window.location.href = url.toString();
}

function filterByStatus(status) {
    const url = new URL(window.location.href);
    if (status) url.searchParams.set('status', status);
    else url.searchParams.delete('status');
    window.location.href = url.toString();
}

let searchTimeout;
document.getElementById('searchInput')?.addEventListener('keyup', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const url = new URL(window.location.href);
        if (this.value) url.searchParams.set('search', this.value);
        else url.searchParams.delete('search');
        window.location.href = url.toString();
    }, 500);
});
</script>
@endpush