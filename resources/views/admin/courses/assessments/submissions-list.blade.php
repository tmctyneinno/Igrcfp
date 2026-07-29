@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0 d-flex align-items-center gap-2">
            Recent Assessment Submissions
            <span class="badge bg-primary-600 text-white radius-4 px-8 py-4">{{ $submissions->total() }}</span>
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
            <li>-</li>
            <li class="fw-medium">Recent Submissions</li>
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

    <div class="row gy-4 mb-24">
        <div class="col-xxl-3 col-sm-6">
            <div class="card shadow-none border bg-gradient-start-1 h-100">
                <div class="card-body p-20 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Total Submissions</p>
                        <h6 class="mb-0">{{ $statistics['total'] ?? 0 }}</h6>
                    </div>
                    <div class="w-44-px h-44-px bg-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:document-text-outline" class="text-white text-2xl"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card shadow-none border bg-gradient-start-2 h-100">
                <div class="card-body p-20 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Pending Grading</p>
                        <h6 class="mb-0">{{ $statistics['pending'] ?? 0 }}</h6>
                    </div>
                    <div class="w-44-px h-44-px bg-warning-600 rounded-circle d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:hourglass-outline" class="text-white text-2xl"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card shadow-none border bg-gradient-start-3 h-100">
                <div class="card-body p-20 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Graded</p>
                        <h6 class="mb-0">{{ $statistics['graded'] ?? 0 }}</h6>
                    </div>
                    <div class="w-44-px h-44-px bg-success-600 rounded-circle d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:check-circle-outline" class="text-white text-2xl"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card shadow-none border bg-gradient-start-4 h-100">
                <div class="card-body p-20 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Average Score</p>
                        <h6 class="mb-0">{{ isset($statistics['avg_score']) && $statistics['avg_score'] !== null ? number_format($statistics['avg_score'], 1) . '%' : '—' }}</h6>
                    </div>
                    <div class="w-44-px h-44-px bg-info-600 rounded-circle d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:chart-2-outline" class="text-white text-2xl"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <form class="navbar-search" method="GET">
                    <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search student..." value="{{ request('search') }}">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
                
                <form method="GET" class="d-inline">
                    <select name="status" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted (Pending)</option>
                        <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>Graded</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="card-body p-24">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Student</th>
                            <th scope="col">Assessment</th>
                            <th scope="col">Course</th>
                            <th scope="col">Submitted At</th>
                            <th scope="col">Score</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-32-px h-32-px rounded-circle bg-primary-100 d-flex align-items-center justify-content-center">
                                        <span class="text-primary-600 fw-semibold">{{ substr($submission->user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="fw-medium mb-0">{{ $submission->user->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-secondary-light mb-0">{{ $submission->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $submission->assessment->title ?? 'N/A' }}</span>
                                <p class="text-xs text-secondary-light mb-0">{{ ucfirst($submission->assessment->assessment_level ?? '') }}</p>
                            </td>
                            <td>
                                <span class="text-sm">{{ $submission->assessment->course->title ?? 'N/A' }}</span>
                            </td>
                            <td>
                                {{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y H:i') : 'N/A' }}
                            </td>
                            <td>
                                @if($submission->percentage !== null)
                                    <span class="fw-bold {{ $submission->passed ? 'text-success-600' : 'text-danger-600' }}">
                                        {{ number_format($submission->percentage, 1) }}%
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $submission->status == 'graded' ? 'success' : 'warning' }}-100 text-{{ $submission->status == 'graded' ? 'success' : 'warning' }}-600 radius-4 px-8 py-4">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('admin.assessments.submission.view', $submission->id) }}" 
                                       class="bg-info-focus bg-hover-info-200 text-info-600 w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle"
                                       title="View & Grade">
                                        <iconify-icon icon="majesticons:eye-line"></iconify-icon>
                                    </a>

                                    <!-- <form action="{{ route('admin.assessments.submission.delete', $submission->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this submission? This cannot be undone.');"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-danger-focus bg-hover-danger-200 text-danger-600 w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle border-0"
                                                title="Delete Submission">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
                                    </form> -->
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">No submissions found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                <span>Showing {{ $submissions->firstItem() ?? 0 }} to {{ $submissions->lastItem() ?? 0 }} of {{ $submissions->total() }} entries</span>
                {{ $submissions->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>
@endsection