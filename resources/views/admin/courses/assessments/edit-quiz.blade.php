{{-- resources/views/admin/courses/assessments/edit-quiz.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Edit Quiz</h6>
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
            <li class="fw-medium">
                <a href="{{ route('admin.assessments.show', $assessment->id) }}" class="hover-text-primary">
                    {{ Str::limit($assessment->title, 30) }}
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Edit Quiz</li>
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

    {{-- ============================================================
         MAIN EDIT FORM — no other <form> tag inside this one
    ============================================================ --}}
    <form action="{{ route('admin.assessments.update', $assessment->id) }}"
          method="POST"
          enctype="multipart/form-data"
          id="quizForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="assessment_level" value="quiz">
        <input type="hidden" name="type" value="quiz">

        <div class="row gy-4">

            {{-- ── Left Column ── --}}
            <div class="col-lg-8">

                {{-- Basic Information --}}
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-12">
                                <label class="form-label">Quiz Title <span class="text-danger">*</span></label>
                                <input type="text" name="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $assessment->title) }}"
                                       placeholder="e.g., Module 1 Review Quiz" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Course <span class="text-danger">*</span></label>
                                <select name="course_id" id="course_id"
                                        class="form-select @error('course_id') is-invalid @enderror" required>
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}"
                                            {{ old('course_id', $assessment->course_id) == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Module (Optional)</label>
                                <select name="module_id" id="module_id"
                                        class="form-select @error('module_id') is-invalid @enderror">
                                    <option value="">-- First select a course --</option>
                                </select>
                                @error('module_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">Select a course first to see available modules</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Brief description of the quiz">{{ old('description', $assessment->description) }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Questions --}}
                <div class="card mt-24">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Quiz Questions</h6>
                            <p class="text-sm text-secondary-light mt-1">Edit or add questions for your quiz</p>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addQuestion()">
                            Add Question
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="questions-container"></div>
                        <div class="text-center py-5 bg-light rounded-8"
                             id="no-questions-message" style="display:none;">
                            <iconify-icon icon="solar:document-text-outline"
                                          class="icon-4x text-muted mb-3"></iconify-icon>
                            <h6 class="text-muted mb-2">No questions added yet</h6>
                            <p class="text-muted mb-3">Click the button below to add your first question.</p>
                            <button type="button" class="btn btn-primary" onclick="addQuestion()">
                                Add First Question
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card mt-24">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Part B: Essay Questions</h6>
                            <p class="text-sm text-secondary-light mt-1">Edit or add essay prompts with examiner guidance.</p>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addEssay()">
                            Add Essay
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="essay-questions-container"></div>
                        <div class="text-center py-5 bg-light rounded-8"
                             id="no-essay-questions-message" style="display:none;">
                            <iconify-icon icon="solar:document-text-outline"
                                          class="icon-4x text-muted mb-3"></iconify-icon>
                            <h6 class="text-muted mb-2">No essay questions added yet</h6>
                            <p class="text-muted mb-3">Click the button below to add your first essay question.</p>
                            <button type="button" class="btn btn-primary" onclick="addEssay()">
                                Add First Essay
                            </button>
                        </div>
                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- ── Right Column ── --}}
            <div class="col-lg-4">

                {{-- Settings --}}
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Quiz Settings</h6>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                            <input type="number" name="duration"
                                   class="form-control @error('duration') is-invalid @enderror"
                                   value="{{ old('duration', $assessment->duration) }}"
                                   placeholder="e.g. 30" min="1" max="180" required>
                            <small class="text-muted">How long students have to complete the quiz (1–180 mins)</small>
                            @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active"   {{ old('status', $assessment->status) == 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="draft"    {{ old('status', $assessment->status) == 'draft'    ? 'selected' : '' }}>Draft</option>
                                <option value="archived" {{ old('status', $assessment->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Marks</label>
                            <input type="number" name="total_marks" class="form-control"
                                   value="{{ old('total_marks', $assessment->total_marks ?? 100) }}" min="1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Passing Score (%)</label>
                            <input type="number" name="passing_score" class="form-control"
                                   value="{{ old('passing_score', $assessment->passing_score ?? 70) }}"
                                   min="1" max="100">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Weight (% of final grade)</label>
                            <input type="number" name="weight" class="form-control"
                                   value="{{ old('weight', $assessment->weight ?? 10) }}"
                                   min="1" max="100">
                        </div>

                    </div>
                </div>

                {{-- File Upload --}}
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Additional Resources</h6>
                    </div>
                    <div class="card-body">
                        @if($assessment->file_path)
                            <div class="mb-3 p-3 bg-light rounded-8 d-flex align-items-center gap-3">
                                <iconify-icon icon="solar:file-text-outline"
                                              class="text-primary-600 text-xl"></iconify-icon>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="fw-semibold text-sm mb-0 text-truncate">{{ $assessment->file_name }}</p>
                                    <p class="text-muted text-xs mb-0">{{ $assessment->formatted_file_size }}</p>
                                </div>
                                <a href="{{ Storage::url($assessment->file_path) }}" target="_blank"
                                   class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox"
                                       name="remove_file" id="removeFile" value="1">
                                <label class="form-check-label text-danger" for="removeFile">
                                    Remove current file
                                </label>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">
                                {{ $assessment->file_path ? 'Replace File (Optional)' : 'Upload File (Optional)' }}
                            </label>
                            <input type="file" name="assessment_file" class="form-control"
                                   accept=".pdf,.doc,.docx">
                            <p class="text-sm mt-1 text-muted">Supporting materials for the quiz</p>
                        </div>
                    </div>
                </div>

                {{-- Danger Zone — button only, the actual <form> is OUTSIDE the main form --}}
                <div class="card mt-24 border border-danger-subtle">
                    <div class="card-header">
                        <h6 class="card-title mb-0 text-danger-600">Danger Zone</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-sm text-secondary-light mb-3">
                            Deleting this quiz will permanently remove all questions and student submissions.
                        </p>
                        <button type="button"
                                class="btn btn-outline-danger w-100"
                                onclick="confirmDelete()">
                            <iconify-icon icon="fluent:delete-24-regular" class="me-1"></iconify-icon>
                            Delete Quiz
                        </button>
                    </div>
                </div>

            </div>{{-- end col-lg-4 --}}

            {{-- Submit Buttons --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('admin.assessments.show', $assessment->id) }}"
                               class="btn btn-outline-secondary px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5" id="submitBtn">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end row --}}
    </form>
    {{-- ============================================================
         END MAIN EDIT FORM
    ============================================================ --}}

    {{-- DELETE FORM — lives outside the edit form so _method=DELETE
         never bleeds into the update submission --}}
    <form id="deleteForm"
          action="{{ route('admin.assessments.destroy', $assessment->id) }}"
          method="POST"
          style="display:none;">
        @csrf
        @method('DELETE')
    </form>

</div>
@endsection

{{-- Question Template --}}
<template id="question-template">
    <div class="question-item card mb-4" data-question-idx="{idx}">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Question <span class="question-number">1</span></h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-question"
                    onclick="removeQuestion(this)">
                Remove
            </button>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-12 mb-3">
                    <label class="form-label fw-semibold">Question Text</label>
                    <textarea name="questions[{idx}][text]"
                              class="form-control question-text rich-editor" rows="2" required></textarea>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="questions[{idx}][type]"
                            class="form-select question-type"
                            onchange="handleQuestionTypeChange(this)">
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="true_false">True/False</option>
                        <option value="short_answer">Short Answer</option>
                        <option value="essay">Essay</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Points</label>
                    <input type="number" name="questions[{idx}][points]"
                           class="form-control question-points" value="1" min="1" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Difficulty</label>
                    <select name="questions[{idx}][difficulty]" class="form-select">
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>

                <div class="col-12 mb-3 options-container" style="display:block;">
                    <label class="form-label fw-semibold">Answer Options</label>
                    <div class="options-list"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                            onclick="addOption(this)">
                        Add Option
                    </button>
                </div>

                <div class="col-12 mb-3 true-false-container" style="display:none;">
                    <label class="form-label fw-semibold">Select Correct Answer</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio"
                                   name="questions[{idx}][correct_answer]"
                                   value="True" id="true-{idx}">
                            <label class="form-check-label" for="true-{idx}">True</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio"
                                   name="questions[{idx}][correct_answer]"
                                   value="False" id="false-{idx}">
                            <label class="form-check-label" for="false-{idx}">False</label>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3 short-answer-container" style="display:none;">
                    <label class="form-label fw-semibold">Correct Answer</label>
                    <input type="text" name="questions[{idx}][correct_answer]"
                           class="form-control" placeholder="Enter the correct answer">
                </div>

                <div class="col-12 mb-3 essay-container" style="display:none;">
                    <label class="form-label fw-semibold">Examiner's Marking Guidance</label>
                    <textarea name="questions[{idx}][explanation]"
                              class="form-control rich-editor"
                              rows="3"
                              placeholder="Enter guidance for the examiner or rubric notes."></textarea>
                </div>

            </div>
        </div>
    </div>
</template>

<template id="option-template">
    <div class="option-item d-flex align-items-center gap-2 mb-2">
        <input type="text" class="form-control"
               name="questions[{idx}][options][]" placeholder="Enter option" required>
        <div class="form-check">
            <input class="form-check-input" type="radio"
                   name="questions[{idx}][correct_answer]" value="{option-index}">
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger"
                onclick="removeOption(this)">
            <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
        </button>
    </div>
</template>

<template id="essay-question-template">
    <div class="question-item card mb-4" data-question-idx="{idx}">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Essay Question <span class="essay-question-number">1</span></h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-question"
                    onclick="removeEssay(this)">
                Remove
            </button>
        </div>
        <div class="card-body">
            <input type="hidden" name="questions[{idx}][type]" value="essay" class="question-type">
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label fw-semibold">Question Text</label>
                    <textarea name="questions[{idx}][text]"
                              class="form-control question-text rich-editor"
                              rows="3" required></textarea>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Points</label>
                    <input type="number" name="questions[{idx}][points]"
                           class="form-control question-points" value="5" min="1" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label fw-semibold">Type</label>
                    <input type="text" class="form-control" value="Essay" readonly>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label fw-semibold">Examiner's Marking Guidance</label>
                    <textarea name="questions[{idx}][explanation]"
                              class="form-control rich-editor"
                              rows="3"
                              placeholder="Enter guidance for the examiner or rubric notes."></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

{{-- Pass existing questions to JS --}}
<script id="existing-questions-data" type="application/json">
    {!! json_encode($questionsData, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}
</script>
<script id="existing-essay-questions-data" type="application/json">
    {!! json_encode($essayQuestionsData, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}
</script>

{{-- Pass existing module_id for pre-selection --}}
<script id="existing-module-id" type="application/json">
    {{ json_encode($assessment->module_id) }}
</script>

@push('styles')
<style>
    .question-item { transition: all 0.3s ease; }
    .question-item:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .option-item {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .option-item:hover { background: #e9ecef; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
let questionCount = 0;
const questionEditors = new Map();

function initializeQuestionEditors(questionElement) {
    questionElement?.querySelectorAll('textarea.rich-editor:not([data-ck-initialized])').forEach(function (textarea) {
        textarea.dataset.ckInitialized = 'pending';

        ClassicEditor.create(textarea, {
            toolbar: {
                items: [
                    'bold', 'italic', 'underline', '|',
                    'bulletedList', 'numberedList', '|',
                    'link', 'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            }
        })
            .then(function (editor) {
                textarea.dataset.ckInitialized = 'true';
                textarea.removeAttribute('required');
                questionEditors.set(textarea, editor);
            })
            .catch(function (error) {
                delete textarea.dataset.ckInitialized;
                console.error('CKEditor could not be initialized:', error);
            });
    });
}

function syncQuestionEditors() {
    questionEditors.forEach(function (editor, textarea) {
        textarea.value = editor.getData();
    });
}

function destroyQuestionEditors(questionElement) {
    questionElement?.querySelectorAll('textarea.rich-editor').forEach(function (textarea) {
        const editor = questionEditors.get(textarea);
        if (!editor) return;

        questionEditors.delete(textarea);
        editor.destroy().catch(function (error) {
            console.error('CKEditor could not be removed:', error);
        });
    });
}

function hasQuestionContent(value) {
    const element = document.createElement('div');
    element.innerHTML = value || '';
    return element.textContent.replace(/\u00a0/g, ' ').trim().length > 0;
}

// ── Confirm and submit delete ─────────────────────────────────────────────────
function confirmDelete() {
    if (confirm('Delete this quiz and ALL its data? This cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}

// ── Module loader ─────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const courseSelect     = document.getElementById('course_id');
    const existingModuleId = JSON.parse(
        document.getElementById('existing-module-id').textContent
    );

    if (courseSelect && courseSelect.value) {
        loadModules(courseSelect.value, existingModuleId);
    }

    courseSelect?.addEventListener('change', function () {
        if (this.value) {
            loadModules(this.value, null);
        } else {
            document.getElementById('module_id').innerHTML =
                '<option value="">-- First select a course --</option>';
        }
    });

    // Load existing questions
    let existingQuestions = [];
    let existingEssayQuestions = [];
    try {
        const raw = document.getElementById('existing-questions-data').textContent.trim();
        existingQuestions = JSON.parse(raw);
    } catch(err) {
        console.error('Failed to parse questions JSON:', err);
    }

    try {
        const rawEssay = document.getElementById('existing-essay-questions-data').textContent.trim();
        existingEssayQuestions = JSON.parse(rawEssay);
    } catch(err) {
        console.error('Failed to parse essay questions JSON:', err);
    }

    if (existingQuestions.length > 0) {
        document.getElementById('no-questions-message').style.display = 'none';
        existingQuestions.forEach(function(q) { addQuestion(q); });
    } else {
        document.getElementById('no-questions-message').style.display = 'block';
    }

    if (existingEssayQuestions.length > 0) {
        document.getElementById('no-essay-questions-message').style.display = 'none';
        existingEssayQuestions.forEach(function(q) { addEssay(q); });
    } else {
        document.getElementById('no-essay-questions-message').style.display = 'block';
    }
});

function loadModules(courseId, preSelectId) {
    const moduleSelect = document.getElementById('module_id');
    moduleSelect.innerHTML = '<option value="">Loading modules...</option>';
    moduleSelect.disabled  = true;

    fetch(`{{ route('admin.assessments.get-modules', '') }}/${courseId}`)
        .then(r => { if (!r.ok) throw new Error('Network error'); return r.json(); })
        .then(modules => {
            moduleSelect.innerHTML =
                '<option value="">-- Select Module (Optional) --</option>';
            if (modules && modules.length > 0) {
                modules.forEach(module => {
                    const opt       = document.createElement('option');
                    opt.value       = module.id;
                    opt.textContent = `Module ${module.module_number}: ${module.title}`;
                    if (preSelectId && module.id == preSelectId) opt.selected = true;
                    moduleSelect.appendChild(opt);
                });
                moduleSelect.disabled = false;
            } else {
                moduleSelect.innerHTML =
                    '<option value="">No modules available for this course</option>';
            }
        })
        .catch(() => {
            moduleSelect.innerHTML =
                '<option value="">Error loading modules. Please try again.</option>';
        });
}

// ── Add question ──────────────────────────────────────────────────────────────
function addQuestion(existingData) {
    const container      = document.getElementById('questions-container');
    const noQuestionsMsg = document.getElementById('no-questions-message');
    const template       = document.getElementById('question-template');

    if (!template) { console.error('Question template not found!'); return; }
    if (noQuestionsMsg) noQuestionsMsg.style.display = 'none';

    let html           = template.innerHTML.replace(/{idx}/g, questionCount);
    const tempDiv      = document.createElement('div');
    tempDiv.innerHTML  = html.trim();
    const questionEl   = tempDiv.firstElementChild;
    if (!questionEl) return;

    questionEl.dataset.questionIdx = questionCount;

    const numberSpan = questionEl.querySelector('.question-number');
    if (numberSpan) numberSpan.textContent = container.children.length + 1;

    container.appendChild(questionEl);

    if (existingData) {
        const textArea = questionEl.querySelector('.question-text');
        if (textArea) textArea.value = existingData.text || '';

        const pointsInput = questionEl.querySelector('.question-points');
        if (pointsInput) pointsInput.value = parseInt(existingData.points) || 1; // ← fix here

        const diffSelect = questionEl.querySelector(
            `select[name="questions[${questionCount}][difficulty]"]`
        );
        if (diffSelect) diffSelect.value = existingData.difficulty || 'medium';

        const typeSelect = questionEl.querySelector('.question-type');
        if (typeSelect) {
            typeSelect.value = existingData.type || 'multiple_choice';
            handleQuestionTypeChange(typeSelect);
        }

        switch (existingData.type) {
            case 'multiple_choice': {
                const optionsContainer = questionEl.querySelector('.options-container');
                const addBtn           = optionsContainer?.querySelector('.btn');
                const options          = existingData.options || [];
                const correctIndex     = options.indexOf(existingData.correct_answer);

                options.forEach((optText, i) => {
                    if (addBtn) addOption(addBtn);
                    const optionsList = optionsContainer.querySelector('.options-list');
                    const rows        = optionsList.querySelectorAll('.option-item');
                    const lastRow     = rows[rows.length - 1];
                    if (!lastRow) return;

                    const textInput = lastRow.querySelector('input[type="text"]');
                    if (textInput) textInput.value = optText;

                    const radio = lastRow.querySelector('input[type="radio"]');
                    if (radio && i === correctIndex) radio.checked = true;
                });
                break;
            }

            case 'true_false': {
                const tfContainer = questionEl.querySelector('.true-false-container');
                if (tfContainer && existingData.correct_answer) {
                    const normalised =
                        existingData.correct_answer.charAt(0).toUpperCase() +
                        existingData.correct_answer.slice(1).toLowerCase();
                    const radio = tfContainer.querySelector(`input[value="${normalised}"]`);
                    if (radio) radio.checked = true;
                }
                break;
            }

            case 'short_answer': {
                const saContainer = questionEl.querySelector('.short-answer-container');
                const input = saContainer?.querySelector('input[type="text"]');
                if (input) input.value = existingData.correct_answer || '';
                break;
            }

            case 'essay': {
                const essayContainer = questionEl.querySelector('.essay-container');
                const textarea = essayContainer?.querySelector('textarea');
                if (textarea) textarea.value = existingData.explanation || '';
                break;
            }
        }

    } else {
        const typeSelect = questionEl.querySelector('.question-type');
        if (typeSelect) handleQuestionTypeChange(typeSelect);

        const optionsContainer = questionEl.querySelector('.options-container');
        const addBtn           = optionsContainer?.querySelector('.btn');
        if (addBtn) {
            for (let i = 0; i < 4; i++) addOption(addBtn);
        }
    }

    initializeQuestionEditors(questionEl);
    questionCount++;
    updateQuestionNumbers();
}

function addEssay(existingData) {
    const container = document.getElementById('essay-questions-container');
    const noEssayMsg = document.getElementById('no-essay-questions-message');
    const template = document.getElementById('essay-question-template');

    if (!template) { console.error('Essay question template not found!'); return; }
    if (noEssayMsg) noEssayMsg.style.display = 'none';

    let html = template.innerHTML.replace(/{idx}/g, questionCount);
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html.trim();
    const questionEl = tempDiv.firstElementChild;
    if (!questionEl) return;

    questionEl.dataset.questionIdx = questionCount;
    const numberSpan = questionEl.querySelector('.essay-question-number');
    if (numberSpan) numberSpan.textContent = container.children.length + 1;
    container.appendChild(questionEl);

    if (existingData) {
        const textArea = questionEl.querySelector('.question-text');
        if (textArea) textArea.value = existingData.text || '';

        const pointsInput = questionEl.querySelector('.question-points');
        if (pointsInput) pointsInput.value = parseInt(existingData.points) || 1;

        const explanation = questionEl.querySelector('textarea[name$="[explanation]"]');
        if (explanation) explanation.value = existingData.explanation || '';
    }

    initializeQuestionEditors(questionEl);
    questionCount++;
    updateEssayNumbers();
}

function removeEssay(button) {
    const questionItem = button.closest('.question-item');
    if (questionItem) {
        destroyQuestionEditors(questionItem);
        questionItem.remove();
        if (document.querySelectorAll('#essay-questions-container .question-item').length === 0) {
            const noEssayMsg = document.getElementById('no-essay-questions-message');
            if (noEssayMsg) noEssayMsg.style.display = 'block';
        }
        updateEssayNumbers();
    }
}

function updateEssayNumbers() {
    document.querySelectorAll('#essay-questions-container .question-item').forEach((q, i) => {
        const span = q.querySelector('.essay-question-number');
        if (span) span.textContent = i + 1;
    });
}

// ── Remove question ───────────────────────────────────────────────────────────
function removeQuestion(button) {
    const questionItem = button.closest('.question-item');
    if (questionItem) {
        destroyQuestionEditors(questionItem);
        questionItem.remove();
        if (document.querySelectorAll('.question-item').length === 0) {
            document.getElementById('no-questions-message').style.display = 'block';
        }
        updateQuestionNumbers();
    }
}

function updateQuestionNumbers() {
    document.querySelectorAll('.question-item').forEach((q, i) => {
        const span = q.querySelector('.question-number');
        if (span) span.textContent = i + 1;
    });
}

// ── Question type toggle ──────────────────────────────────────────────────────
function handleQuestionTypeChange(select) {
    const questionItem = select.closest('.question-item');
    if (!questionItem) return;

    const optionsContainer     = questionItem.querySelector('.options-container');
    const trueFalseContainer   = questionItem.querySelector('.true-false-container');
    const shortAnswerContainer = questionItem.querySelector('.short-answer-container');

    [optionsContainer, trueFalseContainer, shortAnswerContainer].forEach(c => {
        if (c) {
            c.querySelectorAll('input[required]').forEach(i => i.removeAttribute('required'));
            c.style.display = 'none';
        }
    });

    const essayContainer = questionItem.querySelector('.essay-container');

    switch (select.value) {
        case 'multiple_choice':
            if (optionsContainer) {
                optionsContainer.style.display = 'block';
                optionsContainer.querySelectorAll('input[type="text"]')
                    .forEach(i => i.setAttribute('required', 'required'));
            }
            break;
        case 'true_false':
            if (trueFalseContainer) {
                trueFalseContainer.style.display = 'block';
                const radios = trueFalseContainer.querySelectorAll('input[type="radio"]');
                if (radios.length) radios[0].setAttribute('required', 'required');
            }
            break;
        case 'short_answer':
            if (shortAnswerContainer) {
                shortAnswerContainer.style.display = 'block';
                const inp = shortAnswerContainer.querySelector('input[type="text"]');
                if (inp) inp.setAttribute('required', 'required');
            }
            break;
        case 'essay':
            if (essayContainer) {
                essayContainer.style.display = 'block';
            }
            break;
    }
}

// ── Add option ────────────────────────────────────────────────────────────────
function addOption(button) {
    const optionsContainer = button.closest('.options-container');
    if (!optionsContainer) return;

    const optionsList  = optionsContainer.querySelector('.options-list');
    const template     = document.getElementById('option-template');
    const questionItem = button.closest('.question-item');

    if (!template || !questionItem || !optionsList) return;

    const idx         = questionItem.dataset.questionIdx;
    const optionIndex = optionsList.children.length;

    let html = template.innerHTML
        .replace(/{idx}/g, idx)
        .replace(/{option-index}/g, optionIndex);

    const tempDiv     = document.createElement('div');
    tempDiv.innerHTML = html.trim();
    const optionEl    = tempDiv.firstElementChild;

    if (optionEl) {
        const textInput = optionEl.querySelector('input[type="text"]');
        if (textInput) textInput.setAttribute('required', 'required');
        optionsList.appendChild(optionEl);
    }
}

// ── Remove option ─────────────────────────────────────────────────────────────
function removeOption(button) {
    const optionItem = button.closest('.option-item');
    if (optionItem) optionItem.remove();
}

// ── Form submit ───────────────────────────────────────────────────────────────
document.getElementById('quizForm')?.addEventListener('submit', function (e) {
    syncQuestionEditors();
    const questions = document.querySelectorAll('.question-item');

    if (questions.length === 0) {
        e.preventDefault();
        alert('❌ Please add at least one question.');
        return false;
    }

    for (let i = 0; i < questions.length; i++) {
        const question     = questions[i];
        const questionText = question.querySelector('.question-text')?.value;
        const points       = question.querySelector('.question-points')?.value;
        const typeSelect   = question.querySelector('.question-type');

        if (!hasQuestionContent(questionText)) {
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

        switch (typeSelect.value) {
            case 'multiple_choice': {
                const options = question.querySelectorAll(
                    '.option-item input[type="text"]'
                );
                if (options.length < 2) {
                    e.preventDefault();
                    alert(`❌ Question ${i + 1}: Multiple choice needs at least 2 options.`);
                    return false;
                }
                let hasEmpty = false;
                options.forEach(o => { if (!o.value.trim()) hasEmpty = true; });
                if (hasEmpty) {
                    e.preventDefault();
                    alert(`❌ Question ${i + 1}: All options must have text.`);
                    return false;
                }
                if (!question.querySelector(
                    '.options-container input[type="radio"]:checked'
                )) {
                    e.preventDefault();
                    alert(`❌ Question ${i + 1}: Please select the correct answer.`);
                    return false;
                }
                break;
            }
            case 'true_false':
                if (!question.querySelector(
                    '.true-false-container input[type="radio"]:checked'
                )) {
                    e.preventDefault();
                    alert(`❌ Question ${i + 1}: Please select True or False.`);
                    return false;
                }
                break;
            case 'short_answer': {
                const val = question.querySelector(
                    '.short-answer-container input[type="text"]'
                )?.value;
                if (!val?.trim()) {
                    e.preventDefault();
                    alert(`❌ Question ${i + 1}: Correct answer is required.`);
                    return false;
                }
                break;
            }
        }
    }

    // Disable inputs in hidden containers so they don't pollute the payload
    document.querySelectorAll('.question-item').forEach(question => {
        const type = question.querySelector('.question-type')?.value;
        if (!type) return;

        const containers = {
            multiple_choice: question.querySelector('.options-container'),
            true_false:      question.querySelector('.true-false-container'),
            short_answer:    question.querySelector('.short-answer-container'),
            essay:           question.querySelector('.essay-container'),
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
        submitBtn.disabled  = true;
        submitBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Saving Changes...';
    }

    return true;
});
</script>
@endpush
