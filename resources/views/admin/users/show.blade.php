@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">User Details</h6>
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
            <li class="fw-medium">{{ $user->name }}</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
    @endif

    <div class="row gy-4">
        <!-- Left Column - User Profile -->
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Profile Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-20">
                        <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" 
                             class=" h-21-px rounded-circle object-fit-cover mb-3">
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-secondary-light mb-2">{{ $user->email }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge {{ $user->is_verified ? 'bg-success-600' : 'bg-warning-600' }} text-white px-12 py-6 radius-8">
                                {{ $user->is_verified ? 'Verified' : 'Unverified' }}
                            </span>
                            <span class="badge {{ $user->status === 'active' ? 'bg-success-600' : ($user->status === 'pending' ? 'bg-warning-600' : 'bg-danger-600') }} text-white px-12 py-6 radius-8">
                                {{ ucfirst($user->status ?? 'Active') }}
                            </span>
                        </div>
                    </div>

                    <table class="table table-borderless">
                        <tr>
                            <td class="text-secondary-light">User ID:</td>
                            <td class="fw-medium">{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Candidate ID:</td>
                            <td class="fw-medium">{{ $user->candidate_id ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Phone:</td>
                            <td class="fw-medium">{{ $user->full_phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Account Type:</td>
                            <td class="fw-medium">{{ ucfirst($user->account_type ?? 'Learner') }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Role:</td>
                            <td class="fw-medium">{{ ucfirst($user->role ?? 'User') }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Joined:</td>
                            <td class="fw-medium">{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Last Login:</td>
                            <td class="fw-medium">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Email Verified:</td>
                            <td class="fw-medium">
                                @if($user->email_verified_at)
                                    <span class="text-success-600">{{ $user->email_verified_at->format('M d, Y') }}</span>
                                @else
                                    <span class="text-warning-600">Not verified</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if($user->bio)
                    <div class="mt-16">
                        <label class="form-label fw-semibold">Bio</label>
                        <p class="text-secondary-light">{{ $user->bio }}</p>
                    </div>
                    @endif

                    <div class="border-top pt-16 mt-16">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Contact Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-secondary-light">Address:</td>
                            <td class="fw-medium">{{ $user->full_address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Country:</td>
                            <td class="fw-medium">{{ $user->country ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">City:</td>
                            <td class="fw-medium">{{ $user->city ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">State:</td>
                            <td class="fw-medium">{{ $user->state ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary-light">Postal Code:</td>
                            <td class="fw-medium">{{ $user->postal_code ?? 'N/A' }}</td>
                        </tr>
                    </table>

                    @if($user->company || $user->job_title)
                    <div class="mt-16">
                        <label class="form-label fw-semibold">Professional Info</label>
                        <p class="mb-1"><strong>Company:</strong> {{ $user->company ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Job Title:</strong> {{ $user->job_title ?? 'N/A' }}</p>
                    </div>
                    @endif

                    @if($user->website || $user->linkedin_url)
                    <div class="mt-16">
                        <label class="form-label fw-semibold">Social Links</label>
                        @if($user->website)
                        <p class="mb-1">
                            <a href="{{ $user->website }}" target="_blank" class="text-primary-600">
                                <iconify-icon icon="solar:link-outline"></iconify-icon> Website
                            </a>
                        </p>
                        @endif
                        @if($user->linkedin_url)
                        <p class="mb-0">
                            <a href="{{ $user->linkedin_url }}" target="_blank" class="text-primary-600">
                                <iconify-icon icon="mdi:linkedin"></iconify-icon> LinkedIn
                            </a>
                        </p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Statistics Summary -->
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Enrolled Courses:</span>
                        <span class="fw-bold">{{ $user->enrollments->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Completed Courses:</span>
                        <span class="fw-bold">{{ $user->completedEnrollments->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Active Courses:</span>
                        <span class="fw-bold">{{ $user->activeEnrollments->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Assessments Submitted:</span>
                        <span class="fw-bold">{{ $user->assessmentSubmissions->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Overall Progress:</span>
                        <span class="fw-bold">{{ $user->overall_progress }}%</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Profile Completion:</span>
                        <span class="fw-bold">{{ $user->profile_completion_percentage }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Details -->
        <div class="col-lg-8">
            <!-- Enrollments Card -->
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0">Course Enrollments ({{ $user->enrollments->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    @if($user->enrollments->count() > 0)
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Enrolled Date</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Completed</th>
                                    <th>Certificate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($enrollment->course)
                                                <img src="{{ $enrollment->course->image_url }}" class="w-40-px h-40-px rounded-8 object-fit-cover">
                                                <div>
                                                    <a href="{{ route('admin.courses.show', $enrollment->course) }}" class="fw-medium text-primary-600">
                                                        {{ $enrollment->course->title }}
                                                    </a>
                                                    <p class="text-sm text-secondary-light mb-0">{{ $enrollment->course->code ?? '' }}</p>
                                                </div>
                                            @else
                                                <span class="text-muted">Course not found</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $enrollment->enrollment_date ? $enrollment->enrollment_date->format('M d, Y') : $enrollment->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                                                <div class="progress-bar bg-primary-600" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                                            </div>
                                            <span>{{ $enrollment->progress ?? 0 }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $enrollment->status == 'enrolled' ? 'success-600' : ($enrollment->status == 'completed' ? 'info-600' : 'warning-600') }} text-white">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">No course enrollments found.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Assessment Submissions Card -->
            <div class="card mt-24">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0">Assessment Submissions ({{ $user->assessmentSubmissions->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    @if($user->assessmentSubmissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Assessment</th>
                                    <th>Course</th>
                                    <th>Submitted</th>
                                    <th>Score</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->assessmentSubmissions->take(10) as $submission)
                                <tr>
                                    <td>
                                        <span class="fw-medium">{{ $submission->assessment->title ?? 'N/A' }}</span>
                                        <p class="text-sm text-secondary-light mb-0">
                                            {{ ucfirst(str_replace('_', ' ', $submission->assessment->assessment_level ?? '')) }}
                                        </p>
                                    </td>
                                    <td>{{ $submission->assessment->course->short_title ?? 'N/A' }}</td>
                                    <td>{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y H:i') : 'N/A' }}</td>
                                    <td>
                                        @if($submission->percentage !== null)
                                            <span class="fw-medium {{ $submission->passed ? 'text-success-600' : 'text-danger-600' }}">
                                                {{ number_format($submission->percentage, 1) }}%
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $submission->status_badge }}">
                                            {{ $submission->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.assessments.submission.view', $submission->id) }}" 
                                           class="bg-info-focus bg-hover-info-200 text-info-600 w-30-px h-30-px d-inline-flex justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="majesticons:eye-line"></iconify-icon>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($user->assessmentSubmissions->count() > 10)
                    <div class="text-center py-3">
                        <a href="#" class="text-primary-600">View all {{ $user->assessmentSubmissions->count() }} submissions →</a>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">No assessment submissions found.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Transactions Card -->
            @if(isset($user->transactions) && $user->transactions->count() > 0)
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Transaction History</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Course</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->transactions->take(5) as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_id ?? $transaction->id }}</td>
                                    <td>{{ $transaction->enrollment->course->title ?? 'N/A' }}</td>
                                    <td>${{ number_format($transaction->amount, 2) }}</td>
                                    <td>{{ ucfirst($transaction->payment_method ?? 'N/A') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->status == 'completed' ? 'success-600' : 'warning-600' }} text-white">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Certificates Card -->
            @if($user->enrollments->where('certificate_generated', true)->count() > 0)
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Certificates Earned</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($user->enrollments->where('certificate_generated', true) as $enrollment)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-8">
                                <div class="w-50-px h-50-px bg-success-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:document-text-outline" class="text-success-600 icon-2x"></iconify-icon>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="fw-medium mb-1">{{ $enrollment->course->title ?? 'Course Certificate' }}</p>
                                    <p class="text-sm text-secondary-light mb-1">Certificate #: {{ $enrollment->certificate_number }}</p>
                                    <a href="{{ route('admin.certificates.download', $enrollment->id) }}" class="text-primary-600 text-sm">
                                        Download Certificate →
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .w-100-px { width: 100px; }
    .h-100-px { height: 100px; }
    .w-40-px { width: 40px; }
    .h-40-px { height: 40px; }
    .w-50-px { width: 50px; }
    .h-50-px { height: 50px; }
    .w-30-px { width: 30px; }
    .h-30-px { height: 30px; }
    .rounded-8 { border-radius: 8px; }
    .object-fit-cover { object-fit: cover; }
    .mb-20 { margin-bottom: 20px; }
    .mt-16 { margin-top: 16px; }
    .pt-16 { padding-top: 16px; }
    .icon-2x { font-size: 2rem; }
</style>
@endpush 