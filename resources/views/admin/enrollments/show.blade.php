@extends('admin.layouts.app')

@section('title', 'Enrollment Details')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.enrollments.index') }}">Enrollments</a></li>
                    <li class="breadcrumb-item active" aria-current="page">#{{ $enrollment->id }}</li>
                </ol>
            </nav>
            <h2 class="h4 mb-0 text-gray-800">Enrollment Details</h2>
        </div>
        <div>
            <a href="{{ route('admin.enrollments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Enrollment & Course Info -->
        <div class="col-lg-8">
            <!-- Enrollment Status Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold">Enrollment Information</h5>
                    <span class="badge rounded-pill 
                        @if($enrollment->status == 'enrolled') bg-success 
                        @elseif($enrollment->status == 'pending_payment') bg-warning text-dark 
                        @elseif($enrollment->status == 'completed') bg-primary 
                        @else bg-danger 
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Enrollment ID</label>
                            <p class="mb-0 fs-5">#{{ $enrollment->id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Enrollment Date</label>
                            <p class="mb-0">{{ $enrollment->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Amount Paid</label>
                            <p class="mb-0 fs-5 text-success">£{{ number_format($enrollment->amount, 2) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Payment Method</label>
                            <p class="mb-0">{{ ucfirst($enrollment->payment_method ?? 'N/A') }}</p>
                        </div>
                    </div>
                    
                    @if($enrollment->progress > 0)
                    <div class="mt-3">
                        <label class="text-muted small text-uppercase fw-bold">Course Progress</label>
                        <div class="progress mt-1" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $enrollment->progress }}%" aria-valuenow="{{ $enrollment->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">{{ $enrollment->progress }}% Completed</small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Course Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold">Course Details</h5>
                </div>
                <div class="card-body">
                    @if($enrollment->course)
                    <div class="d-flex align-items-start">
                        {{-- CORRECTED IMAGE LOGIC --}}
                        @php
                            // Check if image_url exists and if the file physically exists in storage
                            $courseImage = $enrollment->course->image_url;
                            $hasCourseImage = $courseImage && \Illuminate\Support\Facades\Storage::disk('public')->exists($courseImage);
                        @endphp

                        @if($hasCourseImage)
                            <img src="{{ Storage::url($courseImage) }}" 
                                 alt="{{ $enrollment->course->title }}" 
                                 class="rounded me-3 shadow-sm" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else 
                            <div class="bg-light border rounded me-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="fas fa-book text-muted fa-2x"></i>
                            </div>
                        @endif
                        
                        <div>
                            <h5 class="mb-1">{{ $enrollment->course->title }}</h5>
                            <p class="text-muted mb-2">{{ Str::limit($enrollment->course->short_description, 150) }}</p>
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-dark border">{{ $enrollment->course->level }}</span>
                                <span class="badge bg-light text-dark border">{{ $enrollment->course->duration }} Hours</span>
                            </div>
                        </div>
                    </div>
                    @else
                    <p class="text-danger">Course data not found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Student & Transaction Info -->
        <div class="col-lg-4">
            <!-- Student Profile Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold">Student Profile</h5>
                </div>
                <div class="card-body text-center">
                    @if($enrollment->user)
                        <div class="mb-3">
                            {{-- CORRECTED USER IMAGE LOGIC --}}
                            @php
                                $userAvatar = $enrollment->user->profile_picture ?? $enrollment->user->avatar; // Check both fields
                                $hasUserImage = $userAvatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($userAvatar);
                            @endphp

                            @if($hasUserImage)
                                <img src="{{ Storage::url($userAvatar) }}" 
                                     class="rounded-circle mb-2 shadow-sm" 
                                     width="80" 
                                     height="80" 
                                     style="object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2 shadow-sm" style="width: 80px; height: 80px; font-size: 2rem;">
                                    {{ substr($enrollment->user->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <h5 class="mb-1">{{ $enrollment->user->name }}</h5>
                            <p class="text-muted small mb-0">{{ $enrollment->user->email }}</p>
                            @if($enrollment->user->phone)
                                <p class="text-muted small">{{ $enrollment->user->phone }}</p>
                            @endif
                        </div>
                        <hr>
                        <div class="text-start">
                            <p class="mb-1"><strong>User ID:</strong> #{{ $enrollment->user->id }}</p>
                            <p class="mb-1"><strong>Joined:</strong> {{ $enrollment->user->created_at->format('M d, Y') }}</p>
                            <p class="mb-0"><strong>Status:</strong> 
                                <span class="badge {{ $enrollment->user->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($enrollment->user->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.users.show', $enrollment->user->id) }}" class="btn btn-outline-primary btn-sm w-100">View Full Profile</a>
                        </div>
                    @else
                        <p class="text-muted">Student data unavailable.</p>
                    @endif
                </div>
            </div>

            <!-- Transaction Info (If available) -->
            @if($enrollment->transaction)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold">Transaction Details</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Transaction ID</span>
                            <span class="fw-bold">#{{ $enrollment->transaction->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Status</span>
                            <span class="badge {{ $enrollment->transaction->status == 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ ucfirst($enrollment->transaction->status) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Date</span>
                            <span>{{ $enrollment->transaction->created_at->format('M d, Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Actions</h6>
                    <form action="{{ route('admin.enrollments.update-status', $enrollment->id) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PATCH')
                        <div class="mb-2">
                            <label class="form-label small">Update Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="pending_payment" {{ $enrollment->status == 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                                <option value="enrolled" {{ $enrollment->status == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                <option value="completed" {{ $enrollment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $enrollment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Update Status</button>
                    </form>
                    
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="confirmDelete({{ $enrollment->id }})">
                        Delete Enrollment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
 
<!-- Delete Confirmation Modal Script -->
<script>
function confirmDelete(id) {
    if(confirm('Are you sure you want to delete this enrollment? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.enrollments.destroy", ":id") }}'.replace(':id', id);
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection