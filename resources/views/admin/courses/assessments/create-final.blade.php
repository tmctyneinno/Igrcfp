 @extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create Final Exam</h6>
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
            <li class="fw-medium">Create Final Exam</li>
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

    <form action="{{ route('admin.assessments.store') }}" method="POST" enctype="multipart/form-data" id="finalExamForm">
        @csrf
        <input type="hidden" name="assessment_level" value="final_exam">
        <input type="hidden" name="type" value="exam">
        <input type="hidden" name="is_timed" value="1">
        <input type="hidden" name="requires_identity_verification" value="1">

        <div class="row gy-4">
            <!-- Left Column - Basic Info & Questions -->
            <div class="col-lg-8">
                <!-- Basic Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Exam Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title') }}" placeholder="e.g., Final Examination - Certified GRC Professional" required>
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
                                          rows="3" placeholder="Describe the final exam">{{ old('description') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questions Card -->
                <div class="card mt-24">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Exam Questions</h6>
                            <p class="text-sm text-secondary-light mt-1">Add at least 50 questions for the final exam</p>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addQuestion()">
                            <iconify-icon icon="ic:baseline-plus" class="icon me-1"></iconify-icon>
                            Add Question
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="questions-container">
                            <!-- Questions will be added here dynamically -->
                        </div>
                        <div class="text-center py-5 bg-light rounded-8" id="no-questions-message" style="display: block;">
                            <iconify-icon icon="solar:document-text-outline" class="icon-4x text-muted mb-3"></iconify-icon>
                            <h6 class="text-muted mb-2">No questions added yet</h6>
                            <p class="text-muted mb-3">Click the button below to add your first question.</p>
                            <button type="button" class="btn btn-primary" onclick="addQuestion()">
                                <iconify-icon icon="ic:baseline-plus" class="icon me-1"></iconify-icon>
                                Add First Question
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Settings -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Exam Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Marks</label>
                            <input type="number" name="total_marks" class="form-control" value="{{ old('total_marks', 200) }}" min="1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Passing Score (%)</label>
                            <input type="number" name="passing_score" class="form-control" value="{{ old('passing_score', 70) }}" min="1" max="100">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Weight (% of final grade)</label>
                            <input type="number" name="weight" class="form-control" value="{{ old('weight', 50) }}" min="1" max="100">
                        </div>
                    </div>
                </div>

                <!-- Timing Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Timing (Required)</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                            <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" 
                                   value="{{ old('duration', 120) }}" min="30" required>
                            @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <p class="text-sm text-muted mt-1">Recommended: 120-180 minutes</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Release Date</label>
                            <input type="date" name="release_date" class="form-control" value="{{ old('release_date') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" 
                                   value="{{ old('due_date') }}" required>
                            @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Due Time</label>
                            <input type="time" name="due_time" class="form-control" value="{{ old('due_time', '23:59') }}">
                        </div>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="card mt-24 border-warning">
                    <div class="card-header bg-warning text-white">
                        <h6 class="card-title mb-0">Security Requirements</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <iconify-icon icon="solar:shield-check-bold" class="icon-2x text-warning"></iconify-icon>
                            <div>
                                <p class="fw-bold mb-1">Identity Verification Required</p>
                                <p class="text-sm text-muted mb-0">Students must verify identity with live photo</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <iconify-icon icon="solar:clock-circle-bold" class="icon-2x text-warning"></iconify-icon>
                            <div>
                                <p class="fw-bold mb-1">Timed Exam</p>
                                <p class="text-sm text-muted mb-0">Exam will be strictly timed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Exam File</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload File (Optional)</label>
                            <input type="file" name="assessment_file" class="form-control" accept=".pdf,.doc,.docx">
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
                            <button type="submit" class="btn btn-warning px-5 text-white">Create Final Exam</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Question Template -->
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
                        <option value="essay">Essay</option>
                        <option value="case_study">Case Study</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Points</label>
                    <input type="number" name="questions[{idx}][points]" class="form-control question-points" value="2" min="1" required>
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
                <div class="col-12 mb-3 options-container" style="display: block;">
                    <label class="form-label fw-semibold">Answer Options</label>
                    <div class="options-list">
                        <!-- Options will be added here -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addOption(this)">
                        <iconify-icon icon="ic:baseline-plus"></iconify-icon>
                        Add Option
                    </button>
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

                <!-- Essay/Case Study Container -->
                <div class="col-12 mb-3 essay-container" style="display: none;">
                    <label class="form-label fw-semibold">Rubric/Guidelines</label>
                    <textarea name="questions[{idx}][correct_answer]" class="form-control" rows="4" placeholder="Describe grading criteria and expected answer elements"></textarea>
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
@endsection

@push('styles')
<style>
    .question-item {
        transition: all 0.3s ease;
    }
    .question-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .option-item {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .option-item:hover {
        background: #e9ecef;
    }
</style>
@endpush

@push('scripts')
<script>
let questionCount = 0;

function addQuestion() {
    const container = document.getElementById('questions-container');
    const noQuestionsMsg = document.getElementById('no-questions-message');
    const template = document.getElementById('question-template');
    
    if (noQuestionsMsg) {
        noQuestionsMsg.style.display = 'none';
    }
    
    let questionHtml = template.innerHTML.replace(/{idx}/g, questionCount);
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = questionHtml;
    const questionElement = tempDiv.firstElementChild;
    
    questionElement.dataset.questionIdx = questionCount;
    questionElement.querySelector('.question-number').textContent = container.children.length + 1;
    
    container.appendChild(questionElement);
    
    // Add default options for multiple choice
    const optionsContainer = questionElement.querySelector('.options-container');
    if (optionsContainer) {
        addOption(optionsContainer.querySelector('.btn'));
        addOption(optionsContainer.querySelector('.btn'));
        addOption(optionsContainer.querySelector('.btn'));
        addOption(optionsContainer.querySelector('.btn'));
    }
    
    questionCount++;
    updateQuestionNumbers();
}

function removeQuestion(button) {
    const questionItem = button.closest('.question-item');
    questionItem.remove();
    
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
    const essayContainer = questionItem.querySelector('.essay-container');
    
    optionsContainer.style.display = 'none';
    trueFalseContainer.style.display = 'none';
    essayContainer.style.display = 'none';
    
    switch(select.value) {
        case 'multiple_choice':
            optionsContainer.style.display = 'block';
            break;
        case 'true_false':
            trueFalseContainer.style.display = 'block';
            break;
        case 'essay':
        case 'case_study':
            essayContainer.style.display = 'block';
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
    
    let optionHtml = template.innerHTML
        .replace(/{idx}/g, idx)
        .replace('{option-value}', 'option' + optionCount);
    
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = optionHtml;
    const optionElement = tempDiv.firstElementChild;
    
    optionsList.appendChild(optionElement);
}

function removeOption(button) {
    const optionItem = button.closest('.option-item');
    optionItem.remove();
}

// Form validation
document.getElementById('finalExamForm').addEventListener('submit', function(e) {
    const questions = document.querySelectorAll('.question-item');
    
    if (questions.length === 0) {
        e.preventDefault();
        alert('Please add at least one question.');
        return false;
    }
    
    if (questions.length < 10) {
        if (!confirm('You have only ' + questions.length + ' questions. Final exams typically have 50+ questions. Continue anyway?')) {
            e.preventDefault();
            return false;
        }
    }
    
    return true;
});
</script>
@endpush