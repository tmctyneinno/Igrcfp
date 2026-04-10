@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">User Enrollments</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.users.index') }}" class="hover-text-primary">Users</a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.users.show', $user) }}" class="hover-text-primary">{{ $user->name }}</a>
            </li>
            <li>-</li>
            <li class="fw-medium">Enrollments</li>
        </ul>
    </div>

    <!-- User Summary Card -->
    <div class="card mb-24">
        <div class="card-body p-24">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" 
                     class="w-30-px h-30-px rounded-circle object-fit-cover">
                <div>
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-secondary-light mb-0">{{ $user->email }}</p>
                </div>
                <div class="ms-auto">
                    <span class="badge bg-success-600 text-white px-16 py-8 radius-8">
                        {{ $enrollments->total() }} Total Enrollments
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollments Table -->
    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h6 class="card-title mb-0">Course Enrollments</h6>
        </div>
        <div class="card-body p-24">
            @if($enrollments->count() > 0)
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Enrolled Date</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Completed</th>
                                <th>Certificate</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($enrollment->course)
                                            <img src="{{ $enrollment->course->image_url }}" class="w-40-px h-40-px rounded-8 object-fit-cover">
                                            <div>
                                                <a href="{{ route('admin.courses.show', $enrollment->course) }}" class="fw-medium text-primary-600">
                                                    {{ $enrollment->course->title }}
                                                </a>
                                              <p class="text-sm text-secondary-light mb-0">
                                                <a href="{{ route('admin.courses.modules.index', $enrollment->course) }}" 
                                                class="text-primary-600 text-decoration-none hover-text-primary-700 d-inline-flex align-items-center gap-1">
                                                    <iconify-icon icon="solar:documents-outline" class="icon"></iconify-icon>
                                                    {{ $enrollment->course->modules->count() }} module{{ $enrollment->course->modules->count() != 1 ? 's' : '' }}
                                                    <iconify-icon icon="solar:arrow-right-outline" class="icon text-sm"></iconify-icon>
                                                </a>
                                            </p>
                                            </div>
                                        @else
                                            <span class="text-muted">Course not found</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $enrollment->enrollment_date ? $enrollment->enrollment_date->format('M d, Y') : $enrollment->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; width: 60px;">
                                            <div class="progress-bar bg-primary-600" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                                        </div>
                                        <span class="text-sm">{{ $enrollment->progress ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'enrolled' => 'success',
                                            'active' => 'info',
                                            'completed' => 'primary',
                                            'cancelled' => 'danger',
                                            'pending_payment' => 'warning'
                                        ];
                                        $color = $statusColors[$enrollment->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}-600 text-white px-12 py-6 radius-8">
                                        {{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($enrollment->amount > 0)
                                        <span class="fw-medium">${{ number_format($enrollment->amount, 2) }}</span>
                                        <br><small class="text-muted">{{ ucfirst($enrollment->payment_method ?? 'N/A') }}</small>
                                    @else
                                        <span class="badge bg-success-600 text-white">Free</span>
                                    @endif
                                </td>
                                <td>
                                    @if($enrollment->completed_at)
                                        {{ $enrollment->completed_at->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($enrollment->certificate_generated)
                                        <a href="{{ route('admin.certificates.download', $enrollment->id) }}" class="text-success-600">
                                            <iconify-icon icon="solar:document-text-outline"></iconify-icon> View
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.courses.show', $enrollment->course_id) }}" 
                                       class="bg-info-focus bg-hover-info-200 text-info-600 w-30-px h-30-px d-inline-flex justify-content-center align-items-center rounded-circle"
                                       title="View Course">
                                        <iconify-icon icon="majesticons:eye-line"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <span>Showing {{ $enrollments->firstItem() }} to {{ $enrollments->lastItem() }} of {{ $enrollments->total() }} entries</span>
                    {{ $enrollments->links('vendor.pagination.custom') }}
                </div>
            @else
                <div class="text-center py-5">
                    <iconify-icon icon="solar:document-text-outline" class="icon-4x text-muted mb-3"></iconify-icon>
                    <h6 class="text-muted mb-2">No enrollments found</h6>
                    <p class="text-muted">This user hasn't enrolled in any courses yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .w-40-px { width: 40px; }
    .h-40-px { height: 40px; }
    .w-60-px { width: 60px; }
    .h-60-px { height: 60px; }
    .w-30-px { width: 30px; }
    .h-30-px { height: 30px; }
    .rounded-8 { border-radius: 8px; }
    .object-fit-cover { object-fit: cover; }
    .px-16 { padding-left: 16px; padding-right: 16px; }
    .py-8 { padding-top: 8px; padding-bottom: 8px; }
    .icon-4x { font-size: 4rem; }
    .hover-bg-success-700:hover { background-color: #15803d !important; }
</style>
@endpush