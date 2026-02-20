@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Completed Enrollments</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.enrollments.index') }}" class="hover-text-primary">Enrollments</a>
            </li>
            <li>-</li>
            <li class="fw-medium">Completed</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
                <form method="GET" class="d-inline">
                    <select name="per_page" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </form>
                
                <form class="navbar-search" method="GET">
                    <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search completed enrollments..." value="{{ request('search') }}">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
                
                @if(request('search') || request('per_page') != 10)
                    <a href="{{ route('admin.enrollments.completed') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                @endif
            </div>
            <div>
                <a href="{{ route('admin.enrollments.index') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                    <iconify-icon icon="mdi:arrow-left" class="icon text-xl line-height-1"></iconify-icon>
                    Back to All Enrollments
                </a>
            </div>
        </div>

        <div class="card-body p-24">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col" width="50">S.L</th>
                            <th scope="col">Student</th>
                            <th scope="col">Course</th>
                            <th scope="col">Enrollment Date</th>
                            <th scope="col">Completed Date</th>
                            <th scope="col">Certificate</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollments as $enrollment)
                        <tr>
                            <td>{{ $loop->iteration + ($enrollments->currentPage() - 1) * $enrollments->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-12">
                                        <img src="{{ asset('storage/' . ($enrollment->user->avatar ?? 'default-avatar.jpg')) }}" 
                                             alt="{{ $enrollment->user->name }}" 
                                             class="w-40-px h-40-px rounded-circle object-fit-cover">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0 fw-medium">{{ $enrollment->user->name }}</h6>
                                        <span class="text-sm text-secondary-light">{{ $enrollment->user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ Str::limit($enrollment->course->title, 30) }}</span>
                                </div>
                            </td>
                            <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                            <td>{{ $enrollment->completed_at ? $enrollment->completed_at->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if($enrollment->certificate_issued)
                                    <span class="badge bg-success">Issued</span>
                                    @if($enrollment->certificate_url)
                                        <a href="{{ $enrollment->certificate_url }}" target="_blank" class="ms-1">
                                            <iconify-icon icon="mdi:download" class="text-info"></iconify-icon>
                                        </a>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Not Issued</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-10">
                                    <a href="{{ route('admin.enrollments.show', $enrollment) }}" 
                                       class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                       title="View Details">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <iconify-icon icon="mdi:check-circle-outline" class="icon-3x mb-2"></iconify-icon>
                                    <p>No completed enrollments found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                <span>Showing {{ $enrollments->firstItem() ?? 0 }} to {{ $enrollments->lastItem() ?? 0 }} of {{ $enrollments->total() }} entries</span>
                {{ $enrollments->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>
@endsection