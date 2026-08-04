@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">{{ $assessment->title }}</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.assessments.all') }}" class="hover-text-primary">Assessments</a>
            </li> 
            <li>-</li> 
            <li class="fw-medium">{{ Str::limit($assessment->title, 30) }}</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
    @endif

    <!-- Action Buttons -->
    <div class="d-flex gap-3 justify-content-end mb-24">
        <a href="{{ route('admin.assessments.edit', $assessment->id) }}" class="btn btn-primary">
            Edit Assessment
        </a>
        <a href="{{ route('admin.assessments.submissions', $assessment->id) }}" class="btn btn-info">
            View Submissions ({{ $assessment->submissions_count }})
        </a>
        <form action="{{ route('admin.assessments.destroy', $assessment->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this assessment?')">
                Delete
            </button>
        </form>
    </div>

    <div class="row gy-4">
        <!-- Left Column - Assessment Details -->
        <div class="col-lg-8">
            <!-- Overview Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Assessment Overview</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start gap-4 mb-4">
                        @php
                            $typeColors = [
                                'quiz' => 'bg-green-600',
                                'module_assessment' => 'bg-blue-600',
                                'final_exam' => 'bg-red-600',
                                'diploma' => 'bg-purple-600'
                            ];
                            $typeLabels = [
                                'quiz' => 'Quiz',
                                'module_assessment' => 'Module Assessment',
                                'final_exam' => 'Final Exam',
                                'diploma' => 'Diploma Project'
                            ];
                            $color = $typeColors[$assessment->assessment_level] ?? 'bg-gray-600';
                            $label = $typeLabels[$assessment->assessment_level] ?? ucfirst($assessment->assessment_level);
                        @endphp
                        <span class="badge {{ $color }} text-white px-12 py-6 radius-8">{{ $label }}</span>
                        
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
                    </div>

                    <h5 class="fw-semibold mb-2">{{ $assessment->title }}</h5>
                    <p class="text-secondary-light">{{ $assessment->description ?: 'No description provided' }}</p>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-secondary-light">Course:</td>
                                    <td class="fw-medium">{{ $assessment->course?->title }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light">Module:</td>
                                    <td class="fw-medium">
                                        @if($assessment->module)
                                            Module {{ $assessment->module->module_number }}: {{ $assessment->module->title }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light">Total Marks:</td>
                                    <td class="fw-medium">{{ $assessment->total_marks ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light">Passing Score:</td>
                                    <td class="fw-medium">{{ $assessment->passing_score ? $assessment->passing_score . '%' : '—' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-secondary-light">Duration:</td>
                                    <td class="fw-medium">
                                        @if($assessment->is_timed)
                                            {{ $assessment->duration }} minutes
                                        @else
                                            <span class="text-muted">Untimed</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light">Release Date:</td>
                                    <td class="fw-medium">{{ $assessment->release_date ? $assessment->release_date->format('M d, Y H:i') : 'Immediate' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light">Due Date:</td>
                                    <td class="fw-medium">
                                        @if($assessment->due_date)
                                            {{ $assessment->due_date->format('M d, Y H:i') }}
                                            @if($assessment->due_date < now())
                                                <span class="badge bg-danger-600 text-white ms-2">Overdue</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No deadline</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary-light">Weight:</td>
                                    <td class="fw-medium">{{ $assessment->weight ? $assessment->weight . '%' : '—' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security & Integrity Card -->
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Security & Integrity</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-40-px h-40-px bg-{{ $assessment->requires_identity_verification ? 'success' : 'secondary' }}-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:shield-check-outline" class="text-{{ $assessment->requires_identity_verification ? 'success' : 'secondary' }}-600"></iconify-icon>
                                </div>
                                <div>
                                    <span class="d-block fw-medium">Identity Verification</span>
                                    <small class="text-{{ $assessment->requires_identity_verification ? 'success' : 'secondary' }}-600">
                                        {{ $assessment->requires_identity_verification ? 'Required' : 'Not Required' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-40-px h-40-px bg-{{ $assessment->needs_manual_marking ? 'warning' : 'secondary' }}-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:pen-2-outline" class="text-{{ $assessment->needs_manual_marking ? 'warning' : 'secondary' }}-600"></iconify-icon>
                                </div>
                                <div>
                                    <span class="d-block fw-medium">Manual Marking</span>
                                    <small class="text-{{ $assessment->needs_manual_marking ? 'warning' : 'secondary' }}-600">
                                        {{ $assessment->needs_manual_marking ? 'Required' : 'Auto-graded' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-40-px h-40-px bg-{{ $assessment->allow_late_submissions ? 'info' : 'secondary' }}-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:clock-circle-outline" class="text-{{ $assessment->allow_late_submissions ? 'info' : 'secondary' }}-600"></iconify-icon>
                                </div>
                                <div>
                                    <span class="d-block fw-medium">Late Submissions</span>
                                    <small class="text-{{ $assessment->allow_late_submissions ? 'info' : 'secondary' }}-600">
                                        {{ $assessment->allow_late_submissions ? 'Allowed' : 'Not Allowed' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $quizQuestions = $assessment->questions->where('question_type', '!=', 'essay');
                $essayQuestions = $assessment->questions->where('question_type', 'essay');
            @endphp

            @if(in_array($assessment->assessment_level, ['quiz', 'module_assessment', 'final_exam']) && $quizQuestions->count() > 0)
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quiz Questions ({{ $quizQuestions->count() }})</h6>
                </div>
                <div class="card-body">
                    <div class="accordion" id="quizQuestionsAccordion">
                        @foreach($quizQuestions as $index => $question)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $question->id }}">
                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" 
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $question->id }}">
                                    <div class="d-flex align-items-center gap-3 w-100">
                                        <span class="badge bg-primary-600 text-white">Q{{ $index + 1 }}</span>
                                        <span class="fw-medium">{{ Str::limit($question->question_text, 80) }}</span>
                                        <span class="ms-auto me-3">{{ $question->points }} pts</span>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ $question->id }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" 
                                 data-bs-parent="#quizQuestionsAccordion">
                                <div class="accordion-body">
                                    <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</p>
                                    
                                    @if($question->options)
                                        <p><strong>Options:</strong></p>
                                        <ul>
                                            @foreach($question->options as $option)
                                                <li>{{ $option }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    
                                    <p><strong>Correct Answer:</strong> {{ $question->correct_answer }}</p>
                                    
                                    @if($question->explanation)
                                        <p><strong>Explanation:</strong> {{ $question->explanation }}</p>
                                    @endif
                                    
                                    <p><strong>Difficulty:</strong> {{ ucfirst($question->difficulty_level) }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if(in_array($assessment->assessment_level, ['quiz', 'module_assessment', 'final_exam']))
            <div class="card mt-24 border-warning-subtle">
                <div class="card-header bg-warning-50">
                    <h6 class="card-title mb-0">Part B: Essay Questions ({{ $essayQuestions->count() }})</h6>
                </div>
                <div class="card-body">
                    @if($essayQuestions->count() > 0)
                        <div class="accordion" id="essayQuestionsAccordion">
                            @foreach($essayQuestions as $index => $question)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEssay{{ $question->id }}">
                                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapseEssay{{ $question->id }}">
                                        <div class="d-flex align-items-center gap-3 w-100">
                                            <span class="badge bg-warning-600 text-white">B{{ $index + 1 }}</span>
                                            <span class="fw-medium">{{ Str::limit($question->question_text, 80) }}</span>
                                            <span class="ms-auto me-3">{{ $question->points }} pts</span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapseEssay{{ $question->id }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" 
                                     data-bs-parent="#essayQuestionsAccordion">
                                    <div class="accordion-body">
                                        <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</p>
                                        <p><strong>Examiner's Marking Guidance:</strong> {{ $question->explanation ?: 'Not provided' }}</p>
                                        <p><strong>Difficulty:</strong> {{ ucfirst($question->difficulty_level) }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-secondary mb-0">
                            No Part B essay questions have been added for this assessment.
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Project Brief (for diploma) -->
            @if($assessment->assessment_level == 'diploma' && $assessment->project_brief)
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Project Brief</h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-4 rounded-8">
                        {!! nl2br(e($assessment->project_brief)) !!}
                    </div>

                    @if($assessment->deliverables)
                        <h6 class="mt-4 mb-2">Deliverables:</h6>
                        <ul>
                            @foreach(explode("\n", $assessment->deliverables) as $deliverable)
                                @if(trim($deliverable))
                                    <li>{{ $deliverable }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @endif

                    @if($assessment->rubric)
                        <h6 class="mt-4 mb-2">Grading Rubric:</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Criteria</th>
                                        <th>Points</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assessment->rubric as $criterion)
                                        <tr>
                                            <td>{{ $criterion['criteria'] ?? '' }}</td>
                                            <td>{{ $criterion['points'] ?? '' }}</td>
                                            <td>{{ $criterion['description'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Statistics & File -->
        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary-light">Total Submissions:</span>
                            <span class="fw-medium">{{ $assessment->submissions_count }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary-light">Pending Grading:</span>
                            <span class="fw-medium {{ $assessment->pending_grading_count > 0 ? 'text-warning' : '' }}">
                                {{ $assessment->pending_grading_count }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary-light">Average Score:</span>
                            <span class="fw-medium">{{ $assessment->average_score ? number_format($assessment->average_score, 2) . '%' : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary-light">Highest Score:</span>
                            <span class="fw-medium">{{ $assessment->highest_score ? number_format($assessment->highest_score, 2) . '%' : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary-light">Lowest Score:</span>
                            <span class="fw-medium">{{ $assessment->lowest_score ? number_format($assessment->lowest_score, 2) . '%' : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary-light">Pass Rate:</span>
                            <span class="fw-medium">{{ $assessment->success_rate ? $assessment->success_rate . '%' : '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Card -->
            @if($assessment->file_path)
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Assessment File</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-8">
                        <div class="w-50-px h-50-px bg-primary-100 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:file-text-outline" class="text-primary-600 icon-2x"></iconify-icon>
                        </div>
                        <div class="flex-grow-1">
                            <p class="fw-medium mb-1">{{ $assessment->file_name }}</p>
                            <p class="text-sm text-secondary-light mb-0">{{ $assessment->formatted_file_size }}</p>
                        </div>
                        <a href="{{ $assessment->file_url }}" class="btn btn-sm btn-outline-primary" target="_blank">
                            <iconify-icon icon="solar:download-outline"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.assessments.edit', $assessment->id) }}" class="btn btn-outline-primary">
                            Edit Assessment
                        </a>
                        <a href="{{ route('admin.assessments.submissions', $assessment->id) }}" class="btn btn-outline-info">
                            View Submissions
                        </a>
                        <button type="button" class="btn btn-outline-success" onclick="window.print()">
                            Print Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 