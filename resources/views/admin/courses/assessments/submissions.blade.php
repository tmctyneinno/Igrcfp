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
                <a href="{{ route('admin.assessments.all') }}" class="hover-text-primary">Assessments</a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.assessments.show', $assessment->id) }}" class="hover-text-primary">
                    {{ Str::limit($assessment->title, 30) }}
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Submissions</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
    @endif

    <!-- Stats Cards -->
    <div class="row gy-4 mb-24">
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Total</span>
                            <h4 class="mb-0">{{ $submissions->total() }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:users-group-rounded-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Graded</span>
                            <h4 class="mb-0">{{ $submissions->where('status', 'graded')->count() }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-success-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:check-read-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Pending</span>
                            <h4 class="mb-0">{{ $submissions->where('status', 'submitted')->count() }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-warning-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:clock-circle-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Average</span>
                            <h4 class="mb-0">{{ number_format($submissions->where('status', 'graded')->avg('score'), 1) }}%</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-info-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:chart-2-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions Table -->
    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div>
                <h5 class="mb-1">Student Submissions</h5>
                <p class="text-secondary-light text-sm mb-0">Manage and grade student submissions</p>
            </div>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm w-auto" id="statusFilter" onchange="filterByStatus(this.value)">
                    <option value="">All Status</option>
                    <option value="submitted">Pending</option>
                    <option value="graded">Graded</option>
                    <option value="late">Late</option>
                </select>
                <button class="btn btn-sm btn-outline-primary" onclick="exportSubmissions()">
                    <iconify-icon icon="solar:export-outline"></iconify-icon>
                    Export
                </button>
            </div>
        </div>

        <div class="card-body p-24">
            @if($submissions->count() > 0)
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Candidate ID</th>
                                <th>Submitted</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Time Spent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="w-35-px h-35-px bg-primary-100 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <span class="text-primary-600 fw-semibold">{{ strtoupper(substr($submission->user->name ?? 'U', 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <span class="fw-medium">{{ $submission->user->name ?? 'Unknown' }}</span>
                                            <p class="text-sm text-secondary-light mb-0">{{ $submission->user->email ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-purple-100 text-purple-600 px-12 py-6 radius-8">
                                        {{ $submission->user->candidate_id ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <span class="d-block">{{ $submission->submitted_at->format('M d, Y') }}</span>
                                        <small class="text-secondary-light">{{ $submission->submitted_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($submission->score !== null)
                                        <span class="fw-bold {{ $submission->passed ? 'text-success-600' : 'text-danger-600' }}">
                                            {{ number_format($submission->score, 1) }}%
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'submitted' => 'bg-warning-600',
                                            'graded' => 'bg-success-600',
                                            'late' => 'bg-danger-600'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$submission->status] ?? 'bg-secondary-600' }} text-white px-12 py-6 radius-8">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($submission->time_spent)
                                        <span>{{ floor($submission->time_spent / 60) }} mins</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('admin.assessments.submission.view', $submission->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <iconify-icon icon="majesticons:eye-line"></iconify-icon>
                                            Review
                                        </a>
                                        @if($submission->status == 'submitted')
                                            <a href="{{ route('admin.assessments.submission.view', $submission->id) }}#grade" 
                                               class="btn btn-sm btn-outline-success">
                                                Grade
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <div class="text-muted">
                        Showing {{ $submissions->firstItem() }} to {{ $submissions->lastItem() }} of {{ $submissions->total() }} entries
                    </div>
                    <div>
                        {{ $submissions->withQueryString()->links('vendor.pagination.custom') }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <iconify-icon icon="solar:document-text-outline" class="icon-4x text-muted mb-3"></iconify-icon>
                    <h6 class="text-muted mb-2">No submissions yet</h6>
                    <p class="text-muted">Students haven't submitted this assessment yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterByStatus(status) {
    const url = new URL(window.location.href);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}

</script>
@endpush