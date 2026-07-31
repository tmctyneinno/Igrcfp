@extends('admin.layouts.app')

@section('content')
<!-- ADDED pb-5 to ensure bottom padding so footer doesn't overlap -->
<div class="dashboard-main-body pb-5">
    <!-- Breadcrumb & Header -->
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
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ADDED h-100 to row to ensure columns stretch equally if needed -->
    <div class="row gy-4 h-100">
        
        <!-- Left Column: Profile & Stats -->
        <!-- ADDED d-flex flex-column to allow internal stretching -->
        <div class="col-lg-4 d-flex flex-column">
            
            <!-- Profile Card -->
            <div class="card">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="card-title mb-0">Profile Information</h6>
                </div>
                <div class="card-body p-24">
                    <div class="text-center mb-20">
                        @php
                            $avatarPath = $user->profile_picture ?? $user->avatar;
                            $hasAvatar = $avatarPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($avatarPath);
                        @endphp

                        @if($hasAvatar)
                            <img src="{{ Storage::url($avatarPath) }}" alt="{{ $user->name }}" 
                                 class="w-50-px h-50-px rounded-circle object-fit-cover mb-3 border border-2 border-light">
                        @else
                            <div class="w-50-px h-50-px rounded-circle bg-primary-100 d-flex align-items-center justify-content-center mx-auto mb-3 border border-2 border-light">
                                <span class="text-primary-600 fw-bold fs-4">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif 

                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-secondary-light mb-2">{{ $user->email }}</p>
                        
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <span class="badge {{ $user->email_verified_at ? 'bg-success-100 text-success-600' : 'bg-warning-100 text-warning-600' }} px-12 py-6 radius-8">
                                {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                            </span>
                            <span class="badge {{ $user->status === 'active' ? 'bg-success-100 text-success-600' : 'bg-danger-100 text-danger-600' }} px-12 py-6 radius-8">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-secondary-light ps-0">User ID:</td>
                                    <td class="fw-medium text-end pe-0">#{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light ps-0">Phone:</td>
                                    <td class="fw-medium text-end pe-0">{{ $user->full_phone ?? $user->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light ps-0">Role:</td>
                                    <td class="fw-medium text-end pe-0">{{ ucfirst($user->role ?? 'Learner') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light ps-0">Joined:</td>
                                    <td class="fw-medium text-end pe-0">{{ $user->created_at->format('M d, Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
 
                    <div class="mt-16 pt-16 border-top">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary w-100">
                             Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Scholarship Management Card -->
            <!-- ADDED mt-24 and mb-4 for spacing -->
            <div class="card mt-24 border-emerald-200 mb-4">
                <div class="card-header bg-emerald-50 border-bottom border-emerald-100 py-16 px-24">
                    <h6 class="card-title mb-0 text-emerald-700">Scholarship Access</h6>
                </div>
                <div class="card-body p-24">
                    
                    <!-- 1. Global Category Access Toggle -->
                    <form action="{{ route('admin.users.toggle-scholarship', $user) }}" method="POST">
                        @csrf
                        <div class="d-flex align-items-center justify-content-between mb-16 pb-16 border-bottom border-neutral-100">
                            <div>
                                <p class="mb-1 fw-medium text-dark">Global Category Access</p>
                                <p class="text-xs text-secondary-light mb-0">
                                    Grants free enrollment for all eligible certification categories.
                                </p>
                            </div>
                            <div class="form-check form-switch ms-3">
                                <input class="form-check-input" type="checkbox" name="is_scholarship_applicant" id="scholarshipToggle" 
                                       {{ $user->is_scholarship_applicant ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                <label class="form-check-label" for="scholarshipToggle"></label>
                            </div>
                        </div>
                    </form>

                    <!-- 2. Individual Course Assignments -->
                    <div class="mt-16">
                        <div class="d-flex align-items-center justify-content-between mb-12">
                            <p class="fw-medium text-dark mb-0">Individual Course Assignments</p>
                            <a href="{{ route('admin.users.scholarship-courses', $user) }}" 
                               class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                                <iconify-icon icon="solar:pen-new-square-linear" class="icon"></iconify-icon>
                                Manage
                            </a>
                        </div>
                        
                        @if(isset($user->scholarshipCourses) && $user->scholarshipCourses->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($user->scholarshipCourses as $sc)
                                    <span class="badge bg-primary-100 text-black radius-4 px-10 py-3 text-xs fw-normal border border-primary-200">
                                        {{ Str::limit($sc->title, 25) }}
                                    </span>
                                @endforeach
                            </div>
                            <p class="text-xs text-secondary-light mt-2 mb-0">
                                {{ $user->scholarshipCourses->count() }} course(s) assigned individually.
                            </p>
                        @else
                            <p class="text-xs text-secondary-light fst-italic mb-0">
                                No individual courses assigned. Use "Manage" to assign specific courses.
                            </p>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <!-- Right Column: Main Content -->
        <div class="col-lg-8">
            
            <!-- Statistics Row -->
            <div class="row gy-3 mb-24">
                <div class="col-md-4">
                    <div class="card p-20 h-100 bg-primary-50 border-primary-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="w-40-px h-40-px bg-primary-100 rounded-circle d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:book-bookmark-linear" class="text-primary-600"></iconify-icon>
                            </div>
                            <div>
                                <span class="text-secondary-light small d-block">Enrolled Courses</span>
                                <h5 class="mb-0 fw-bold text-primary-700">{{ $user->enrollments->count() }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-20 h-100 bg-success-50 border-success-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="w-40-px h-40-px bg-success-100 rounded-circle d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:check-circle-linear" class="text-success-600"></iconify-icon>
                            </div>
                            <div>
                                <span class="text-secondary-light small d-block">Completed</span>
                                <h5 class="mb-0 fw-bold text-success-700">{{ $user->enrollments->where('status', 'completed')->count() }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-20 h-100 bg-info-50 border-info-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="w-40-px h-40-px bg-info-100 rounded-circle d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:test-tube-minimalistic-linear" class="text-info-600"></iconify-icon>
                            </div>
                            <div>
                                <span class="text-secondary-light small d-block">Assessments</span>
                                <h5 class="mb-0 fw-bold text-info-700">{{ $user->assessmentSubmissions->count() }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrollments Table WITH ASSESSMENT STAGE -->
            <div class="card mb-24">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0">Course Enrollments</h6>
                </div>  
                <div class="card-body p-0">
                    @if($user->enrollments->count() > 0)
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table mb-0">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Date</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Assessment Stage</th>
                                    <th>Certificate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($enrollment->course)
                                                @php
                                                    $courseImg = $enrollment->course->image_url;
                                                    $hasCourseImg = $courseImg && \Illuminate\Support\Facades\Storage::disk('public')->exists($courseImg);
                                                @endphp
                                                
                                                @if($hasCourseImg)
                                                    <img src="{{ Storage::url($courseImg) }}" class="w-40-px h-40-px rounded-8 object-fit-cover">
                                                @else
                                                    <div class="w-40-px h-40-px rounded-8 bg-light d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:book-linear" class="text-muted"></iconify-icon>
                                                    </div>
                                                @endif

                                                <div>
                                                    <a href="{{ route('admin.courses.show', $enrollment->course->slug) }}" class="fw-medium text-primary-600 hover-text-primary-700">
                                                        {{ Str::limit($enrollment->course->title, 40) }}
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-muted">Course removed</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                                                <div class="progress-bar bg-primary-600" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                                            </div>
                                            <span class="small">{{ $enrollment->progress ?? 0 }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $enrollment->status == 'completed' ? 'success' : ($enrollment->status == 'cancelled' ? 'danger' : 'warning') }}-100 text-{{ $enrollment->status == 'completed' ? 'success' : ($enrollment->status == 'cancelled' ? 'danger' : 'warning') }}-600 radius-4 px-8 py-4">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </td>
                                    
                                    {{-- Assessment Stage Cell --}}
                                    <td>
                                        @php
                                            $stage = $enrollment->assessment_stage ?? ['label' => 'Unknown', 'class' => 'secondary'];
                                        @endphp
                                        <span class="badge bg-{{ $stage['class'] }}-100 text-{{ $stage['class'] }}-600 radius-4 px-8 py-4">
                                            {{ $stage['label'] }}
                                        </span>
                                    </td>

                                    <td> 
                                        @if($enrollment->certificate_generated) 
                                            <a href="{{ route('admin.certificates.download', $enrollment->id) }}" class="text-success-600 hover-text-success-700" title="Download Certificate">
                                                <iconify-icon icon="solar:document-text-linear" class="icon-xl"></iconify-icon>
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

            <!-- Assessment Submissions -->
            <div class="card">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0">Recent Assessment Submissions</h6>
                </div>
                <div class="card-body p-0">
                    @if($user->assessmentSubmissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table mb-0">
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
                                @foreach($user->assessmentSubmissions->take(5) as $submission)
                                <tr>
                                    <td>
                                        <span class="fw-medium">{{ $submission->assessment->title ?? 'N/A' }}</span>
                                        <div class="small text-secondary-light">{{ ucfirst(str_replace('_', ' ', $submission->assessment->assessment_level ?? '')) }}</div>
                                    </td>
                                    <td>{{ $submission->assessment->course->title ?? 'N/A' }}</td>
                                    <td>{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        @if($submission->percentage !== null)
                                            <span class="fw-bold {{ $submission->passed ? 'text-success-600' : 'text-danger-600' }}">
                                                {{ number_format($submission->percentage, 1) }}%
                                            </span>
                                        @else
                                            <span class="text-muted">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $submission->status == 'graded' ? 'success' : 'warning' }}-100 text-{{ $submission->status == 'graded' ? 'success' : 'warning' }}-600 radius-4 px-8 py-4">
                                            {{ ucfirst($submission->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.assessments.submission.view', $submission->id) }}" 
                                           class="bg-info-focus bg-hover-info-200 text-info-600 w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle"
                                           title="View Submission">
                                            <iconify-icon icon="majesticons:eye-line"></iconify-icon>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">No assessment submissions found.</p>
                    </div>
                    @endif
                </div>
            </div>

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
    .w-32-px { width: 32px; }
    .h-32-px { height: 32px; }
    .rounded-8 { border-radius: 8px; }
    .object-fit-cover { object-fit: cover; }
    .mb-20 { margin-bottom: 20px; } 
    .mt-16 { margin-top: 16px; }
    .pt-16 { padding-top: 16px; }
    .p-20 { padding: 20px; }
    
    /* Custom Badge Colors */
    .bg-emerald-50 { background-color: #ecfdf5 !important; }
    .border-emerald-100 { border-color: #d1fae5 !important; }
    .border-emerald-200 { border-color: #a7f3d0 !important; }
    .text-emerald-700 { color: #047857 !important; }
</style>
@endpush