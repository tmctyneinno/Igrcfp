@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Transaction Details</h6>
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
            <li class="fw-medium">Details</li>
        </ul>
    </div>

    <div class="row gy-4">
        <!-- Transaction Information -->
        <div class="col-lg-8">
            <div class="card h-100 p-0 radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Transaction Information</h5>
                    <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : ($transaction->status === 'refunded' ? 'info' : 'danger')) }} px-24 py-8">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
                <div class="card-body p-24">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-secondary-light fw-medium">Transaction ID</label>
                            <p class="fw-semibold">{{ $transaction->transaction_id ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-secondary-light fw-medium">Reference</label>
                            <p class="fw-semibold">{{ $transaction->reference ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-secondary-light fw-medium">Session ID</label>
                            <p class="fw-semibold">{{ $transaction->session_id ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-secondary-light fw-medium">Payment Method</label>
                            <p class="fw-semibold text-uppercase">{{ $transaction->payment_method }}</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-secondary-light fw-medium">Amount</label>
                            <p class="fw-semibold text-success">${{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency) }}</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-secondary-light fw-medium">Date & Time</label>
                            <p class="fw-semibold">{{ $transaction->created_at->format('F d, Y - h:i A') }}</p>
                        </div>
                        @if($transaction->paid_at)
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-secondary-light fw-medium">Paid At</label>
                            <p class="fw-semibold">{{ $transaction->paid_at->format('F d, Y - h:i A') }}</p>
                        </div>
                        @endif
                        @if($transaction->refunded_at)
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-secondary-light fw-medium">Refunded At</label>
                            <p class="fw-semibold">{{ $transaction->refunded_at->format('F d, Y - h:i A') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="col-lg-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body p-24">
                    @if($transaction->user)
                    <div class="d-flex align-items-center mb-24">
                        <div class="flex-shrink-0 me-16">
                            <img src="{{ asset('storage/' . ($transaction->user->avatar ?? 'default-avatar.jpg')) }}" 
                                 alt="{{ $transaction->user->name }}" 
                                 class="w-80-px h-80-px rounded-circle object-fit-cover">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-8">{{ $transaction->user->name }}</h6>
                            <p class="text-secondary-light mb-4">{{ $transaction->user->email }}</p>
                            <p class="text-secondary-light mb-0">{{ $transaction->user->phone ?? 'No phone' }}</p>
                        </div>
                    </div>
                    @else
                    <p class="text-muted">Customer information not available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Enrollment Information -->
        @if($transaction->enrollment)
        <div class="col-lg-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h5 class="mb-0">Enrollment Information</h5>
                </div>
                <div class="card-body p-24">
                    <div class="mb-4">
                        <label class="form-label text-secondary-light fw-medium">Course</label>
                        <p class="fw-semibold">{{ $transaction->enrollment->course->title ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-secondary-light fw-medium">Enrollment Status</label>
                        <p>
                            <span class="badge bg-{{ $transaction->enrollment->status === 'enrolled' ? 'success' : ($transaction->enrollment->status === 'pending_payment' ? 'warning' : 'danger') }}">
                                {{ ucfirst($transaction->enrollment->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-secondary-light fw-medium">Enrollment Date</label>
                        <p class="fw-semibold">{{ $transaction->enrollment->enrollment_date->format('F d, Y') }}</p>
                    </div>
                    <a href="{{ route('admin.enrollments.show', $transaction->enrollment) }}" class="btn btn-outline-primary w-100">
                        View Enrollment Details
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Payment Details -->
        <div class="col-lg-6">
            <div class="card h-100 p-0 radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h5 class="mb-0">Payment Details</h5>
                </div>
                <div class="card-body p-24">
                    @if($transaction->payment_details)
                        @foreach($transaction->payment_details as $key => $value)
                            @if(!is_array($value))
                            <div class="mb-3">
                                <label class="form-label text-secondary-light fw-medium text-capitalize">{{ str_replace('_', ' ', $key) }}</label>
                                <p class="fw-semibold">{{ $value }}</p>
                            </div>
                            @endif
                        @endforeach
                    @else
                        <p class="text-muted">No additional payment details available.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="col-12">
            <div class="card p-24">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                        <iconify-icon icon="mdi:arrow-left" class="me-2"></iconify-icon>
                        Back to List
                    </a>
                    
                    @if($transaction->status == 'pending')
                        <form action="{{ route('admin.transactions.update-status', $transaction) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Mark this transaction as completed?')">
                                <iconify-icon icon="mdi:check" class="me-2"></iconify-icon>
                                Mark Completed
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.transactions.update-status', $transaction) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="failed">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Mark this transaction as failed?')">
                                <iconify-icon icon="mdi:close" class="me-2"></iconify-icon>
                                Mark Failed
                            </button>
                        </form>
                    @endif
                    
                    @if($transaction->status == 'completed' && !$transaction->refunded_at)
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#refundModal">
                            <iconify-icon icon="mdi:cash-refund" class="me-2"></iconify-icon>
                            Process Refund
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
@if($transaction->status == 'completed' && !$transaction->refunded_at)
<div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.transactions.refund', $transaction) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="refundModalLabel">Process Refund</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Transaction Amount</label>
                        <p class="fw-semibold">${{ number_format($transaction->amount, 2) }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="refund_amount" class="form-label">Refund Amount ($)</label>
                        <input type="number" 
                               step="0.01" 
                               class="form-control" 
                               id="refund_amount" 
                               name="refund_amount" 
                               value="{{ $transaction->amount }}"
                               max="{{ $transaction->amount }}"
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
                    <button type="submit" class="btn btn-warning">Process Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection