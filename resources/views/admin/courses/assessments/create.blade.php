@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create {{ ucfirst(str_replace('_', ' ', $type)) }}</h6>
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
            <li class="fw-medium">Create {{ ucfirst(str_replace('_', ' ', $type)) }}</li>
        </ul>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.assessments.store') }}" method="POST" enctype="multipart/form-data" id="assessmentForm">
        @csrf
        <input type="hidden" name="assessment_level" value="{{ $type }}">

        <div class="row gy-4">
            <!-- Left Column - Basic Info -->
            <div class="col-lg-8">
                <!-- Basic Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Assessment Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title') }}" placeholder="Enter assessment title" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Course <span class="text-danger">*</span></label>
                                <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Module (Optional)</label>
                                <select name="module_id" class="form-select @error('module_id') is-invalid @enderror">
                                    <option value="">Select Module</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                                            Module {{ $module->module_number }}: {{ $module->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('module_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="4" placeholder="Describe the assessment">{{ old('description') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questions Card (for quiz/exam types) -->
                @if(in_array($type, ['quiz', 'module_assessment', 'final_exam']))
                <div class="card mt-24">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Questions</h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addQuestion()">
                            <iconify-icon icon="ic:baseline-plus" class="icon"></iconify-icon>
                            Add Question
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="questions-container">
                            <!-- Questions will be added here dynamically -->
                        </div>
                        <div class="text-muted text-center py-4" id="no-questions-message">
                            <iconify-icon icon="solar:document-text-outline" class="icon-3x mb-2"></iconify-icon>
                            <p>No questions added yet. Click "Add Question" to create your first question.</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Project Brief Card (for diploma) -->
                @if($type == 'diploma')
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Project Brief</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Project Description <span class="text-danger">*</span></label>
                            <textarea name="project_brief" class="form-control @error('project_brief') is-invalid @enderror" 
                                      rows="8" placeholder="Describe the project/case study requirements in detail">{{ old('project_brief') }}</textarea>
                            @error('project_brief') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deliverables</label>
                            <textarea name="deliverables" class="form-control" rows="4" 
                                      placeholder="List expected deliverables (one per line)">{{ old('deliverables') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Grading Rubric (JSON format)</label>
                            <textarea name="rubric" class="form-control" rows="6" 
                                      placeholder='[{"criteria": "Research Quality", "points": 25, "description": "..."}]'>{{ old('rubric') }}</textarea>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column - Settings -->
            <div class="col-lg-4">
                <!-- Status & Availability -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Status & Availability</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $type)) }}" readonly>
                            <input type="hidden" name="type" value="{{ 
                                $type == 'quiz' ? 'quiz' : 
                                ($type == 'diploma' ? 'project' : 'exam') 
                            }}">
                        </div>
                    </div>
                </div>

                <!-- Scoring Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Scoring</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Total Marks</label>
                            <input type="number" name="total_marks" class="form-control @error('total_marks') is-invalid @enderror" 
                                   value="{{ old('total_marks') }}" min="1">
                            @error('total_marks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Passing Score (%)</label>
                            <input type="number" name="passing_score" class="form-control @error('passing_score') is-invalid @enderror" 
                                   value="{{ old('passing_score', 60) }}" min="1" max="100">
                            @error('passing_score') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Weight (% of final grade)</label>
                            <input type="number" name="weight" class="form-control" value="{{ old('weight') }}" min="1" max="100">
                        </div>
                    </div>
                </div>

                <!-- Timing Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Timing</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_timed" id="isTimed" value="1" 
                                       {{ old('is_timed', in_array($type, ['module_assessment', 'final_exam']) ? 'checked' : '') }}>
                                <label class="form-check-label" for="isTimed">Timed Assessment</label>
                            </div>
                        </div>

                        <div class="mb-3" id="duration-field">
                            <label class="form-label">Duration (minutes)</label>
                            <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" 
                                   value="{{ old('duration', $type == 'quiz' ? 10 : ($type == 'final_exam' ? 120 : 60)) }}" min="1">
                            @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Release Date</label>
                            <input type="date" name="release_date" class="form-control" value="{{ old('release_date') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Due Time</label>
                            <input type="time" name="due_time" class="form-control" value="{{ old('due_time', '23:59') }}">
                        </div>
                    </div>
                </div>

                <!-- Security Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Security & Integrity</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="requires_identity_verification" id="requiresIdentity" value="1" 
                                       {{ old('requires_identity_verification', in_array($type, ['final_exam', 'diploma']) ? 'checked' : '') }}>
                                <label class="form-check-label" for="requiresIdentity">
                                    Requires Identity Verification
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="needs_manual_marking" id="needsManual" value="1" 
                                       {{ old('needs_manual_marking', $type == 'diploma' ? 'checked' : '') }}>
                                <label class="form-check-label" for="needsManual">
                                    Requires Manual Marking
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="allow_late_submissions" id="allowLate" value="1" 
                                       {{ old('allow_late_submissions') ? 'checked' : '' }}>
                                <label class="form-check-label" for="allowLate">
                                    Allow Late Submissions
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Assessment File</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload File</label>
                            <input type="file" name="assessment_file" class="form-control" accept=".pdf,.doc,.docx,.xlsx,.zip">
                            <p class="text-sm mt-1 text-muted">Max size: 50MB. Supported: PDF, DOCX, XLSX, ZIP</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('admin.assessments.all') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5">Create Assessment</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .question-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        position: relative;
        border: 1px solid #dee2e6;
    }
    .question-remove {
        position: absolute;
        top: 10px;
        right: 10px;
        color: #dc3545;
        cursor: pointer;
        background: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
    }
    .question-remove:hover {
        background: #dc3545;
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
let questionCount = 0;

function addQuestion() {
    const container = document.getElementById('questions-container');
    const noQuestionsMsg = document.getElementById('no-questions-message');
    
    if (noQuestionsMsg) {
        noQuestionsMsg.style.display = 'none';
    }
    
    const questionHtml = `
        <div class="question-item" id="question-${questionCount}">
            <div class="question-remove" onclick="removeQuestion(${questionCount})">
                <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
            </div>
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">Question Text</label>
                    <input type="text" name="questions[${questionCount}][text]" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Type</label>
                    <select name="questions[${questionCount}][type]" class="form-select" onchange="toggleOptions(${questionCount}, this.value)">
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="true_false">True/False</option>
                        <option value="short_answer">Short Answer</option>
                        <option value="essay">Essay</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Points</label>
                    <input type="number" name="questions[${questionCount}][points]" class="form-control" value="1" min="1" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Difficulty</label>
                    <select name="questions[${questionCount}][difficulty]" class="form-select">
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
                <div class="col-12 mb-3 options-container" id="options-${questionCount}">
                    <label class="form-label">Options (one per line)</label>
                    <textarea name="questions[${questionCount}][options]" class="form-control" rows="3">Option 1\nOption 2\nOption 3\nOption 4</textarea>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Correct Answer</label>
                    <input type="text" name="questions[${questionCount}][correct_answer]" class="form-control" required>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Explanation (optional)</label>
                    <textarea name="questions[${questionCount}][explanation]" class="form-control" rows="2"></textarea>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', questionHtml);
    questionCount++;
}

function removeQuestion(id) {
    const element = document.getElementById(`question-${id}`);
    if (element) {
        element.remove();
    }
    
    if (document.querySelectorAll('.question-item').length === 0) {
        document.getElementById('no-questions-message').style.display = 'block';
    }
}

function toggleOptions(questionId, type) {
    const optionsContainer = document.getElementById(`options-${questionId}`);
    if (type === 'multiple_choice') {
        optionsContainer.style.display = 'block';
    } else {
        optionsContainer.style.display = 'none';
    }
}

// Initialize with some questions for certain types
@if(in_array($type, ['quiz', 'module_assessment', 'final_exam']))
    @if($type == 'quiz')
        // Add 5 questions for quiz
        for (let i = 0; i < 5; i++) {
            addQuestion();
        }
    @elseif($type == 'module_assessment')
        // Add 20 questions for module assessment
        for (let i = 0; i < 20; i++) {
            addQuestion();
        }
    @elseif($type == 'final_exam')
        // Add 50 questions for final exam
        for (let i = 0; i < 50; i++) {
            addQuestion();
        }
    @endif
@endif
</script>
@endpush