@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Review Submission</h6>
        <div class="d-flex gap-2">
            <!-- NEW: Reject Enrollment Button -->
            @if(!$submission->enrollment?->certificate_generated) {{-- Only allow rejection if not certified --}}
            <button type="button" class="btn btn-outline-danger d-flex align-items-center gap-2" 
                    data-bs-toggle="modal" 
                    data-bs-target="#rejectEnrollmentModal">
                <iconify-icon icon="solar:user-remove-linear"></iconify-icon>
                Reject Enrollment
            </button>
            @endif

            <!-- Export Button -->
            <a href="{{ route('admin.assessments.submission.export', $submission->encoded_id) }}" 
               class="btn btn-outline-primary d-flex align-items-center gap-2">
                <iconify-icon icon="solar:document-text-linear"></iconify-icon>
                Export PDF
            </a>
            
            <a href="{{ route('admin.assessments.submissions', $assessment->id) }}" class="btn btn-outline-secondary">
                 Back to Submissions
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}</div>
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
                                        @php
                                            $earnedMarks = ($submission->percentage / 100) * ($assessment->total_marks ?? 100);
                                        @endphp
                                        
                                        {{ number_format($submission->percentage, 1) }}%
                                        <span class="text-secondary-light small">
                                            ({{ number_format($earnedMarks, 1) }}/{{ $assessment->total_marks ?? 100 }} pts)
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Grading Form Card -->
                <div class="card" id="grade">
                    <div class="card-header border-bottom bg-base py-16 px-24 d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Grade Student</h6>
                        @if($essayQuestions->isNotEmpty())
                            <span class="badge bg-warning-100 text-warning-600 radius-4 px-8 py-4">
                                Manual Grading Required
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-24">
                        @if($submission->status === 'graded')
                            <div class="alert alert-success mb-3">
                                <p class="mb-1"><strong>Graded by:</strong> {{ $submission->grader->name ?? 'Admin' }}</p>
                                <p class="mb-0"><strong>Date:</strong> {{ $submission->graded_at?->format('M d, Y H:i') }}</p>
                            </div>
                        @endif

                        <form action="{{ route('admin.assessments.submission.grade', $submission->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- 1. Auto-Graded Score (MCQ Only) -->
                            <div class="mb-3 p-16 bg-light rounded-8 border border-neutral-200">
                                <label class="form-label small fw-bold text-secondary-light mb-1">Part A: Auto-Graded (Quiz)</label>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-dark">
                                        {{ number_format($autoScore, 1) }} / {{ $mcqTotalPoints }} pts
                                    </span>
                                    <span class="badge bg-primary-100 text-primary-600">Locked</span>
                                </div>
                            </div>

                            <!-- 2. Manual Essay Grading Inputs -->
                            @if($essayQuestions->isNotEmpty())
                                <div class="mb-3"> 
                                    <label class="form-label fw-semibold text-dark">Part B: Essay Scores (Manual)</label>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless mb-0">
                                            <thead>
                                                <tr class="text-secondary-light small">
                                                    <th>Question</th>
                                                    <th class="text-end">Max Pts</th>
                                                    <th class="text-end">Awarded</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($essayQuestions as $index => $item)
                                                    @php
                                                        $savedEssayScores = data_get($submission->grader_comments, 'score_breakdown.essay_scores', []);
                                                        $submittedEssayScore = old(
                                                            'essay_scores.' . $item['question']->id,
                                                            $savedEssayScores[$item['question']->id] ?? null
                                                        );
                                                    @endphp
                                                    <tr>
                                                        <td class="ps-0">
                                                            <small class="d-block text-truncate" style="max-width: 150px;" title="{{ $item['question']->question_text }}">
                                                                Q{{ $index + 1 }}: {{ Str::limit($item['question']->question_text, 30) }}
                                                            </small>
                                                        </td>
                                                        <td class="text-end text-muted">{{ $item['max_points'] }}</td>
                                                        <td class="pe-0" style="min-width: 90px;">
                                                            <input type="number" 
                                                                name="essay_scores[{{ $item['question']->id }}]" 
                                                            class="form-control form-control-sm text-end fw-semibold essay-score-input" 
                                                                min="0" 
                                                                max="{{ $item['max_points'] }}" 
                                                                step="0.5"
                                                                value="{{ $submittedEssayScore }}"
                                                            placeholder="0"
                                                            aria-label="Awarded score for question {{ $index + 1 }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            <!-- 3. Final Total Score Calculation -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Final Total Score <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="final_score" id="final_score_input"
                                        class="form-control fw-bold"
                                        step="0.01" min="0" max="{{ $assessment->total_marks ?? 100 }}"
                                        value="{{ old('final_score', $submission->score ?? $autoScore) }}"
                                        readonly required>
                                    <span class="input-group-text bg-light">/ {{ $assessment->total_marks ?? 100 }}</span>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-info-circle"></i> Auto-score ({{ number_format($autoScore, 1) }}) + Manual Essays. Max: {{ $assessment->total_marks }}.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Feedback</label>
                                <textarea name="feedback" class="form-control" rows="5" placeholder="Provide detailed feedback...">{{ old('feedback', $submission->feedback) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="examiner_report" class="form-label fw-semibold">Examiner's Report</label>
                                <input type="file" name="examiner_report" id="examiner_report" class="form-control" accept=".pdf,.doc,.docx">
                                <small class="text-muted">PDF, DOC, or DOCX up to 10 MB.</small>
                                @if($submission->examiner_report_path)
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 p-2 border rounded-2 bg-light">
                                        <div class="d-flex align-items-center gap-2 min-width-0">
                                            <iconify-icon icon="solar:document-text-linear" class="text-primary icon-xl"></iconify-icon>
                                            <span class="small d-block flex-grow-1" style="min-width: 0; width: 120px; max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $submission->examiner_report_name }}">
                                                {{ $submission->examiner_report_name ?: 'Examiner report' }}
                                            </span>
                                        </div>
                                        <div class="d-flex gap-5">
                                            <a href="{{ route('admin.assessments.submission.examiner-report', $submission) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 text-nowrap">
                                                <iconify-icon icon="solar:eye-linear"></iconify-icon><span>View</span>
                                            </a>
                                            <a href="{{ route('admin.assessments.submission.examiner-report', [$submission, 'download' => 1]) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 text-nowrap">
                                                <iconify-icon icon="solar:download-linear" class="me-1"></iconify-icon> Download
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Submit Final Grade
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
                                $isCorrect = data_get($r, 'correct', false);

                                // Simple, consistent answer extraction
                                $raw = data_get($r, 'answer') ?? data_get($r, 'response') ?? data_get($r, 'answers') ?? null;

                                // Normalize options if present
                                $options = $q->options ?? null;
                                if (is_string($options)) {
                                    $opts = json_decode($options, true);
                                    if (json_last_error() === JSON_ERROR_NONE) $options = $opts;
                                }

                                $displayAnswer = null;
                                if ($raw === null || $raw === '') {
                                    $displayAnswer = 'No answer provided';
                                } else {
                                    // If options array exists and raw is an index or key, try to map to label
                                    if (is_array($options) && count($options) > 0) {
                                        if (is_numeric($raw) && isset($options[(int)$raw])) {
                                            $displayAnswer = $options[(int)$raw];
                                        } elseif (isset($options[$raw])) {
                                            $displayAnswer = $options[$raw];
                                        }
                                    }

                                    // Fallback to a simple stringified value
                                    if ($displayAnswer === null) {
                                        $displayAnswer = is_array($raw) ? implode(', ', array_map('strval', $raw)) : (string) $raw;
                                    }
                                }
                            @endphp
                            <div class="col-md-6">
                                <div class="p-16 border rounded-8 {{ $isCorrect ? 'border-success-200 bg-success-50' : 'border-danger-200 bg-danger-50' }}">
                                    <p class="fw-medium mb-2 small text-uppercase text-secondary-light"><b>Question {{ $loop->iteration }}</b></p>
                                    <p class="mb-2">{!! Str::limit($q->question_text, 80) !!}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="small"><strong>Answer:</strong> {!! nl2br(e($displayAnswer)) !!}</span>
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
                            $explanation = $q->explanation ?? null;
                            
                            // Calculate word count for the answer
                            $plainText = strip_tags($answerText);
                            $wordCount = str_word_count($plainText);
                            $charCount = strlen($plainText);
                        @endphp
                        
                        <div class="border rounded-8 p-16 mb-3 last-child-mb-0 bg-light-50">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                <span class="fw-semibold text-dark">Essay {{ $loop->iteration }}</span>
                                <span class="badge bg-neutral-200 text-neutral-600 radius-4 px-8 py-4 flex-shrink-0">
                                    {{ $q->points ?? 0 }} {{ Str::plural('pt', $q->points ?? 0) }}
                                </span>
                            </div>

                            <div class="essay-prompt mb-3">
                                <p class="essay-section-label mb-2">
                                    <b>Essay Question</b>
                                </p>
                                <div class="text-dark">{!! $q->question_text !!}</div>
                            </div>
                            
                            <!-- Enhanced Display Text Content with White Background -->
                            @if($hasAnswer)
                                <div class="essay-answer bg-white rounded-6 p-12">
                                    <!-- Header with badges -->
                                    <div class="d-flex align-items-center justify-content-between mb-3 ">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="essay-answer-icon">
                                                <iconify-icon icon="solar:pen-new-square-linear"></iconify-icon>
                                            </span>
                                            <p class="essay-section-label text-warning-700 mb-0 bg-warning-100">Student's response</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($wordCount > 50)
                                                <span class="badge bg-success-100 text-success-600 border border-success-200">
                                                    Detailed
                                                </span>
                                            @elseif($wordCount > 20)
                                                <span class="badge bg-warning-100 text-warning-600 border border-warning-200">
                                                    Moderate
                                                </span>
                                            @else
                                                <span class="badge bg-danger-100 text-danger-600 border border-danger-200">
                                                    Short
                                                </span>
                                            @endif
                                            <span class="badge bg-primary-50 text-primary-600 border border-primary-200">
                                                {{ $wordCount }} words
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Copy button -->
                                    <button class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2 copy-response" 
                                            data-content="{{ strip_tags($answerText) }}"
                                            title="Copy response">
                                        <iconify-icon icon="solar:copy-linear"></iconify-icon>
                                    </button>

                                    <!-- Response content with white background -->
                                    <div class="essay-content-wrapper bg-white rounded-6 p-12">
                                        <div class="essay-content">
                                            {!! $answerText !!}
                                        </div>
                                    </div>
                                    
                                   
                                </div>
                            @else
                                <div class="alert alert-warning py-2 mb-2">
                                    <small><i class="fas fa-exclamation-circle me-1"></i> No text answer was submitted for this question.</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#retryModal{{ $q->id }}">
                                    <iconify-icon icon="solar:letter-linear" class="me-1"></iconify-icon> Notify Student to Retake
                                </button>

                                <!-- Retry Notification Modal -->
                                <div class="modal fade" id="retryModal{{ $q->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.assessments.submission.notify-retry', $submission->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="question_id" value="{{ $q->id }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Notify Student — Retake Essay Question</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">To</label>
                                                        <input type="text" class="form-control" value="{{ $submission->user->email ?? '' }}" disabled>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Subject</label>
                                                        <input type="text" name="subject" class="form-control"
                                                            value="Action Required: Retake Your Essay Response — {{ $assessment->title }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Message</label>
                                                        <textarea name="message" class="form-control" rows="12">Hi {{ $submission->user->name ?? 'Student' }},

We recently completed a scheduled system upgrade to improve the assessment experience on the platform. During this process, we identified that your response to one of the essay questions in "{{ $assessment->title }}" was not saved correctly.

We understand this is frustrating, and we want to make sure your effort is properly reflected in your results — so we're giving you the opportunity to submit your answer again.

What you need to do:
1. Log back into your dashboard
2. Go to "{{ $assessment->title }}"
3. Answer the following question again: "{{ $q->question_text }}"
4. Submit your response

This will only take a few minutes, and your other answers and progress remain safe and unaffected. Please complete this as soon as possible so we can finalize your grading without delay.

If you have any questions or run into issues, just reply to this email and we'll help right away.

Thanks for your patience as we continue improving the platform.

Best regards,
The Team</textarea>
                                                    </div>
                                                    <small class="text-muted">You can edit the subject and message above before sending.</small>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-warning">
                                                        <iconify-icon icon="solar:letter-linear" class="me-1"></iconify-icon> Send Email
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Model Answer / Explanation (for grader reference) -->
                            @if(!empty($explanation))
                                <div class="mt-3 bg-info-focus border border-info-100 rounded-6 p-16">
                                    <p class="fw-semibold text-info-600 mb-1 small text-uppercase">
                                        <iconify-icon icon="solar:lightbulb-linear" class="me-1"></iconify-icon>
                                        Model Answer / Explanation
                                    </p>
                                    <p class="mb-0 text-sm">{!! $explanation !!}</p>
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

<!-- Reject Enrollment Modal -->
<div class="modal fade" id="rejectEnrollmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.enrollments.reject', $submission->enrollment) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title">Reject Course Enrollment</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove <strong>{{ $submission->user->name }}</strong> from <strong>{{ $assessment->course->title }}</strong>?</p>
                    <p class="text-sm text-muted">This will delete their enrollment record and send them an email notification.</p>
                    <div class="mb-3 mt-3">
                        <label class="form-label fw-semibold">Reason for Rejection</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="e.g., Not assigned to this scholarship course..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                </div>
            </form>
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

    .essay-section-label {
        color: #6b7280;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .essay-prompt {
        background: #f8fafc;
        border-left: 3px solid #cbd5e1;
        border-radius: 0.375rem;
        padding: 0.875rem 1rem;
    }

    /* Enhanced Essay Answer Styles with White Background */
    .essay-answer {
        background: #ffffff;
        border: 2px solid #2563eb;
        border-left: 6px solid #2563eb;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
        padding: 1.25rem;
        position: relative;
        transition: all 0.2s ease;
    }

    .essay-answer::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, transparent 50%, rgba(37, 99, 235, 0.05) 50%);
        border-radius: 0 0 0 60px;
        pointer-events: none;
    }

    .essay-answer:hover {
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.18);
        transform: translateY(-1px);
    }

    .essay-answer-icon {
        align-items: center;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        border-radius: 50%;
        color: white;
        display: inline-flex;
        font-size: 1.1rem;
        height: 2.25rem;
        justify-content: center;
        width: 2.25rem;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
    }

    /* Response content wrapper with white background */
    .essay-content-wrapper {
        background: #ffffff;
        border-radius: 0.5rem;
        padding: 0.5rem;
        margin: 0 -0.25rem;
    }

    .essay-content {
        background: #ffffff;
        color: #1f2937;
        font-size: 0.95rem;
        line-height: 1.8;
        padding: 0.5rem;
    }

    .essay-content > :last-child { 
        margin-bottom: 0; 
    }
    
    .essay-content p { 
        margin-bottom: 1rem; 
    }
    
    .essay-content ul, 
    .essay-content ol { 
        margin-left: 1.5rem; 
        margin-bottom: 1rem; 
    }
    
    .essay-content h1, 
    .essay-content h2, 
    .essay-content h3 { 
        margin-top: 1.5rem; 
        margin-bottom: 0.5rem; 
        font-weight: 600; 
    }

    .essay-content blockquote {
        border-left: 3px solid #60a5fa;
        color: #4b5563;
        font-style: italic;
        margin: 1rem 0;
        padding: 0.5rem 1rem;
        background: #f8fafc;
        border-radius: 0 0.375rem 0.375rem 0;
    }

    .essay-content pre {
        background: #f1f5f9;
        border-radius: 0.375rem;
        padding: 1rem;
        overflow-x: auto;
        font-size: 0.85rem;
    }

    .essay-content code {
        background: #f1f5f9;
        border-radius: 0.25rem;
        padding: 0.1rem 0.3rem;
        font-size: 0.85rem;
    }

    .essay-metadata {
        border-color: #e5e7eb !important;
        font-size: 0.8rem;
    }

    /* Copy button */
    .copy-response {
        z-index: 5;
        opacity: 0.6;
        transition: opacity 0.2s ease;
    }

    .copy-response:hover {
        opacity: 1;
        background: #f0f7ff;
        border-color: #2563eb;
        color: #2563eb;
    }

    .copy-response.copied {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }

    .copy-response.copied iconify-icon {
        transform: scale(1.1);
    }

    /* Badge styles */
    .badge.bg-success-100 {
        background-color: #d1fae5 !important;
        color: #065f46 !important;
    }

    .badge.bg-warning-100 {
        background-color: #fef3c7 !important;
        color: #92400e !important;
    }

    .badge.bg-danger-100 {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
    }

    .badge.bg-primary-50 {
        background-color: #eff6ff !important;
        color: #1e40af !important;
    }

    /* Loading animation for copy */
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    .copy-loading::after {
        content: '...';
        animation: pulse-dot 1.5s ease-in-out infinite;
    }

    /* Smooth transitions */
    .essay-answer,
    .copy-response,
    .badge {
        transition: all 0.2s ease-in-out;
    }

    /* Hover effects for response */
    .essay-answer:hover .essay-content-wrapper {
        background: #fafcff;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Essay score calculation
        const essayInputs = document.querySelectorAll('.essay-score-input');
        const finalScoreInput = document.getElementById('final_score_input');
        
        // Get values from PHP
        let autoScore = parseFloat("{{ $autoScore ?? 0 }}");
        const maxTotalMarks = parseFloat("{{ $assessment->total_marks ?? 100 }}");

        if (isNaN(autoScore)) autoScore = 0;

        function calculateTotal() {
            let manualTotal = 0;
            
            // Sum all manual essay inputs
            essayInputs.forEach(input => {
                let val = parseFloat(input.value);
                if (isNaN(val)) val = 0;
                
                // Optional: Enforce max points per question in JS too
                const maxPts = parseFloat(input.max);
                if (val > maxPts) {
                    val = maxPts;
                    input.value = maxPts; // Correct the input visually
                }
                
                manualTotal += val;
            });
            
            let calculatedTotal = autoScore + manualTotal;

            // Ensure total doesn't exceed max marks
            if (calculatedTotal > maxTotalMarks) {
                calculatedTotal = maxTotalMarks;
            }

            // Update the final score input
            finalScoreInput.value = calculatedTotal.toFixed(2);
        }

        // Attach listeners
        essayInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
            input.addEventListener('change', calculateTotal);
        });
        
        // Initial calculation
        calculateTotal();

        // Copy to clipboard functionality
        const copyButtons = document.querySelectorAll('.copy-response');
        
        copyButtons.forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                
                const content = this.getAttribute('data-content');
                const originalIcon = this.innerHTML;
                
                try {
                    // Try using the modern clipboard API
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        await navigator.clipboard.writeText(content);
                    } else {
                        // Fallback for older browsers
                        const textarea = document.createElement('textarea');
                        textarea.value = content;
                        textarea.style.position = 'fixed';
                        textarea.style.opacity = '0';
                        document.body.appendChild(textarea);
                        textarea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textarea);
                    }
                    
                    // Visual feedback
                    this.classList.add('copied');
                    this.innerHTML = '<iconify-icon icon="solar:check-circle-linear"></iconify-icon>';
                    
                    // Reset after 2 seconds
                    setTimeout(() => {
                        this.classList.remove('copied');
                        this.innerHTML = originalIcon;
                    }, 2000);
                    
                } catch (err) {
                    console.error('Failed to copy:', err);
                    
                    // Show error feedback
                    this.classList.add('btn-danger');
                    this.innerHTML = '<iconify-icon icon="solar:close-circle-linear"></iconify-icon>';
                    
                    setTimeout(() => {
                        this.classList.remove('btn-danger');
                        this.innerHTML = originalIcon;
                    }, 2000);
                }
            });
        });

        // Auto-resize textareas (if any)
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });

        // Smooth scroll to grading section when clicking grade button
        const gradeButton = document.querySelector('[href="#grade"]');
        if (gradeButton) {
            gradeButton.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('#grade').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        }

        // Tooltip initialization
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        console.log('Essay response enhancements loaded successfully!');
    });
</script>
@endpush