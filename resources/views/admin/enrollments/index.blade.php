{{-- resources/views/admin/enrollments/index.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Enrollment Management</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Enrollments</li>
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

    <!-- Summary Cards -->
    <div class="row gy-4 mb-24">
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div class="">
                            <span class="mb-0 fw-medium text-secondary-light text-md">Total Enrollments</span>
                            <h6 class="fw-semibold mb-0 mt-2 text-primary-light">{{ $summary['total'] ?? 0 }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-primary-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mingcute:user-star-line" class="icon text-2xl text-primary-600"></iconify-icon>
                        </div>
                    </div>
                    <div class="mt-24">
                        <span class="text-secondary-light text-sm fw-normal">Total revenue: ${{ number_format($summary['total_revenue'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div class="">
                            <span class="mb-0 fw-medium text-secondary-light text-md">Pending</span>
                            <h6 class="fw-semibold mb-0 mt-2 text-warning-main">{{ $summary['pending'] ?? 0 }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-warning-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:hourglass-empty" class="icon text-2xl text-warning-600"></iconify-icon>
                        </div>
                    </div>
                    <div class="mt-24">
                        <span class="text-secondary-light text-sm fw-normal">Awaiting confirmation</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div class="">
                            <span class="mb-0 fw-medium text-secondary-light text-md">Completed</span>
                            <h6 class="fw-semibold mb-0 mt-2 text-success-main">{{ $summary['completed'] ?? 0 }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-success-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:check-circle" class="icon text-2xl text-success-600"></iconify-icon>
                        </div>
                    </div>
                    <div class="mt-24">
                        <span class="text-secondary-light text-sm fw-normal">Successfully enrolled</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card h-100 p-0 radius-12 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div class="">
                            <span class="mb-0 fw-medium text-secondary-light text-md">Cancelled</span>
                            <h6 class="fw-semibold mb-0 mt-2 text-danger-main">{{ $summary['cancelled'] ?? 0 }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-danger-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:cancel" class="icon text-2xl text-danger-600"></iconify-icon>
                        </div>
                    </div>
                    <div class="mt-24">
                        <span class="text-secondary-light text-sm fw-normal">Cancelled/Refunded</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search by name, email, course..." value="{{ request('search') }}">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
                
                <form method="GET" class="d-inline d-flex">
                    <select name="status" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    
                    <select name="course_id" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px ms-2" onchange="this.form.submit()">
                        <option value="">All Courses</option>
                        @foreach($courses ?? [] as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                        @endforeach
                    </select>
                    
                    @if(request('search') || request('status') || request('course_id') || request('per_page') != 10)
                        <a href="{{ route('admin.enrollments.index') }}" class="btn btn-sm btn-outline-secondary ms-2">Clear</a>
                    @endif
                </form>
            </div>
            <div>
                <a href="{{ route('admin.enrollments.export') }}" class="btn btn-success text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2 me-2">
                    <iconify-icon icon="mdi:export" class="icon text-xl line-height-1"></iconify-icon>
                    Export
                </a>
            </div>
        </div>

        <form id="bulk-action-form" action="{{ route('admin.enrollments.bulk-action') }}" method="POST">
            @csrf
            <div class="card-body p-24">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <select name="action" class="form-select form-select-sm w-auto" required>
                        <option value="">Bulk Actions</option>
                        <option value="completed">Mark as Completed</option>
                        <option value="pending">Mark as Pending</option>
                        <option value="cancelled">Mark as Cancelled</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Apply</button>
                </div>

                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col" width="50">
                                    <div class="d-flex align-items-center gap-10">
                                        <div class="form-check style-check d-flex align-items-center">
                                            <input class="form-check-input radius-4 border input-form-dark" type="checkbox" id="selectAll">
                                        </div>
                                        S.L
                                    </div>
                                </th>
                                <th scope="col">Student</th>
                                <th scope="col">Course</th>
                                <th scope="col">Enrollment Date</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col">Enrollment Status</th>
                                <th scope="col">Progress</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $enrollment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-10">
                                        <div class="form-check style-check d-flex align-items-center">
                                            <input class="form-check-input radius-4 border border-neutral-400 enrollment-checkbox" type="checkbox" name="enrollment_ids[]" value="{{ $enrollment->id }}">
                                        </div>
                                        {{ $loop->iteration + ($enrollments->currentPage() - 1) * $enrollments->perPage() }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-12">
                                            <img src="{{ asset('storage/' . ($enrollment->user->avatar ?? 'default-avatar.jpeg')) }}" 
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
                                        <small class="text-muted">ID: #{{ $enrollment->course->id }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $enrollment->created_at->format('M d, Y') }}</span>
                                        <small class="text-muted">{{ $enrollment->created_at->format('h:i A') }}</small>
                                    </div>
                                </td> 
                                <td>
                                    <div class="d-flex flex-column">
                                        {{ $enrollment }}
                                        <span class="fw-medium">${{ number_format($enrollment->amount ?? 0, 2) }}</span>
                                        @if($enrollment->transaction)
                                            <small class="text-muted">Ref: {{ substr($enrollment->transaction->reference, -8) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($enrollment->transaction)
                                        @if($enrollment->transaction->status == 'completed')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($enrollment->transaction->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($enrollment->transaction->status == 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @elseif($enrollment->transaction->status == 'refunded')
                                            <span class="badge bg-info">Refunded</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">No Payment</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $enrollment->status === 'completed' ? 'success' : ($enrollment->status === 'cancelled' ? 'danger' : 'warning') }} px-12 py-6">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress w-100" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $enrollment->progress ?? 0 }}%" 
                                                 aria-valuenow="{{ $enrollment->progress ?? 0 }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="text-xs">{{ $enrollment->progress ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td class="text-center"> 
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <a href="{{ route('admin.enrollments.show', $enrollment) }}" 
                                           class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                           title="View Details">
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>
                                        
                                        @if($enrollment->transaction)
                                            <a href="{{ route('admin.transactions.show', $enrollment->transaction) }}" 
                                               class="bg-primary-focus bg-hover-primary-200 text-primary-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                               title="View Transaction">
                                                <iconify-icon icon="hugeicons:transaction" class="menu-icon"></iconify-icon>
                                            </a>
                                        @endif

                                        <!-- Status Update Dropdown -->
                                        <div class="dropdown">
                                            <button class="bg-warning-focus bg-hover-warning-200 text-warning-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" 
                                                    type="button" 
                                                    data-bs-toggle="dropdown" 
                                                    aria-expanded="false"
                                                    title="Update Status">
                                                <iconify-icon icon="mdi:chevron-down" class="menu-icon"></iconify-icon>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ route('admin.enrollments.update-status', $enrollment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="dropdown-item">Mark Pending</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.enrollments.update-status', $enrollment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="dropdown-item">Mark Completed</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.enrollments.update-status', $enrollment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="dropdown-item">Mark Cancelled</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.enrollments.destroy', $enrollment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" 
                                                    onclick="return confirm('Are you sure you want to delete this enrollment? This action cannot be undone.')" 
                                                    title="Delete">
                                                <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <iconify-icon icon="mingcute:user-star-line" class="icon-3x mb-2"></iconify-icon>
                                        <p>No enrollments found.</p>
                                        @if(request('search') || request('status') || request('course_id'))
                                            <a href="{{ route('admin.enrollments.index') }}" class="btn btn-sm btn-primary">Clear Filters</a>
                                        @else
                                            <p class="text-sm">Enrollments will appear here when students register for courses.</p>
                                        @endif
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
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkboxes
    const selectAll = document.getElementById('selectAll');
    const enrollmentCheckboxes = document.querySelectorAll('.enrollment-checkbox');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            enrollmentCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        });
    }

    // Bulk action form validation
    const bulkForm = document.getElementById('bulk-action-form');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.enrollment-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one enrollment.');
                return false;
            }
            
            const action = document.querySelector('select[name="action"]').value;
            if (!action) {
                e.preventDefault();
                alert('Please select an action.');
                return false;
            }
        });
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush