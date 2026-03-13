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

    <!-- Assessment Type Tabs -->
    <div class="card mb-24">
        <div class="card-body p-16">
            <ul class="nav nav-pills" id="assessmentTypeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.assessments.create', ['type' => 'quiz']) }}" 
                       class="nav-link {{ $type == 'quiz' ? 'active' : '' }} d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:quiz-game"></iconify-icon>
                        Quiz
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.assessments.create', ['type' => 'module_assessment']) }}" 
                       class="nav-link {{ $type == 'module_assessment' ? 'active' : '' }} d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:clipboard-list"></iconify-icon>
                        Module Assessment
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.assessments.create', ['type' => 'final_exam']) }}" 
                       class="nav-link {{ $type == 'final_exam' ? 'active' : '' }} d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:document"></iconify-icon>
                        Final Exam
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.assessments.create', ['type' => 'diploma']) }}" 
                       class="nav-link {{ $type == 'diploma' ? 'active' : '' }} d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:medal-ribbon"></iconify-icon>
                        Diploma Project
                    </a>
                </li>
            </ul>
        </div>
    </div>

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
                        <div>
                            <h6 class="card-title mb-0">Questions</h6>
                            <p class="text-sm text-secondary-light mt-1">
                                @if($type == 'quiz')
                                    Add 5-10 questions for your quiz
                                @elseif($type == 'module_assessment')
                                    Add 20-30 questions for your module assessment
                                @elseif($type == 'final_exam')
                                    Add at least 50 questions for your final exam
                                @endif
                            </p>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addQuestion()">
                             Add Question
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="questions-container">
                            <!-- Questions will be added here dynamically -->
                        </div>
                        <div class="text-center py-5 bg-light rounded-8" id="no-questions-message">
                            <iconify-icon icon="solar:document-text-outline" class="icon-4x text-muted mb-3"></iconify-icon>
                            <h6 class="text-muted mb-2">No questions added yet</h6>
                            <p class="text-muted mb-3">Click the "Add Question" button to create your first question.</p>
                            <button type="button" class="btn btn-primary" onclick="addQuestion()">
                                <iconify-icon icon="ic:baseline-plus" class="icon me-1"></iconify-icon>
                                Add Your First Question
                            </button>
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
        <!-- Add this temporarily for debugging -->
        <script>
        document.getElementById('assessmentForm').addEventListener('submit', function(e) {
            console.log('Form submitted');
            
            // Check if any validation is preventing submission
            const questions = document.querySelectorAll('.question-item');
            console.log('Questions count:', questions.length);
            
            // Log form data
            const formData = new FormData(this);
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
        });
        </script>
    </form>
</div>

<!-- Question Template (Hidden) -->
<template id="question-template">
    <div class="question-item card mb-4" data-question-idx="{idx}">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Question <span class="question-number">1</span></h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-question" onclick="removeQuestion(this)">
                <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                Remove
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label fw-semibold">Question Text</label>
                    <textarea name="questions[{idx}][text]" class="form-control question-text" rows="2" required></textarea>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="questions[{idx}][type]" class="form-select question-type" onchange="handleQuestionTypeChange(this)">
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="true_false">True/False</option>
                        <option value="multiple_answer">Multiple Answer</option>
                        <option value="short_answer">Short Answer</option>
                        <option value="essay">Essay</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Points</label>
                    <input type="number" name="questions[{idx}][points]" class="form-control question-points" value="1" min="1" required>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Difficulty</label>
                    <select name="questions[{idx}][difficulty]" class="form-select">
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>

                <!-- Options Container (for multiple choice) -->
                <div class="col-12 mb-3 options-container" style="display: none;">
                    <label class="form-label fw-semibold">Answer Options</label>
                    <div class="options-list">
                        <!-- Options will be added here -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addOption(this)">
                        Add Option
                    </button>
                    <p class="text-sm text-muted mt-1">Add at least 2 options for multiple choice questions</p>
                </div>

                <!-- True/False Container -->
                <div class="col-12 mb-3 true-false-container" style="display: none;">
                    <label class="form-label fw-semibold">Select Correct Answer</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="questions[{idx}][correct_answer]" value="true" id="true-{idx}">
                            <label class="form-check-label" for="true-{idx}">True</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="questions[{idx}][correct_answer]" value="false" id="false-{idx}">
                            <label class="form-check-label" for="false-{idx}">False</label>
                        </div>
                    </div>
                </div>

                <!-- Multiple Answer Container -->
                <div class="col-12 mb-3 multiple-answer-container" style="display: none;">
                    <label class="form-label fw-semibold">Answer Options (Select all that apply)</label>
                    <div class="multiple-answer-options">
                        <!-- Options will be added here -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addMultipleAnswerOption(this)">
                        <iconify-icon icon="ic:baseline-plus"></iconify-icon>
                        Add Option
                    </button>
                </div>

                <!-- Short Answer/Essay Container -->
                <div class="col-12 mb-3 short-answer-container" style="display: none;">
                    <label class="form-label fw-semibold">Correct Answer (Keywords or expected answer)</label>
                    <input type="text" name="questions[{idx}][correct_answer]" class="form-control" placeholder="Enter the correct answer or keywords">
                </div>

                <!-- Explanation -->
                <div class="col-12 mb-3">
                    <label class="form-label fw-semibold">Explanation (optional)</label>
                    <textarea name="questions[{idx}][explanation]" class="form-control" rows="2" placeholder="Explain why this answer is correct"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Option Item Template -->
<template id="option-template">
    <div class="option-item d-flex align-items-center gap-2 mb-2">
        <input type="text" class="form-control" name="questions[{idx}][options][]" placeholder="Enter option" required>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="questions[{idx}][correct_answer]" value="{option-value}">
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOption(this)">
            <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
        </button>
    </div>
</template>

<!-- Multiple Answer Option Template -->
<template id="multiple-answer-template">
    <div class="multiple-answer-item d-flex align-items-center gap-2 mb-2">
        <input type="text" class="form-control" name="questions[{idx}][options][]" placeholder="Enter option" required>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="questions[{idx}][correct_answers][]" value="{option-value}">
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOption(this)">
            <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
        </button>
    </div>
</template>
@endsection

@push('styles')
<style>
    .question-item {
        transition: all 0.3s ease;
    }
    .question-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .option-item, .multiple-answer-item {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .option-item:hover, .multiple-answer-item:hover {
        background: #e9ecef;
    }
    .option-item .form-check-input:checked,
    .multiple-answer-item .form-check-input:checked {
        background-color: #0A1F44;
        border-color: #0A1F44;
    }
    .nav-pills .nav-link.active {
        background-color: #0A1F44;
        color: white;
    }
    .nav-pills .nav-link {
        color: #6c757d;
        padding: 10px 20px;
    }
    .nav-pills .nav-link:hover {
        background-color: #e9ecef;
    }
</style>
@endpush

@push('scripts')
<script>
let questionCount = 0;

// Initialize with questions based on assessment type
document.addEventListener('DOMContentLoaded', function() {
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
});

function addQuestion() {
    const container = document.getElementById('questions-container');
    const noQuestionsMsg = document.getElementById('no-questions-message');
    const template = document.getElementById('question-template');
    
    if (noQuestionsMsg) {
        noQuestionsMsg.style.display = 'none';
    }
    
    // Clone the template content
    const questionHtml = template.innerHTML.replace(/{idx}/g, questionCount);
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = questionHtml;
    const questionElement = tempDiv.firstElementChild;
    
    // Update question number
    questionElement.querySelector('.question-number').textContent = container.children.length + 1;
    
    // Add to container
    container.appendChild(questionElement);
    
    // Default to multiple choice
    const typeSelect = questionElement.querySelector('.question-type');
    handleQuestionTypeChange(typeSelect);
    
    questionCount++;
    updateQuestionNumbers();
}

function removeQuestion(button) {
    const questionItem = button.closest('.question-item');
    questionItem.remove();
    
    // Show no questions message if empty
    if (document.querySelectorAll('.question-item').length === 0) {
        document.getElementById('no-questions-message').style.display = 'block';
    }
    
    updateQuestionNumbers();
}

function updateQuestionNumbers() {
    const questions = document.querySelectorAll('.question-item');
    questions.forEach((question, index) => {
        question.querySelector('.question-number').textContent = index + 1;
    });
}

function handleQuestionTypeChange(select) {
    const questionItem = select.closest('.question-item');
    const optionsContainer = questionItem.querySelector('.options-container');
    const trueFalseContainer = questionItem.querySelector('.true-false-container');
    const multipleAnswerContainer = questionItem.querySelector('.multiple-answer-container');
    const shortAnswerContainer = questionItem.querySelector('.short-answer-container');
    const idx = questionItem.dataset.questionIdx;
    
    // Hide all containers
    optionsContainer.style.display = 'none';
    trueFalseContainer.style.display = 'none';
    multipleAnswerContainer.style.display = 'none';
    shortAnswerContainer.style.display = 'none';
    
    // Show appropriate container based on selected type
    switch(select.value) {
        case 'multiple_choice':
            optionsContainer.style.display = 'block';
            // Add default options if empty
            if (optionsContainer.querySelectorAll('.option-item').length === 0) {
                addOption(optionsContainer.querySelector('.btn'));
                addOption(optionsContainer.querySelector('.btn'));
            }
            break;
        case 'true_false':
            trueFalseContainer.style.display = 'block';
            break;
        case 'multiple_answer':
            multipleAnswerContainer.style.display = 'block';
            // Add default options if empty
            if (multipleAnswerContainer.querySelectorAll('.multiple-answer-item').length === 0) {
                addMultipleAnswerOption(multipleAnswerContainer.querySelector('.btn'));
                addMultipleAnswerOption(multipleAnswerContainer.querySelector('.btn'));
                addMultipleAnswerOption(multipleAnswerContainer.querySelector('.btn'));
            }
            break;
        case 'short_answer':
        case 'essay':
            shortAnswerContainer.style.display = 'block';
            break;
    }
}

function addOption(button) {
    const optionsContainer = button.closest('.options-container');
    const optionsList = optionsContainer.querySelector('.options-list');
    const template = document.getElementById('option-template');
    const questionItem = button.closest('.question-item');
    const idx = questionItem.dataset.questionIdx;
    const optionCount = optionsList.children.length + 1;
    
    const optionHtml = template.innerHTML
        .replace(/{idx}/g, idx)
        .replace('{option-value}', 'option' + optionCount);
    
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = optionHtml;
    const optionElement = tempDiv.firstElementChild;
    
    optionsList.appendChild(optionElement);
}

function addMultipleAnswerOption(button) {
    const container = button.closest('.multiple-answer-container');
    const optionsList = container.querySelector('.multiple-answer-options');
    const template = document.getElementById('multiple-answer-template');
    const questionItem = button.closest('.question-item');
    const idx = questionItem.dataset.questionIdx;
    const optionCount = optionsList.children.length + 1;
    
    const optionHtml = template.innerHTML
        .replace(/{idx}/g, idx)
        .replace('{option-value}', 'option' + optionCount);
    
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = optionHtml;
    const optionElement = tempDiv.firstElementChild;
    
    optionsList.appendChild(optionElement);
}

function removeOption(button) {
    const optionItem = button.closest('.option-item, .multiple-answer-item');
    optionItem.remove();
}

// Form validation before submit
document.getElementById('assessmentForm').addEventListener('submit', function(e) {
    const questions = document.querySelectorAll('.question-item');
    
    @if(in_array($type, ['quiz', 'module_assessment', 'final_exam']))
        @if($type == 'quiz')
            if (questions.length < 5) {
                e.preventDefault();
                alert('Please add at least 5 questions for your quiz.');
                return false;
            }
        @elseif($type == 'module_assessment')
            if (questions.length < 20) {
                e.preventDefault();
                alert('Please add at least 20 questions for your module assessment.');
                return false;
            }
        @elseif($type == 'final_exam')
            if (questions.length < 50) {
                e.preventDefault();
                alert('Please add at least 50 questions for your final exam.');
                return false;
            }
        @endif
        
        // Validate each question has required fields
        for (let i = 0; i < questions.length; i++) {
            const question = questions[i];
            const questionText = question.querySelector('.question-text').value;
            const points = question.querySelector('.question-points').value;
            
            if (!questionText || !points) {
                e.preventDefault();
                alert(`Question ${i + 1} is incomplete. Please fill in all required fields.`);
                return false;
            }
            
            // Validate options for multiple choice
            const typeSelect = question.querySelector('.question-type');
            if (typeSelect.value === 'multiple_choice') {
                const options = question.querySelectorAll('.option-item input[type="text"]');
                const correctAnswer = question.querySelector('input[name^="questions"][name$="[correct_answer]"]:checked');
                
                if (options.length < 2) {
                    e.preventDefault();
                    alert(`Question ${i + 1} needs at least 2 options.`);
                    return false;
                }
                
                if (!correctAnswer) {
                    e.preventDefault();
                    alert(`Please select the correct answer for Question ${i + 1}.`);
                    return false;
                }
            }
        }
    @endif
});
</script>
@endpush