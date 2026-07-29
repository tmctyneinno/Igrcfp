@extends('admin.layouts.app')

@section('content')
@php
    $assessment = $submission->assessment;
    $responses = $submission->question_responses ?? [];
    $uploadedFiles = collect($responses)
        ->filter(fn($response) => !empty($response['uploaded_file']['path']))
        ->map(fn($response) => $response['uploaded_file']);
@endphp

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Review Submission</h6>
        <a href="{{ route('admin.assessments.submissions', $assessment->id) }}" class="btn btn-outline-primary">
            Back to Submissions
        </a>
    </div>
 
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
    @endif

    <div class="row gy-4">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Student Details</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="w-45-px h-45-px bg-primary-100 rounded-circle d-flex align-items-center justify-content-center">
                            <span class="text-primary-600 fw-semibold">{{ strtoupper(substr($submission->user->name ?? 'U', 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="fw-semibold mb-0">{{ $submission->user->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-secondary-light mb-0">{{ $submission->user->email ?? '' }}</p>
                        </div>
                    </div>

                    <table class="table table-bordered mb-0">
                        <tr>
                            <th>Assessment</th>
                            <td>{{ $assessment->title }}</td>
                        </tr>
                        <tr>
                            <th>Course</th>
                            <td>{{ $assessment->course->title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Submitted</th>
                            <td>{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y H:i') : 'Not submitted' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $submission->status)) }}</td>
                        </tr>
                        <tr>
                            <th>Auto Score</th>
                            <td>
                                @if($submission->percentage !== null)
                                    {{ number_format($submission->percentage, 1) }}%
                                    @if($submission->score !== null)
                                        <span class="text-secondary-light">({{ $submission->score }}/{{ $assessment->total_marks }})</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-24" id="grade">
                <div class="card-header">
                    <h6 class="card-title mb-0">Grade Student</h6>
                </div>
                <div class="card-body">
                    @if($submission->status === 'graded')
                        <div class="alert alert-success">
                            This submission was graded by {{ $submission->grader->name ?? 'an admin' }}
                            on {{ $submission->graded_at?->format('M d, Y H:i') }}.
                        </div>
                    @endif

                    <form action="{{ route('admin.assessments.submission.grade', $submission->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Score <span class="text-danger">*</span></label>
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
                            <textarea name="feedback" class="form-control" rows="5" placeholder="Provide feedback to the student...">{{ old('feedback', $submission->feedback) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Submit Grade
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Part B Uploaded Document</h6>
                </div>
                <div class="card-body">
                    @if($uploadedFiles->isNotEmpty())
                        @foreach($uploadedFiles as $file)
                            <div class="d-flex align-items-center gap-3 p-16 bg-light rounded-8 mb-3">
                                <div class="w-50-px h-50-px bg-primary-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:file-text-outline" class="text-primary-600 icon-2x"></iconify-icon>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="fw-medium mb-1">{{ $file['name'] ?? 'Essay document' }}</p>
                                    <p class="text-sm text-secondary-light mb-0">
                                        {{ !empty($file['size']) ? round($file['size'] / 1024, 2) . ' KB' : 'File size unavailable' }}
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ $file['url'] ?? \Illuminate\Support\Facades\Storage::url($file['path']) }}" target="_blank" class="btn btn-outline-primary">
                                        <iconify-icon icon="solar:eye-outline" class="me-1"></iconify-icon>
                                        View
                                    </a>
                                    <a href="{{ $file['url'] ?? \Illuminate\Support\Facades\Storage::url($file['path']) }}" download class="btn btn-outline-success">
                                        <iconify-icon icon="solar:download-outline" class="me-1"></iconify-icon>
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @elseif($submission->submission_file_path)
                        <div class="d-flex align-items-center gap-3 p-16 bg-light rounded-8">
                            <div class="w-50-px h-50-px bg-primary-100 rounded-circle d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:file-text-outline" class="text-primary-600 icon-2x"></iconify-icon>
                            </div>
                            <div class="flex-grow-1">
                                <p class="fw-medium mb-1">{{ $submission->submission_file_name ?? 'Essay document' }}</p>
                                <p class="text-sm text-secondary-light mb-0">{{ $submission->formatted_file_size }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ $submission->submission_file_url }}" target="_blank" class="btn btn-outline-primary">
                                    <iconify-icon icon="solar:eye-outline" class="me-1"></iconify-icon>
                                    View
                                </a>
                                <a href="{{ $submission->submission_file_url }}" download class="btn btn-outline-success">
                                    <iconify-icon icon="solar:download-outline" class="me-1"></iconify-icon>
                                    Download
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">No Part B document was uploaded for this submission.</p>
                    @endif
                </div>
            </div>

            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Question Responses</h6>
                </div>
                <div class="card-body">
                    @forelse($assessment->questions as $question)
                        @php
                            $response = $responses[$question->id] ?? [];
                            $isManual = in_array($question->question_type, ['essay', 'case_study'], true);
                        @endphp
                        <div class="border rounded-8 p-16 mb-3">
                            <div class="d-flex justify-content-between gap-3 mb-2">
                                <p class="fw-semibold mb-0">{{ $question->question_text }}</p>
                                <span class="badge bg-{{ $isManual ? 'warning' : 'secondary' }}-600 text-white">
                                    {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                </span>
                            </div>
                            @if(!$isManual)
                                <p class="mb-1"><strong>Answer:</strong> {{ $response['answer'] ?? 'No answer' }}</p>
                                <p class="mb-0">
                                    <strong>Points:</strong>
                                    {{ $response['points_earned'] ?? 0 }}/{{ $response['points_possible'] ?? $question->points }}
                                </p>
                            @elseif(!empty($response['uploaded_file']))
                                <p class="mb-0">
                                    <strong>Uploaded:</strong>
                                    <a href="{{ $response['uploaded_file']['url'] ?? \Illuminate\Support\Facades\Storage::url($response['uploaded_file']['path']) }}" target="_blank">
                                        {{ $response['uploaded_file']['name'] ?? 'Essay document' }}
                                    </a>
                                </p>
                            @else
                                <p class="text-muted mb-0">No uploaded document for this essay question.</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">No questions found for this assessment.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
