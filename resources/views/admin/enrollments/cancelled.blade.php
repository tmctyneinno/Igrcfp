@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Cancelled Enrollments</h6>
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
            <li class="fw-medium">Cancelled</li>
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
        <div class="col-xl-4 col-sm-6">
            <div class="card h-100 p-0 radius-12 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div class="">
                            <span class="mb-0 fw-medium text-secondary-light text-md">Total Cancelled</span>
                            <h6 class="fw-semibold mb-0 mt-2 text-danger-main">{{ $enrollments->total() ?? 0 }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-danger-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:cancel" class="icon text-2xl text-danger-600"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6">
            <div class="card h-100 p-0 radius-12 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div class="">
                            <span class="mb-0 fw-medium text-secondary-light text-md">Refunded Amount</span>
                            <h6 class="fw-semibold mb-0 mt-2 text-warning-main">
                                ${{ number_format($totalRefunded ?? 0, 2) }}
                            </h6>
                        </div>
                        <div class="w-50-px h-50-px bg-warning-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:cash-refund" class="icon text-2xl text-warning-600"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6">
            <div class="card h-100 p-0 radius-12 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div class="">
                            <span class="mb-0 fw-medium text-secondary-light text-md">Cancellation Rate</span>
                            <h6 class="fw-semibold mb-0 mt-2 text-info-main">{{ $cancellationRate ?? 0 }}%</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-info-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:percent" class="icon text-2xl text-info-600"></iconify-icon>
                        </div>
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
                    <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search cancelled enrollments..." value="{{ request('search') }}">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>

                <form method="GET" class="d-inline">
                    <select name="refund_status" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                        <option value="">All Refund Status</option>
                        <option value="refunded" {{ request('refund_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        <option value="pending_refund" {{ request('refund_status') == 'pending_refund' ? 'selected' : '' }}>Pending Refund</option>
                        <option value="no_refund" {{ request('refund_status') == 'no_refund' ? 'selected' : '' }}>No Refund</option>
                    </select>
                </form>
                
                @if(request('search') || request('refund_status') || request('per_page') != 10)
                    <a href="{{ route('admin.enrollments.cancelled') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
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
                            <th scope="col">Cancelled Date</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Refund Status</th>
                            <th scope="col">Cancellation Reason</th>
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
                                    <span class="text-danger">{{ $enrollment->updated_at->format('M d, Y') }}</span>
                                    <small class="text-muted">{{ $enrollment->updated_at->format('h:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium">${{ number_format($enrollment->amount ?? 0, 2) }}</span>
                            </td>
                            <td>
                                @if($enrollment->transaction && $enrollment->transaction->status == 'refunded')
                                    <span class="badge bg-success">Refunded</span>
                                    <small class="d-block text-muted">{{ $enrollment->transaction->refunded_at?->format('M d, Y') }}</small>
                                @elseif($enrollment->transaction && $enrollment->transaction->status == 'pending_refund')
                                    <span class="badge bg-warning">Pending Refund</span>
                                @else
                                    <span class="badge bg-secondary">No Refund</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm">{{ $enrollment->notes ?? 'No reason provided' }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-10">
                                    <a href="{{ route('admin.enrollments.show', $enrollment) }}" 
                                       class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                       title="View Details">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </a>
                                    
                                    @if($enrollment->transaction && $enrollment->transaction->status != 'refunded')
                                        <button type="button" 
                                                class="bg-success-focus bg-hover-success-200 text-success-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#refundModal{{ $enrollment->id }}"
                                                title="Process Refund">
                                            <iconify-icon icon="mdi:cash-refund" class="menu-icon"></iconify-icon>
                                        </button>
                                    @endif
                                    
                                    <form action="{{ route('admin.enrollments.destroy', $enrollment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" 
                                                onclick="return confirm('Are you sure you want to permanently delete this enrollment?')" 
                                                title="Delete Permanently">
                                            <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Refund Modal -->
                        @if($enrollment->transaction)
                        <div class="modal fade" id="refundModal{{ $enrollment->id }}" tabindex="-1" aria-labelledby="refundModalLabel{{ $enrollment->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.transactions.refund', $enrollment->transaction) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="refundModalLabel{{ $enrollment->id }}">Process Refund</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Enrollment Details</label>
                                                <p class="mb-1"><strong>Student:</strong> {{ $enrollment->user->name }}</p>
                                                <p class="mb-1"><strong>Course:</strong> {{ $enrollment->course->title }}</p>
                                                <p class="mb-1"><strong>Amount:</strong> ${{ number_format($enrollment->amount, 2) }}</p>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="refund_amount" class="form-label">Refund Amount ($)</label>
                                                <input type="number" 
                                                       step="0.01" 
                                                       class="form-control" 
                                                       id="refund_amount" 
                                                       name="refund_amount" 
                                                       value="{{ $enrollment->amount }}"
                                                       max="{{ $enrollment->amount }}"
                                                       required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="refund_reason" class="form-label">Refund Reason</label>
                                                <textarea class="form-control" 
                                                          id="refund_reason" 
                                                          name="refund_reason" 
                                                          rows="3" 
                                                          required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Process Refund</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <iconify-icon icon="mdi:cancel" class="icon-3x mb-2"></iconify-icon>
                                    <p>No cancelled enrollments found.</p>
                                    @if(request('search') || request('refund_status'))
                                        <a href="{{ route('admin.enrollments.cancelled') }}" class="btn btn-sm btn-primary">Clear Filters</a>
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
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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