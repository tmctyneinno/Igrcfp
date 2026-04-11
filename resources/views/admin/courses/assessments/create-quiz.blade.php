{{-- resources/views/admin/courses/assessments/create-quiz.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create New Quiz</h6>
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
            <li class="fw-medium">Create Quiz</li>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.assessments.store') }}" method="POST" enctype="multipart/form-data" id="quizForm">
        @csrf
        <input type="hidden" name="assessment_level" value="quiz">
        <input type="hidden" name="type" value="quiz">

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
                                <label class="form-label">Quiz Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title') }}" placeholder="e.g., Module 1 Review Quiz" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Course <span class="text-danger">*</span></label>
                                <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
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
                                <select name="module_id" id="module_id" class="form-select @error('module_id') is-invalid @enderror">
                                    <option value="">-- First select a course --</option>
                                </select>
                                @error('module_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">Select a course first to see available modules</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3" placeholder="Brief description of the quiz">{{ old('description') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questions Card -->
                <div class="card mt-24">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Quiz Questions</h6>
                            <p class="text-sm text-secondary-light mt-1">Add 1-5 questions for your quiz</p>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addQuestion()">
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
                        <h6 class="card-title mb-0">Quiz Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                            <input type="number" name="duration"
                                   class="form-control @error('duration') is-invalid @enderror"
                                   value="{{ old('duration') }}"
                                   placeholder="e.g. 30"
                                   min="1" max="180" required>
                            <small class="text-muted">How long students have to complete the quiz (1–180 mins)</small>
                            @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Marks</label>
                            <input type="number" name="total_marks" class="form-control" value="{{ old('total_marks', 100) }}" min="1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Passing Score (%)</label>
                            <input type="number" name="passing_score" class="form-control" value="{{ old('passing_score', 70) }}" min="1" max="100">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Weight (% of final grade)</label>
                            <input type="number" name="weight" class="form-control" value="{{ old('weight', 10) }}" min="1" max="100">
                        </div>
                    </div>
                </div>

                <!-- File Upload Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Additional Resources</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload File (Optional)</label>
                            <input type="file" name="assessment_file" class="form-control" accept=".pdf,.doc,.docx">
                            <p class="text-sm mt-1 text-muted">Supporting materials for the quiz</p>
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
                            <button type="submit" class="btn btn-primary px-5" id="submitBtn">Create Quiz</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Question Template — UI identical to original -->
<template id="question-template">
    <div class="question-item card mb-4" data-question-idx="{idx}">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Question <span class="question-number">1</span></h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-question" onclick="removeQuestion(this)">
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
                        <option value="short_answer">Short Answer</option>
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

                <!-- Options Container (for multiple choice) — UI unchanged -->
                <div class="col-12 mb-3 options-container" style="display: block;">
                    <label class="form-label fw-semibold">Answer Options</label>
                    <div class="options-list">
                        <!-- Options will be added here -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addOption(this)">
                        Add Option
                    </button>
                </div>

                {{--
                    FIX 1: True/False radio values changed from lowercase "true"/"false"
                    to "True"/"False" so PHP receives the correctly-cased string directly.
                    No UI change — labels still say "True" and "False".
                --}}
                <div class="col-12 mb-3 true-false-container" style="display: none;">
                    <label class="form-label fw-semibold">Select Correct Answer</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="questions[{idx}][correct_answer]" value="True" id="true-{idx}">
                            <label class="form-check-label" for="true-{idx}">True</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="questions[{idx}][correct_answer]" value="False" id="false-{idx}">
                            <label class="form-check-label" for="false-{idx}">False</label>
                        </div>
                    </div>
                </div>

                <!-- Short Answer Container — unchanged -->
                <div class="col-12 mb-3 short-answer-container" style="display: none;">
                    <label class="form-label fw-semibold">Correct Answer</label>
                    <input type="text" name="questions[{idx}][correct_answer]" class="form-control" placeholder="Enter the correct answer">
                </div>
            </div>
        </div>
    </div>
</template>

{{--
    FIX 2: Option radio value changed from "{option-value}" (which produced "option1","option2"...)
    to "{option-index}" (which produces 0, 1, 2...).
    The controller reads this as a 0-based index and looks up the actual option text from the options array.
    UI is completely unchanged — same layout, same delete button.
--}}
<template id="option-template">
    <div class="option-item d-flex align-items-center gap-2 mb-2">
        <input type="text" class="form-control" name="questions[{idx}][options][]" placeholder="Enter option" required>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="questions[{idx}][correct_answer]" value="{option-index}">
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
    .loading-modules {
        text-align: center;
        padding: 10px;
        color: #6c757d;
    }
</style>
@endpush

@push('scripts')
<script>
let questionCount = 0;

// Dynamic module loading — unchanged
document.addEventListener('DOMContentLoaded', function() {
    const courseSelect = document.getElementById('course_id');
    const moduleSelect = document.getElementById('module_id');
    
    if (courseSelect && courseSelect.value) {
        loadModules(courseSelect.value);
    }
    
    if (courseSelect) {
        courseSelect.addEventListener('change', function() {
            const courseId = this.value;
            if (courseId) {
                loadModules(courseId);
            } else {
                moduleSelect.innerHTML = '<option value="">-- First select a course --</option>';
            }
        });
    }
});

function loadModules(courseId) {
    const moduleSelect = document.getElementById('module_id');
    moduleSelect.innerHTML = '<option value="">Loading modules...</option>';
    moduleSelect.disabled = true;
    
    fetch(`{{ route('admin.assessments.get-modules', '') }}/${courseId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(modules => {
            moduleSelect.innerHTML = '<option value="">-- Select Module (Optional) --</option>';
            if (modules && modules.length > 0) {
                modules.forEach(module => {
                    const option = document.createElement('option');
                    option.value = module.id;
                    option.textContent = `Module ${module.module_number}: ${module.title}`;
                    moduleSelect.appendChild(option);
                });
                moduleSelect.disabled = false;
            } else {
                moduleSelect.innerHTML = '<option value="">No modules available for this course</option>';
                moduleSelect.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error loading modules:', error);
            moduleSelect.innerHTML = '<option value="">Error loading modules. Please try again.</option>';
            moduleSelect.disabled = true;
        });
}

// addQuestion — unchanged
function addQuestion() {
    const container = document.getElementById('questions-container');
    const noQuestionsMsg = document.getElementById('no-questions-message');
    const template = document.getElementById('question-template');
    
    if (!template) { console.error('Question template not found!'); return; }
    if (noQuestionsMsg) noQuestionsMsg.style.display = 'none';
    
    let questionHtml = template.innerHTML;
    questionHtml = questionHtml.replace(/{idx}/g, questionCount);
    
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = questionHtml.trim();
    const questionElement = tempDiv.firstElementChild;
    
    if (!questionElement) { console.error('Failed to create question element'); return; }
    
    questionElement.dataset.questionIdx = questionCount;
    
    const numberSpan = questionElement.querySelector('.question-number');
    if (numberSpan) numberSpan.textContent = container.children.length + 1;
    
    container.appendChild(questionElement);
    
    const typeSelect = questionElement.querySelector('.question-type');
    if (typeSelect) handleQuestionTypeChange(typeSelect);
    
    const optionsContainer = questionElement.querySelector('.options-container');
    if (optionsContainer) {
        const addBtn = optionsContainer.querySelector('.btn');
        if (addBtn) {
            for (let i = 0; i < 4; i++) addOption(addBtn);
        }
    }
    
    questionCount++;
    updateQuestionNumbers();
}

// removeQuestion — unchanged
function removeQuestion(button) {
    const questionItem = button.closest('.question-item');
    if (questionItem) {
        questionItem.remove();
        if (document.querySelectorAll('.question-item').length === 0) {
            const noQuestionsMsg = document.getElementById('no-questions-message');
            if (noQuestionsMsg) noQuestionsMsg.style.display = 'block';
        }
        updateQuestionNumbers();
    }
}

// updateQuestionNumbers — unchanged
function updateQuestionNumbers() {
    document.querySelectorAll('.question-item').forEach((question, index) => {
        const numberSpan = question.querySelector('.question-number');
        if (numberSpan) numberSpan.textContent = index + 1;
    });
}

// handleQuestionTypeChange — unchanged
function handleQuestionTypeChange(select) {
    const questionItem = select.closest('.question-item');
    if (!questionItem) return;
    
    const optionsContainer    = questionItem.querySelector('.options-container');
    const trueFalseContainer  = questionItem.querySelector('.true-false-container');
    const shortAnswerContainer = questionItem.querySelector('.short-answer-container');
    
    if (optionsContainer) {
        optionsContainer.querySelectorAll('input[required]').forEach(i => i.removeAttribute('required'));
        optionsContainer.style.display = 'none';
    }
    if (trueFalseContainer) {
        trueFalseContainer.querySelectorAll('input[required]').forEach(i => i.removeAttribute('required'));
        trueFalseContainer.style.display = 'none';
    }
    if (shortAnswerContainer) {
        shortAnswerContainer.querySelectorAll('input[required]').forEach(i => i.removeAttribute('required'));
        shortAnswerContainer.style.display = 'none';
    }
    
    switch(select.value) {
        case 'multiple_choice':
            if (optionsContainer) {
                optionsContainer.style.display = 'block';
                optionsContainer.querySelectorAll('input[type="text"]').forEach(i => i.setAttribute('required', 'required'));
            }
            break;
        case 'true_false':
            if (trueFalseContainer) {
                trueFalseContainer.style.display = 'block';
                const radios = trueFalseContainer.querySelectorAll('input[type="radio"]');
                if (radios.length > 0) radios[0].setAttribute('required', 'required');
            }
            break;
        case 'short_answer':
            if (shortAnswerContainer) {
                shortAnswerContainer.style.display = 'block';
                const answerInput = shortAnswerContainer.querySelector('input[type="text"]');
                if (answerInput) answerInput.setAttribute('required', 'required');
            }
            break;
    }
}

// addOption — FIX 2: uses 0-based index as radio value instead of "option1","option2"...
function addOption(button) {
    const optionsContainer = button.closest('.options-container');
    if (!optionsContainer) return;
    
    const optionsList  = optionsContainer.querySelector('.options-list');
    const template     = document.getElementById('option-template');
    const questionItem = button.closest('.question-item');
    
    if (!template || !questionItem || !optionsList) return;
    
    const idx         = questionItem.dataset.questionIdx;
    const optionIndex = optionsList.children.length; // 0-based: 0, 1, 2, 3 ...
    
    let optionHtml = template.innerHTML;
    optionHtml = optionHtml.replace(/{idx}/g, idx);
    optionHtml = optionHtml.replace(/{option-index}/g, optionIndex); // ← was {option-value} → "option1"
    
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = optionHtml.trim();
    const optionElement = tempDiv.firstElementChild;
    
    if (optionElement) {
        const textInput = optionElement.querySelector('input[type="text"]');
        if (textInput) textInput.setAttribute('required', 'required');
        optionsList.appendChild(optionElement);
    }
}

// removeOption — unchanged
function removeOption(button) {
    const optionItem = button.closest('.option-item');
    if (optionItem) optionItem.remove();
}

// Form submit — original validation kept + FIX 3 added at the end
document.getElementById('quizForm')?.addEventListener('submit', function(e) {
    const questions = document.querySelectorAll('.question-item');
    
    if (questions.length === 0) {
        e.preventDefault();
        alert('❌ Please add at least one question.');
        return false;
    }
    
    for (let i = 0; i < questions.length; i++) {
        const question    = questions[i];
        const questionText = question.querySelector('.question-text')?.value;
        const points      = question.querySelector('.question-points')?.value;
        const typeSelect  = question.querySelector('.question-type');
        
        if (!questionText || !questionText.trim()) {
            e.preventDefault();
            alert(`❌ Question ${i + 1}: Question text is required.`);
            return false;
        }
        if (!points || points <= 0) {
            e.preventDefault();
            alert(`❌ Question ${i + 1}: Valid points are required.`);
            return false;
        }
        if (!typeSelect) continue;
        
        const questionType = typeSelect.value;
        
        switch(questionType) {
            case 'multiple_choice':
                const options = question.querySelectorAll('.option-item input[type="text"]');
                if (options.length < 2) {
                    e.preventDefault();
                    alert(`❌ Question ${i + 1}: Multiple choice questions need at least 2 options.`);
                    return false;
                }
                let hasEmptyOption = false;
                options.forEach(opt => { if (!opt.value.trim()) hasEmptyOption = true; });
                if (hasEmptyOption) {
                    e.preventDefault();
                    alert(`❌ Question ${i + 1}: All options must have text.`);
                    return false;
                }
                // Validate a correct answer is selected
                const checkedMC = question.querySelector('.options-container input[type="radio"]:checked');
                if (!checkedMC) {
                    e.preventDefault();
                    alert(`❌ Question ${i + 1}: Please select the correct answer.`);
                    return false;
                }
                break;
                
            case 'true_false':
                const checkedTF = question.querySelector('.true-false-container input[type="radio"]:checked');
                if (!checkedTF) {
                    e.preventDefault();
                    alert(`❌ Question ${i + 1}: Please select True or False as the correct answer.`);
                    return false;
                }
                break;
                
            case 'short_answer':
                const shortAnswer = question.querySelector('.short-answer-container input[type="text"]')?.value;
                if (!shortAnswer || !shortAnswer.trim()) {
                    e.preventDefault();
                    alert(`❌ Question ${i + 1}: Correct answer is required for short answer questions.`);
                    return false;
                }
                break;
        }
    }

    // FIX 3: Disable inputs in hidden containers before submit so they send nothing to PHP.
    // This is the core fix for the NULL bug — hidden inputs were submitting empty/wrong values
    // that overwrote the actual correct_answer from the visible container.
    document.querySelectorAll('.question-item').forEach(question => {
        const type = question.querySelector('.question-type')?.value;
        if (!type) return;

        const containers = {
            multiple_choice: question.querySelector('.options-container'),
            true_false:      question.querySelector('.true-false-container'),
            short_answer:    question.querySelector('.short-answer-container'),
        };

        Object.entries(containers).forEach(([containerType, container]) => {
            if (!container) return;
            container.querySelectorAll('input').forEach(inp => {
                inp.disabled = (containerType !== type);
            });
        });
    });
    
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating Quiz...';
    }
    
    return true;
});
</script>
@endpush