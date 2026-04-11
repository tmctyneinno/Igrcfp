@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create Project Assessment</h6>
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
            <li class="fw-medium">Create Project Assessment</li>
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

    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" id="projectForm">
        @csrf
        <input type="hidden" name="assessment_level" value="diploma">
        <input type="hidden" name="type" value="project">
        <input type="hidden" name="needs_manual_marking" value="1">

        <div class="row gy-4">
            <!-- Left Column - Basic Info & Project Brief -->
            <div class="col-lg-8">
                <!-- Basic Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Project Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title') }}" placeholder="e.g., Final Diploma Project" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Course <span class="text-danger">*</span></label>
                                <select name="course_id" id="courseSelect" class="form-select @error('course_id') is-invalid @enderror" required>
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
                                <select name="module_id" id="moduleSelect" class="form-select @error('module_id') is-invalid @enderror">
                                    <option value="">Select Module (Optional)</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                                            Module {{ $module->module_number }}: {{ $module->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('module_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Short Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3" placeholder="Brief description of the project">{{ old('description') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Brief Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Project Brief <span class="text-danger">*</span></h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Project Overview</label>
                            <textarea name="project_brief" id="projectBrief" class="form-control rich-editor @error('project_brief') is-invalid @enderror" 
                                      rows="10" placeholder="Provide a detailed project brief including objectives, scope, and requirements..." required>{{ old('project_brief') }}</textarea>
                            @error('project_brief') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deliverables</label>
                            <textarea name="deliverables" id="deliverables" class="form-control rich-editor @error('deliverables') is-invalid @enderror" 
                                      rows="4" placeholder="List the expected deliverables">{{ old('deliverables') }}</textarea>
                            <p class="text-muted small mt-1">Enter each deliverable on a new line</p>
                            @error('deliverables') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Instructions</label>
                            <textarea name="instructions" id="instructions" class="form-control rich-editor @error('instructions') is-invalid @enderror" 
                                      rows="4" placeholder="Provide detailed instructions for students">{{ old('instructions') }}</textarea>
                            @error('instructions') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Resources Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Resources & Templates</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload Project Template (Optional)</label>
                            <input type="file" name="assessment_file" class="form-control" accept=".pdf,.doc,.docx,.pptx,.xlsx">
                            <p class="text-muted small mt-1">Upload a template or starter file for students</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Resource Links (Optional)</label>
                            <textarea name="settings[resources]" class="form-control" rows="3" placeholder="Add helpful resource links (one per line)">{{ old('settings.resources') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Settings -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Project Settings</h6>
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
                            <input type="number" name="total_marks" class="form-control" value="{{ old('total_marks', 100) }}" min="1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Passing Score (%)</label>
                            <input type="number" name="passing_score" class="form-control" value="{{ old('passing_score', 70) }}" min="1" max="100">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Weight (% of final grade)</label>
                            <input type="number" name="weight" class="form-control" value="{{ old('weight', 40) }}" min="1" max="100">
                        </div>
                    </div>
                </div>

                <!-- Submission Settings Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Submission Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Release Date</label>
                            <input type="datetime-local" name="release_date" class="form-control" value="{{ old('release_date') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Submission Deadline <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="due_date" class="form-control @error('due_date') is-invalid @enderror" 
                                   value="{{ old('due_date') }}" required>
                            @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="allow_late_submissions" id="allowLate" value="1">
                                <label class="form-check-label" for="allowLate">
                                    Allow Late Submissions
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Maximum File Size (MB)</label>
                            <input type="number" name="settings[max_file_size]" class="form-control" value="{{ old('settings.max_file_size', 50) }}" min="1">
                        </div>
                    </div>
                </div>

                <!-- Grading Settings Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Grading Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="needs_manual_marking" id="needsManualMarking" value="1" checked disabled>
                                <label class="form-check-label" for="needsManualMarking">
                                    Requires Manual Marking
                                </label>
                            </div>
                            <p class="text-muted small mt-1">Project assessments always require manual grading</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Assigned Grader (Optional)</label>
                            <select name="graded_by" class="form-select">
                                <option value="">Select Grader</option>
                            </select>
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
                                <input class="form-check-input" type="checkbox" name="requires_identity_verification" id="requiresIdentity" value="1">
                                <label class="form-check-label" for="requiresIdentity">
                                    Requires Identity Verification
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="settings[enable_plagiarism_check]" id="enablePlagiarism" value="1">
                                <label class="form-check-label" for="enablePlagiarism">
                                    Enable Plagiarism Check
                                </label>
                            </div>
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
                            <button type="submit" name="action" value="draft" class="btn btn-outline-primary px-4">Save as Draft</button>
                            <button type="submit" name="action" value="publish" class="btn btn-primary px-5">Publish Project</button>
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
    .rubric-item {
        transition: all 0.2s ease;
        border: 1px solid #e0e0e0;
    }
    .rubric-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-color: #0A1F44;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .form-label.fw-semibold {
        color: #2c3e50;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .btn-primary {
        background-color: #0A1F44;
        border-color: #0A1F44;
    }
    .btn-primary:hover {
        background-color: #0c2b5c;
        border-color: #0c2b5c;
    }
    .btn-outline-primary {
        border-color: #0A1F44;
        color: #0A1F44;
    }
    .btn-outline-primary:hover {
        background-color: #0A1F44;
        color: white;
    }
    .ck-editor__editable {
        min-height: 200px;
    }
</style>
@endpush

@push('scripts')
{{-- CKEditor 5 --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
let rubricCount = 1;
let editors = {};

// Initialize CKEditor for rich text fields
function initializeEditors() {
    const richTextareas = [
        { id: 'projectBrief', height: 300 },
        { id: 'deliverables', height: 150 },
        { id: 'instruction', height: 150 }
    ];
    
    richTextareas.forEach(({ id, height }) => {
        const element = document.getElementById(id);
        if (element) {
            ClassicEditor
                .create(element, {
                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            '|',
                            'bulletedList',
                            'numberedList',
                            '|',
                            'outdent',
                            'indent',
                            '|',
                            'blockQuote',
                            'insertTable',
                            'undo',
                            'redo'
                        ]
                    },
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                        ]
                    },
                    table: {
                        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                    }
                })
                .then(editor => {
                    editors[id] = editor;
                    
                    // Set initial height
                    editor.editing.view.change(writer => {
                        writer.setStyle('height', height + 'px', editor.editing.view.document.getRoot());
                    });
                })
                .catch(error => {
                    console.error('CKEditor error:', error);
                });
        }
    });
}

// Update form data before submit
function updateFormData() {
    // Sync CKEditor content to textareas
    Object.keys(editors).forEach(id => {
        const editor = editors[id];
        const textarea = document.getElementById(id);
        if (editor && textarea) {
            textarea.value = editor.getData();
        }
    });
}

// Filter modules by course
document.getElementById('courseSelect')?.addEventListener('change', function() {
    const courseId = this.value;
    const moduleSelect = document.getElementById('moduleSelect');
    
    if (!courseId) {
        moduleSelect.innerHTML = '<option value="">Select Module (Optional)</option>';
        return;
    }
    
    fetch(`/admin/get-modules/${courseId}`)
        .then(response => response.json())
        .then(modules => {
            let options = '<option value="">Select Module (Optional)</option>';
            modules.forEach(module => {
                options += `<option value="${module.id}">Module ${module.module_number}: ${module.title}</option>`;
            });
            moduleSelect.innerHTML = options;
        })
        .catch(error => console.error('Error fetching modules:', error));
});

function addRubricItem() {
    const container = document.getElementById('rubric-container');
    if (!container) return;
    
    const template = container.querySelector('.rubric-item');
    const newItem = template.cloneNode(true);
    
    newItem.querySelectorAll('input, textarea').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace(/\[\d+\]/, `[${rubricCount}]`));
        }
        input.value = '';
    });
    
    container.appendChild(newItem);
    rubricCount++;
}

function removeRubricItem(button) {
    const container = document.getElementById('rubric-container');
    if (container && container.children.length > 1) {
        button.closest('.rubric-item').remove();
    } else {
        alert('At least one rubric item is required.');
    }
}

// Initialize editors on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEditors();
});

// Form validation and submission
document.getElementById('projectForm').addEventListener('submit', function(e) {
    // Update textareas with CKEditor content
    updateFormData();
    
    const submitBtn = this.querySelector('button[type="submit"][value="publish"]');
    const draftBtn = this.querySelector('button[type="submit"][value="draft"]');
    const action = e.submitter?.value || 'publish';
    
    if (action === 'draft') {
        // Remove required validation for draft
        this.querySelectorAll('[required]').forEach(el => el.removeAttribute('required'));
    }
    
    // Disable buttons during submission
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Publishing...';
    }
    if (draftBtn) {
        draftBtn.disabled = true;
        draftBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    }
    
    return true;
});
</script>
@endpush