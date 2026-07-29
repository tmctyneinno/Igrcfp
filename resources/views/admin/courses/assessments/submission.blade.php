@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Review Submission</h6>
        <a href="{{ route('admin.assessments.submissions', $assessment->id) }}" class="btn btn-outline-primary">
            <iconify-icon icon="solar:arrow-left-linear" class="me-2"></iconify-icon> Back to Submissions
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
    @endif

    <div class="row gy-4">
        <!-- Left Column: Student Info & Grading (STICKY) -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px; z-index: 10;">
                
                <!-- Student Details Card -->
                <div class="card mb-24">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="card-title mb-0">Student Details</h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @php
                                $avatar = $submission->user->profile_picture ?? $submission->user->avatar;
                                $hasAvatar = $avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($avatar);
                            @endphp
                            
                            @if($hasAvatar)
                                <img src="{{ Storage::url($avatar) }}" class="w-50-px h-50-px rounded-circle object-fit-cover">
                            @else
                                <div class="w-50-px h-50-px bg-primary-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <span class="text-primary-600 fw-semibold">{{ strtoupper(substr($submission->user->name ?? 'U', 0, 1)) }}</span>
                                </div>
                            @endif

                            <div>
                                <p class="fw-semibold mb-0">{{ $submission->user->name ?? 'Unknown' }}</p>
                                <p class="text-sm text-secondary-light mb-0">{{ $submission->user->email ?? '' }}</p>
                            </div>
                        </div>

                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-secondary-light ps-0">Assessment:</td>
                                <td class="fw-medium text-end pe-0">{{ $assessment->title }}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary-light ps-0">Course:</td>
                                <td class="fw-medium text-end pe-0">{{ $assessment->course->title ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary-light ps-0">Submitted:</td>
                                <td class="fw-medium text-end pe-0">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y H:i') : 'Not submitted' }}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary-light ps-0">Status:</td>
                                <td class="text-end pe-0">
                                    <span class="badge bg-{{ $submission->status == 'graded' ? 'success' : 'warning' }}-100 text-{{ $submission->status == 'graded' ? 'success' : 'warning' }}-600 radius-4 px-8 py-4">
                                        {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-secondary-light ps-0">Auto Score:</td>
                                <td class="fw-medium text-end pe-0">
                                    @if($submission->percentage !== null)
                                        {{ number_format($submission->percentage, 1) }}%
                                        <span class="text-secondary-light small">({{ $submission->score }}/{{ $assessment->total_marks }})</span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Grading Form Card -->
                <div class="card" id="grade">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="card-title mb-0">Grade Student</h6>
                    </div>
                    <div class="card-body p-24">
                        @if($submission->status === 'graded')
                            <div class="alert alert-success mb-3">
                                <p class="mb-1"><strong>Graded by:</strong> {{ $submission->grader->name ?? 'Admin' }}</p>
                                <p class="mb-0"><strong>Date:</strong> {{ $submission->graded_at?->format('M d, Y H:i') }}</p>
                            </div>
                        @endif

                        <form action="{{ route('admin.assessments.submission.grade', $submission->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Final Score <span class="text-danger">*</span></label>
                                <input
                                    type="number"
                                    name="score"
                                    class="form-control"
                                    step="0.01"
                                    min="0"
                                    max="{{ $assessment->total_marks ?? 100 }}"
                                    value="{{ old('score', $submission->score) }}"
                                    required
                                >
                                <small class="text-muted">Maximum score: {{ $assessment->total_marks ?? 100 }}</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Feedback</label>
                                <textarea name="feedback" class="form-control" rows="5" placeholder="Provide detailed feedback to the student...">{{ old('feedback', $submission->feedback) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Submit Grade
                            </button>
                        </form>
                    </div>
                </div>

            </div> <!-- End Sticky Wrapper -->
        </div>

        <!-- Right Column: Submission Content -->
        <div class="col-lg-8">
            
            <!-- Part A: Quiz / MCQ Responses -->
            @if(isset($mcqQuestions) && $mcqQuestions->isNotEmpty())
            <div class="card mb-24">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0 text-primary-600">
                        <iconify-icon icon="solar:test-tube-minimalistic-linear" class="me-2"></iconify-icon> Part A: Quiz Responses
                    </h6>
                    <span class="badge bg-primary-100 text-primary-600">{{ $mcqQuestions->count() }} Questions</span>
                </div>
                <div class="card-body p-24">
                    <div class="row g-3">
                        @foreach($mcqQuestions as $item)
                            @php
                                $q = $item['question'];
                                $r = $item['response'] ?? [];
                                $isCorrect = $r['correct'] ?? false;
                            @endphp
                            <div class="col-md-6">
                                <div class="p-16 border rounded-8 {{ $isCorrect ? 'border-success-200 bg-success-50' : 'border-danger-200 bg-danger-50' }}">
                                    <p class="fw-medium mb-2 small text-uppercase text-secondary-light">Question {{ $loop->iteration }}</p>
                                    <p class="mb-2">{{ Str::limit($q->question_text, 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="small"><strong>Answer:</strong> {{ $r['answer'] ?? 'N/A' }}</span>
                                        <span class="badge {{ $isCorrect ? 'bg-success-600' : 'bg-danger-600' }} text-white">
                                            {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Part B: Essay Responses -->
            @if(isset($essayQuestions) && $essayQuestions->isNotEmpty())
            <div class="card mb-24">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0 text-info-600">
                        <iconify-icon icon="solar:pen-new-square-linear" class="me-2"></iconify-icon> Part B: Essay Responses
                    </h6>
                    <span class="badge bg-info-100 text-info-600">{{ $essayQuestions->count() }} Essays</span>
                </div>
                <div class="card-body p-24">
                    @foreach($essayQuestions as $item)
                        @php
                            $q = $item['question'];
                            $r = $item['response'] ?? [];
                            $answerText = $r['answer'] ?? null;
                            $hasAnswer = !empty($answerText);
                        @endphp
                        
                        <div class="border rounded-8 p-16 mb-3 last-child-mb-0 bg-light-50">
                            <!-- Question Prompt -->
                            <p class="fw-semibold mb-3 text-dark">{{ $q->question_text }}</p>
                            
                            <!-- Display Text Content -->
                            @if($hasAnswer)
                                <div class="bg-white border rounded-6 p-16 essay-content">
                                    {!! $answerText !!}
                                </div>
                            @else
                                <div class="alert alert-warning py-2 mb-0">
                                    <small><i class="fas fa-exclamation-circle me-1"></i> No text answer was submitted for this question.</small>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Part C: Project / Case Study -->
            @if((isset($projectQuestions) && $projectQuestions->isNotEmpty()) || (isset($uploadedFiles) && $uploadedFiles->isNotEmpty()))
            <div class="card mb-24">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0 text-warning-600">
                        <iconify-icon icon="solar:document-text-linear" class="me-2"></iconify-icon> Project / Case Study Submission
                    </h6>
                </div>
                <div class="card-body p-24">
                    @if(isset($uploadedFiles) && $uploadedFiles->isNotEmpty())
                        @foreach($uploadedFiles as $file)
                            <div class="d-flex align-items-center gap-3 p-16 bg-light rounded-8 mb-3">
                                <div class="w-50-px h-50-px bg-warning-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:folder-with-files-linear" class="text-warning-600 icon-2x"></iconify-icon>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="fw-medium mb-1">{{ $file['name'] ?? 'Project Document' }}</p>
                                    <p class="text-sm text-secondary-light mb-0">
                                        {{ !empty($file['size']) ? round($file['size'] / 1024, 2) . ' KB' : 'Size unavailable' }}
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ Storage::url($file['path']) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <iconify-icon icon="solar:eye-linear" class="me-1"></iconify-icon> View
                                    </a>
                                    <a href="{{ Storage::url($file['path']) }}" download class="btn btn-outline-success btn-sm">
                                        <iconify-icon icon="solar:download-linear" class="me-1"></iconify-icon> Download
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @elseif($submission->submission_file_path)
                         <div class="d-flex align-items-center gap-3 p-16 bg-light rounded-8">
                            <div class="w-50-px h-50-px bg-warning-100 rounded-circle d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:folder-with-files-linear" class="text-warning-600 icon-2x"></iconify-icon>
                            </div>
                            <div class="flex-grow-1">
                                <p class="fw-medium mb-1">{{ $submission->submission_file_name ?? 'Main Submission' }}</p>
                            </div>
                            <a href="{{ Storage::url($submission->submission_file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">View File</a>
                        </div>
                    @else
                        <p class="text-muted mb-0">No specific project files were uploaded.</p>
                    @endif
                    
                    @if(isset($projectQuestions) && $projectQuestions->isNotEmpty())
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="fw-semibold mb-3">Project Prompts & Responses</h6>
                            @foreach($projectQuestions as $item)
                                <div class="mb-3">
                                    <p class="fw-medium small text-secondary-light mb-1">{{ $item['question']->question_text }}</p>
                                    <p class="mb-0">{{ $item['response']['answer'] ?? 'No text response provided.' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .last-child-mb-0:last-child { margin-bottom: 0; }
    .icon-xl { font-size: 1.5rem; }
    .icon-2x { font-size: 2rem; }
    /* Ensure sticky works smoothly */
    .sticky-top { position: -webkit-sticky; position: sticky; }
    
</style>
<style>
    .last-child-mb-0:last-child { margin-bottom: 0; }
    .icon-xl { font-size: 1.5rem; }
    .icon-2x { font-size: 2rem; }
    .sticky-top { position: -webkit-sticky; position: sticky; }
    
    /* Essay Content Styling */
    .essay-content {
        line-height: 1.6;
        color: #333;
    }
    .essay-content p { margin-bottom: 1rem; }
    .essay-content ul, .essay-content ol { margin-left: 1.5rem; margin-bottom: 1rem; }
    .essay-content h1, .essay-content h2, .essay-content h3 { margin-top: 1.5rem; margin-bottom: 0.5rem; font-weight: 600; }
</style>
@endpush