@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">
            @if(isset($course))
                Assessments: {{ $course->title }}
            @else
                All Assessments
            @endif
        </h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Assessments</li>
            @if(isset($course))
                <li>-</li>
                <li class="fw-medium">{{ Str::limit($course->title, 30) }}</li>
            @endif
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

    <!-- Statistics Cards -->
    <div class="row gy-4 mb-24">
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Total</span>
                            <h4 class="mb-0">{{ $statistics['total'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-purple-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:document-text-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Quizzes</span>
                            <h4 class="mb-0">{{ $statistics['quizzes'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-green-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:quiz-game" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Module</span>
                            <h4 class="mb-0">{{ $statistics['module_assessments'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-blue-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:clipboard-list" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Final Exams</span>
                            <h4 class="mb-0">{{ $statistics['final_exams'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-red-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:document" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Diploma</span>
                            <h4 class="mb-0">{{ $statistics['diploma'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-purple-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:medal-ribbon" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="fw-semibold text-secondary-light mb-1">Submissions</span>
                            <h4 class="mb-0">{{ $statistics['submissions'] }}</h4>
                        </div>
                        <div class="w-45-px h-45-px bg-info-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:upload-square-outline" class="text-white text-xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="card h-100 p-0 radius-12 mb-24">
        <div class="card-body p-24">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Course</label>
                    <select class="form-select" id="courseFilter" onchange="filterByCourse(this.value)">
                        <option value="">All Courses</option>
                        @foreach($courses as $courseOption)
                            <option value="{{ $courseOption->id }}" 
                                {{ (isset($course) && $course->id == $courseOption->id) ? 'selected' : '' }}>
                                {{ $courseOption->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Type</label>
                    <select class="form-select" id="levelFilter" onchange="filterByLevel(this.value)">
                        <option value="">All Types</option>
                        <option value="quiz">Quizzes</option>
                        <option value="module_assessment">Module Assessments</option>
                        <option value="final_exam">Final Exams</option>
                        <option value="diploma">Diploma</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="statusFilter" onchange="filterByStatus(this.value)">
                        <option value="">All</option>
                        <option value="active">Active</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search assessments..." 
                           value="{{ request('search') }}" onkeyup="debounceSearch(this.value, 500)">
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('admin.assessments.create') }}" class="btn btn-primary text-sm px-18 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl"></iconify-icon>
                        New Assessment
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Assessments List -->
    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div>
                <h5 class="mb-1">
                    @if(isset($course))
                        {{ $course->title }} - Assessments
                    @else
                        All Assessments
                    @endif
                </h5>
                <p class="text-secondary-light text-sm mb-0">
                    {{ $assessments->total() }} assessments found
                </p>
            </div>
            @if(isset($course))
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadAssessmentModal">
                <iconify-icon icon="solar:upload-outline" class="icon"></iconify-icon>
                Quick Upload
            </button>
            @endif
        </div>

        <div class="card-body p-24">
            @if($assessments->count() > 0)
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th width="50">
                                    <div class="form-check style-check d-flex align-items-center">
                                        <input class="form-check-input radius-4 border border-neutral-400" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>#</th>
                                <th>Title</th>
                                <th>Course</th>
                                <th>Module</th>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Due Date</th>
                                <th>Questions</th>
                                <th>Submissions</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assessments as $index => $assessment)
                            <tr>
                                <td>
                                    <div class="form-check style-check d-flex align-items-center">
                                        <input class="form-check-input radius-4 border border-neutral-400 assessment-checkbox" 
                                               type="checkbox" name="assessment_ids[]" value="{{ $assessment->id }}">
                                    </div>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div>
                                        <span class="fw-semibold">{{ $assessment->title }}</span>
                                        <p class="text-secondary-light text-sm mb-0">{{ Str::limit($assessment->description, 40) }}</p>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info-600 text-white px-12 py-6 radius-8">
                                        {{ $assessment->course?->short_title ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($assessment->module)
                                        <span class="text-sm">Module {{ $assessment->module->module_number }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $typeColors = [
                                            'quiz' => 'bg-green-600',
                                            'module_assessment' => 'bg-blue-600',
                                            'final_exam' => 'bg-red-600',
                                            'diploma' => 'bg-purple-600'
                                        ];
                                        $typeLabels = [
                                            'quiz' => 'Quiz',
                                            'module_assessment' => 'Module',
                                            'final_exam' => 'Final Exam',
                                            'diploma' => 'Diploma'
                                        ];
                                        $color = $typeColors[$assessment->assessment_level] ?? 'bg-gray-600';
                                        $label = $typeLabels[$assessment->assessment_level] ?? ucfirst($assessment->assessment_level);
                                    @endphp
                                    <span class="badge {{ $color }} text-white px-12 py-6 radius-8">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td>
                                    @if($assessment->is_timed)
                                        <span class="d-flex align-items-center gap-1">
                                            <iconify-icon icon="solar:clock-circle-outline" class="text-primary-600"></iconify-icon>
                                            {{ $assessment->duration }} mins
                                        </span>
                                    @else
                                        <span class="text-muted">Untimed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($assessment->due_date)
                                        <div>
                                            <span class="text-sm">{{ $assessment->due_date->format('M d, Y') }}</span>
                                            @if($assessment->due_date < now())
                                                <span class="badge bg-danger-600 text-white px-8 py-4 radius-4 ms-1">Overdue</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $assessment->question_count ?: $assessment->questions_count ?? 0 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span>{{ $assessment->submissions_count }}</span>
                                        @if($assessment->pending_grading_count > 0)
                                            <span class="badge bg-warning-600 text-white px-8 py-4 radius-4">
                                                {{ $assessment->pending_grading_count }} pending
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-success-600',
                                            'draft' => 'bg-warning-600',
                                            'archived' => 'bg-secondary-600'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$assessment->status] ?? 'bg-secondary-600' }} text-white px-12 py-6 radius-8">
                                        {{ ucfirst($assessment->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <a href="{{ route('admin.assessments.show', $assessment->id) }}" 
                                           class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                           title="View">
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.assessments.edit', $assessment->id) }}"
                                           class="bg-success-focus text-success-600 bg-hover-success