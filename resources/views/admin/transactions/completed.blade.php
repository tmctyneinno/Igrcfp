@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Completed Transactions</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.transactions.index') }}" class="hover-text-primary">Transactions</a>
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

    <!-- Summary Cards -->
    <div class="row gy-4 mb-24">
        <div class="col-xl-4 col-sm-6">
            <div class="card h-100 p-0 radius-12 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div class="">
                            <span class="mb-0 fw-medium text-secondary-light text-md">Total Completed</span>
                            <h6 class="fw-semibold mb-0 mt-2 text-success-main">{{ $transactions->total() ?? 0 }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-success-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:check-circle" class="icon text-2xl text-success-600"></iconify-icon>
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
                            <span class="mb-0 fw-medium text-secondary-light text-md">Total Revenue</span>
                            <h6 class="fw-semibold mb-0 mt-2 text-primary-light">
                                ${{ number_format($totalRevenue ?? 0, 2) }}
                            </h6>
                        </div>
                        <div class="w-50-px h-50-px bg-primary-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:cash-multiple" class="icon text-2xl text-primary-600"></iconify-icon>
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
                            <span class="mb-0 fw-medium text-secondary-light text-md">Average Amount</span>
                            <h6 class="fw-semibold mb-0 mt-2 text-info-main">
                                ${{ number_format($averageAmount ?? 0, 2) }}
                            </h6>
                        </div>
                        <div class="w-50-px h-50-px bg-info-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:chart-line" class="icon text-2xl text-info-600"></iconify-icon>
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
                    <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search completed transactions..." value="{{ request('search') }}">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
                
                <form method="GET" class="d-inline d-flex">
                    <select name="payment_method" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px ms-2" onchange="this.form.submit()">
                        <option value="">All Methods</option>
                        <option value="stripe" {{ request('payment_method') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                        <option value="paypal" {{ request('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                        <option value="free" {{ request('payment_method') == 'free' ? 'selected' : '' }}>Free</option>
                    </select>
                    
                    @if(request('search') || request('payment_method') || request('per_page') != 10)
                        <a href="{{ route('admin.transactions.completed') }}" class="btn btn-sm btn-outline-secondary ms-2">Clear</a>
                    @endif
                </form>
            </div>
            <div>
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                    <iconify-icon icon="mdi:arrow-left" class="icon text-xl line-height-1"></iconify-icon>
                    Back to All Transactions
                </a>
            </div>
        </div>

        <div class="card-body p-24">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col" width="50">S.L</th>
                            <th scope="col">Transaction ID</th>
                            <th scope="col">Student</th>
                            <th scope="col">Course</th>
                            <th scope="col">Date</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Payment Method</th>
                            <th scope="col">Paid At</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ substr($transaction->transaction_id, -12) }}</span>
                                    <small class="text-muted">Ref: {{ $transaction->reference ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-12">
                                        <img src="{{ asset('storage/' . ($transaction->user->avatar ?? 'default-avatar.jpg')) }}" 
                                             alt="{{ $transaction->user->name }}" 
                                             class="w-40-px h-40-px rounded-circle object-fit-cover">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0 fw-medium">{{ $transaction->user->name }}</h6>
                                        <span class="text-sm text-secondary-light">{{ $transaction->user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($transaction->enrollment && $transaction->enrollment->course)
                                    <span class="fw-medium">{{ Str::limit($transaction->enrollment->course->title, 30) }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $transaction->created_at->format('M d, Y') }}</span>
                                    <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium text-success">${{ number_format($transaction->amount, 2) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary text-uppercase">{{ $transaction->payment_method }}</span>
                            </td>
                            <td>
                                @if($transaction->paid_at)
                                    <div class="d-flex flex-column">
                                        <span>{{ $transaction->paid_at->format('M d, Y') }}</span>
                                        <small class="text-muted">{{ $transaction->paid_at->format('h:i A') }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    <a href="{{ route('admin.transactions.show', $transaction) }}" 
                                       class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                       title="View Details">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </a>
                                    
                                    
                                    @if($transaction->enrollment)
                                        <a href="{{ route('admin.enrollments.show', $transaction->enrollment) }}" 
                                           class="bg-success-focus bg-hover-success-200 text-success-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none"
                                           title="View Enrollment">
                                            <iconify-icon icon="mdi:account-school" class="menu-icon"></iconify-icon>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>

                       
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <iconify-icon icon="mdi:check-circle" class="icon-3x mb-2"></iconify-icon>
                                    <p>No completed transactions found.</p>
                                    @if(request('search') || request('payment_method'))
                                        <a href="{{ route('admin.transactions.completed') }}" class="btn btn-sm btn-primary">Clear Filters</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                <span>Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} entries</span>
                {{ $transactions->appends(request()->query())->links('vendor.pagination.custom') }}
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