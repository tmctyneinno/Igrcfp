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

    <!-- Statistics Cards -->
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
                <!-- Search Form -->
                <form class="navbar-search" method="GET">
                    <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search student..." value="{{ request('search') }}">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
                
                <!-- Filters Form -->
                <form method="GET" class="d-flex gap-2">
                    <!-- Preserve search term in other filters -->
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    
                    <select name="course_id" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ Str::limit($course->title, 30) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted (Pending)</option>
                        <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>Graded</option>
                    </select>

                    @if(request()->hasAny(['search', 'course_id', 'status']))
                        <a href="{{ route('admin.assessments.submissions.list') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="card-body p-24">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Student</th>
                            <th scope="col">Assessment</th>
                            <th scope="col">Stage</th> 
                            <th scope="col">Submitted At</th>
                            <th scope="col">Score</th>
                            <th scope="col">Grade</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead> 
                    <tbody>
                        @forelse($submissions as $submission)
                        <tr>
                            <td class="fw-medium text-secondary-light">{{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}</td>
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
                                @php
                                    $stage = $submission->current_stage ?? 'Unknown';
                                    $badgeClass = 'bg-neutral-100 text-neutral-600';
                                    
                                    if ($stage === 'Quiz Stage') {
                                        $badgeClass = 'bg-primary-100 text-primary-600';
                                    } elseif ($stage === 'Essay Stage') {
                                        $badgeClass = 'bg-info-100 text-info-600';
                                    } elseif ($stage === 'Quiz Only') {
                                        $badgeClass = 'bg-warning-100 text-warning-600';
                                    } elseif ($stage === 'Completed') {
                                        $badgeClass = 'bg-success-100 text-success-600';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} radius-4 px-8 py-4">{{ $stage }}</span>
                            </td>
                            <td>
                                {{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y H:i') : 'N/A' }}
                            </td>
                            
                            {{-- Score Column --}}
                            <td>
                                @if($submission->percentage !== null)
                                    @php
                                        $percentage = (float) $submission->percentage;
                                        $scoreClass = 'text-danger-600';

                                        if ($percentage > 75) {
                                            $scoreClass = 'text-success-600';
                                        } elseif ($percentage >= 50 && $percentage <= 75) {
                                            $scoreClass = 'text-primary-600';
                                        } elseif ($percentage >= 40 && $percentage <= 49) {
                                            $scoreClass = 'text-warning-600';
                                        }
                                    @endphp
                                    <span class="fw-bold {{ $scoreClass }}">
                                        {{ number_format($percentage, 1) }}%
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            
                            {{-- Grade Column --}}
                            <td>
                                @if(isset($submission->grade_info))
                                    @php
                                        $label = $submission->grade_info['label'];
                                        $class = $submission->grade_info['class'];
                                        $bgClass = 'bg-' . $class . '-100';
                                        $textClass = 'text-' . $class . '-600';
                                    @endphp
                                    <span class="badge {{ $bgClass }} {{ $textClass }} radius-4 px-8 py-4 fw-bold">
                                        {{ $label }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-{{ $submission->status == 'graded' ? 'success' : 'warning' }}-100 text-{{ $submission->status == 'graded' ? 'success' : 'warning' }}-600 radius-4 px-8 py-4">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('admin.assessments.submission.view', $submission->encoded_id ) }}" 
                                       class="bg-info-focus bg-hover-info-200 text-info-600 w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle"
                                       title="View & Grade">
                                        <iconify-icon icon="majesticons:eye-line"></iconify-icon>
                                    </a>

                                    <!-- NEW: Reject Enrollment Button -->
                                    @if(!$submission->enrollment?->certificate_generated)
                                    <button type="button" class="bg-danger-focus bg-hover-danger-200 text-danger-600 w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle border-0"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModalList{{ $submission->id }}"
                                            title="Reject Enrollment">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="8.5" cy="7" r="4"></circle>
                                            <line x1="17" y1="8" x2="23" y2="14"></line>
                                            <line x1="23" y1="8" x2="17" y2="14"></line>
                                        </svg>
                                    </button>

                                    <!-- Reject Modal for List View -->
                                    <div class="modal fade" id="rejectModalList{{ $submission->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.enrollments.reject', $submission->enrollment) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">Reject Enrollment</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Remove <strong>{{ $submission->user->name }}</strong> from <strong>{{ $submission->assessment->course->title ?? 'this course' }}</strong>?</p>
                                                        <div class="mb-3 mt-3">
                                                            <label class="form-label">Reason</label>
                                                            <textarea name="reason" class="form-control" rows="2" placeholder="Not assigned..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Confirm</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr> 
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
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