@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create New Module for: {{ $course->title }}</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.courses.index') }}" class="hover-text-primary">Courses</a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.courses.show', $course->id) }}" class="hover-text-primary">{{ Str::limit($course->title, 20) }}</a>
            </li>
            <li>-</li>
            <li class="fw-medium">Create Module</li>
        </ul>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.courses.modules.store', $course->slug) }}" method="POST" id="moduleForm" novalidate>
        @csrf
        <div class="row gy-4">
            <div class="col-lg-8">
                <!-- Module Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Module Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Module Number <span class="text-danger">*</span></label>
                                <input type="number" name="module_number" class="form-control @error('module_number') is-invalid @enderror" 
                                       value="{{ old('module_number', $nextModuleNumber) }}" min="1" required>
                                <p class="text-sm mt-1 mb-0 text-muted">Auto-generated: {{ $nextModuleNumber }}</p>
                                @error('module_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Module Code</label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                       placeholder="e.g., MOD1, FOUNDATIONS" value="{{ old('code') }}">
                                <p class="text-sm mt-1 mb-0 text-muted">Optional: Custom module identifier</p>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Module Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       placeholder="e.g., Foundations of Governance, Risk and Compliance" 
                                       value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                <textarea id="editor8" name="short_description" class="form-control rich-editor @error('short_description') is-invalid @enderror" 
                                          rows="3" placeholder="Brief description of the module (max 500 characters)" 
                                          required maxlength="500">{{ old('short_description') }}</textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Maximum 500 characters</small>
                                    <small class="character-count" data-target="short_description">0/500</small>
                                </div>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Full Content <span class="text-danger">*</span></label>
                                <textarea id="editor7" name="full_content" class="form-control rich-editor @error('full_content') is-invalid @enderror" 
                                          rows="10" placeholder="Detailed content for the module...">{{ old('full_content') }}</textarea>
                                @error('full_content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Estimated Hours <span class="text-danger">*</span></label>
                                <input type="number" name="estimated_hours" class="form-control @error('estimated_hours') is-invalid @enderror" 
                                       placeholder="2" value="{{ old('estimated_hours', 2) }}" min="1" max="100" required>
                                @error('estimated_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                       placeholder="{{ $nextModuleNumber * 10 }}" value="{{ old('sort_order', $nextModuleNumber * 10) }}">
                                <p class="text-sm mt-1 mb-0 text-muted">Lower numbers appear first</p>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Learning Objectives -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Learning Objectives</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">What will students learn in this module?</label>
                            <textarea id="editor1" name="learning_objectives" class="form-control rich-editor @error('learning_objectives') is-invalid @enderror" 
                            rows="5" placeholder="List the learning objectives (one per line or bullet points)"></textarea>
                            @error('learning_objectives')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Topics Covered -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Topics Covered</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">What topics will be covered in this module?</label>
                            <textarea id="editor2" name="topics_covered" class="form-control rich-editor @error('topics_covered') is-invalid @enderror" 
                                      rows="5" placeholder="List the topics that will be covered"></textarea>
                            @error('topics_covered')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Key Concepts -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Key Concepts</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">What are the key concepts in this module?</label>
                            <textarea id="editor3" name="key_concepts" class="form-control rich-editor @error('key_concepts') is-invalid @enderror" 
                                      rows="5" placeholder="List the key concepts students should understand"></textarea>
                            @error('key_concepts')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Case Study -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Case Study</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Real-world case study for this module</label>
                            <textarea id="editor4" name="case_study" class="form-control rich-editor @error('case_study') is-invalid @enderror" 
                                      rows="5" placeholder="Provide a relevant case study"></textarea>
                            @error('case_study')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Exercise -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Practical Exercise</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Practical exercise for students</label>
                            <textarea id="editor5" name="exercise" class="form-control rich-editor @error('exercise') is-invalid @enderror" 
                                      rows="5" placeholder="Design a practical exercise for this module"></textarea>
                            @error('exercise')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Additional Notes</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Any additional notes or instructions</label>
                            <textarea id="editor6" name="additional_notes" class="form-control rich-editor @error('additional_notes') is-invalid @enderror" 
                                      rows="5" placeholder="Additional information for instructors or students"></textarea>
                            @error('additional_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Module Settings -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Module Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Module
                                    </label>
                                    <p class="text-sm text-muted mb-0">Inactive modules are hidden from students</p>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border-top pt-3 mt-3">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1" id="submitBtn">
                                            Create Module
                                        </button>
                                        <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Information -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Course Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            @if($course->image)
                                <img src="{{ asset('storage/'.$course->image) }}" alt="{{ $course->title }}" 
                                     class="rounded-8" style="width: 40px; height: 40px; object-fit: cover;">
                            @endif
                            <div>
                                <h6 class="mb-0">{{ Str::limit($course->title, 30) }}</h6>
                                <small class="text-muted">{{ $course->code }}</small>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Modules:</span>
                                <span class="fw-medium">{{ $course->modules->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Level:</span>
                                <span class="fw-medium">{{ ucfirst($course->level) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Format:</span>
                                <span class="fw-medium">{{ ucfirst(str_replace('_', ' ', $course->format)) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Duration:</span>
                                <span class="fw-medium">{{ $course->duration }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module Preview -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Module Preview</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="mb-3">
                                <iconify-icon icon="mdi:book-open-page-variant" class="icon-3x text-primary"></iconify-icon>
                            </div>
                            <h6 id="previewModuleNumber">Module {{ $nextModuleNumber }}</h6>
                            <p class="text-sm text-muted mb-0" id="previewEstimatedHours">2 hours</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Tips -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Quick Tips</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Keep learning objectives clear and measurable</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Use real-world case studies for better engagement</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Include practical exercises to reinforce learning</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">2-4 hours per module is ideal for online learning</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Pre-filled with sample content - modify as needed</small>
                            </div>
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
.character-count {
    font-size: 0.75rem;
    color: #6c757d;
}
.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}
.form-control.is-invalid {
    border-color: #dc3545;
}
.icon-3x {
    font-size: 3rem;
}
/* CKEditor styling */
.ck-editor__editable {
    min-height: 200px;
    max-height: 500px;
    overflow-y: auto;
}
</style>
@endpush

@push('scripts')
<!-- Load CKEditor from CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Module creation form loading...');
    
    // Store CKEditor instances
    const ckEditors = {};
    let isFormSubmitting = false;
    
    // Initialize CKEditor for all rich-editor textareas
    function initializeCKEditors() {
        if (typeof ClassicEditor === 'undefined') {
            console.error('CKEditor not loaded. Please check the CDN.');
            // Remove rich-editor class to use plain textareas
            document.querySelectorAll('.rich-editor').forEach(textarea => {
                textarea.classList.remove('rich-editor');
            });
            return;
        }
        
        const editorTextareas = document.querySelectorAll('textarea.rich-editor');
        console.log('Found rich-editor textareas:', editorTextareas.length);
        
        if (editorTextareas.length === 0) {
            return;
        }
        
        // Initialize each CKEditor
        editorTextareas.forEach((textarea, index) => {
            const editorId = textarea.id || `editor-${index}`;
            
            // Skip if already initialized
            if (ckEditors[editorId]) {
                console.log(`Editor ${editorId} already initialized`);
                return;
            }
            
            console.log(`Initializing CKEditor for: ${editorId} (${textarea.name})`);
            
            // Create CKEditor instance
            ClassicEditor
                .create(textarea, {
                    // Simple configuration
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                            'undo', 'redo'
                        ]
                    },
                    // Remove problematic plugins
                    removePlugins: ['MediaEmbed', 'Table', 'ImageUpload', 'Image', 'ImageToolbar', 'ImageCaption'],
                    // Simple upload if needed
                    simpleUpload: {
                        uploadUrl: '{{ route("admin.upload") }}'
                    }
                })
                .then(editor => {
                    console.log(`CKEditor ${editorId} initialized successfully`);
                    ckEditors[editorId] = editor;
                    
                    // Update the textarea when editor changes
                    editor.model.document.on('change:data', () => {
                        textarea.value = editor.getData();
                    });
                    
                    // Set initial value
                    textarea.value = editor.getData();
                })
                .catch(error => {
                    console.error(`Failed to initialize CKEditor ${editorId}:`, error);
                    // If CKEditor fails, remove the rich-editor class
                    textarea.classList.remove('rich-editor');
                    textarea.style.display = 'block';
                });
        });
    }
    
    // Wait a bit before initializing CKEditor
    setTimeout(initializeCKEditors, 300);
    
    // Character count functionality
    function setupCharacterCount(textareaSelector, counterSelector) {
        const textarea = document.querySelector(textareaSelector);
        const counter = document.querySelector(counterSelector);
        
        if (textarea && counter) {
            const maxLength = textarea.getAttribute('maxlength') || 500;
            
            function updateCount() {
                const length = textarea.value.length;
                counter.textContent = `${length}/${maxLength}`;
                
                if (length > maxLength) {
                    counter.style.color = '#dc3545';
                } else if (length > (maxLength * 0.9)) {
                    counter.style.color = '#ffc107';
                } else {
                    counter.style.color = '#6c757d';
                }
            }
            
            textarea.addEventListener('input', updateCount);
            updateCount(); // Initial count
        }
    }
    
    // Set up character counter
    setupCharacterCount('textarea[name="short_description"]', '.character-count[data-target="short_description"]');
    
    // Update preview in real-time
    const moduleNumberInput = document.querySelector('input[name="module_number"]');
    const estimatedHoursInput = document.querySelector('input[name="estimated_hours"]');
    const previewModuleNumber = document.getElementById('previewModuleNumber');
    const previewEstimatedHours = document.getElementById('previewEstimatedHours');
    
    function updatePreview() {
        const moduleNumber = moduleNumberInput ? moduleNumberInput.value : {{ $nextModuleNumber }};
        const estimatedHours = estimatedHoursInput ? estimatedHoursInput.value : 2;
        
        if (previewModuleNumber) {
            previewModuleNumber.textContent = `Module ${moduleNumber}`;
        }
        if (previewEstimatedHours) {
            previewEstimatedHours.textContent = `${estimatedHours} hour${estimatedHours != 1 ? 's' : ''}`;
        }
    }
    
    if (moduleNumberInput) moduleNumberInput.addEventListener('input', updatePreview);
    if (estimatedHoursInput) estimatedHoursInput.addEventListener('input', updatePreview);
    updatePreview(); // Initial update
    
    // Form validation and submission
    const moduleForm = document.getElementById('moduleForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (moduleForm) {
        console.log('Form found, attaching submit handler');
        
        // Handle form submission
        moduleForm.addEventListener('submit', async function(e) {
            console.log('Form submit event triggered');
            
            // Prevent double submission
            if (isFormSubmitting) {
                e.preventDefault();
                return false;
            }
            
            // Prevent default to handle validation first
            e.preventDefault();
            
            // Set submitting flag
            isFormSubmitting = true;
            
            // Update submit button state
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
            }
            
            try {
                // Sync CKEditor data
                console.log('Syncing CKEditor data...');
                Object.keys(ckEditors).forEach(editorId => {
                    const editor = ckEditors[editorId];
                    if (editor && typeof editor.getData === 'function') {
                        const textarea = editor.sourceElement;
                        if (textarea) {
                            const data = editor.getData();
                            textarea.value = data;
                            console.log(`Synced ${editorId}: ${data.length} chars`);
                        }
                    }
                });
                
                // Validate form
                console.log('Validating form...');
                if (!validateForm()) {
                    resetSubmitButton();
                    isFormSubmitting = false;
                    return false;
                }
                
                // Confirm before submitting
                if (!confirm('Are you sure you want to create this module?')) {
                    resetSubmitButton();
                    isFormSubmitting = false;
                    return false;
                }
                
                console.log('Form validation passed, submitting...');
                
                // Submit form programmatically
                this.submit();
                
            } catch (error) {
                console.error('Error during form submission:', error);
                alert('An error occurred. Please try again.');
                resetSubmitButton();
                isFormSubmitting = false;
            }
        });
        
        // Reset submit button
        function resetSubmitButton() {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Create Module';
            }
        }
        
        // Form validation function
        function validateForm() {
            let isValid = true;
            let errorMessage = '';
            let firstInvalidField = null;
            
            // Validate required fields
            const requiredFields = moduleForm.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                        const fieldName = field.name.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                        errorMessage = `Please fill in "${fieldName}"`;
                    }
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Validate estimated hours
            if (estimatedHoursInput) {
                const hours = parseInt(estimatedHoursInput.value) || 0;
                if (hours < 1 || hours > 100) {
                    isValid = false;
                    estimatedHoursInput.classList.add('is-invalid');
                    errorMessage = 'Estimated hours must be between 1 and 100';
                    if (!firstInvalidField) firstInvalidField = estimatedHoursInput;
                } else {
                    estimatedHoursInput.classList.remove('is-invalid');
                }
            }
            
            // Validate short description length
            const shortDesc = moduleForm.querySelector('textarea[name="short_description"]');
            if (shortDesc) {
                const maxLength = shortDesc.getAttribute('maxlength') || 500;
                if (shortDesc.value.length > maxLength) {
                    isValid = false;
                    shortDesc.classList.add('is-invalid');
                    errorMessage = `Short description must be ${maxLength} characters or less`;
                    if (!firstInvalidField) firstInvalidField = shortDesc;
                } else if (shortDesc.value.length === 0) {
                    isValid = false;
                    shortDesc.classList.add('is-invalid');
                    errorMessage = 'Short description is required';
                    if (!firstInvalidField) firstInvalidField = shortDesc;
                } else {
                    shortDesc.classList.remove('is-invalid');
                }
            }
            
            // Show error if validation fails
            if (!isValid && errorMessage) {
                alert(errorMessage);
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
                return false;
            }
            
            return true;
        }
        
        // Real-time validation
        moduleForm.querySelectorAll('input[required], textarea[required]').forEach(field => {
            field.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
            
            field.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });
        
    } else {
        console.error('Form not found! Check the form ID "moduleForm"');
    }
    
    // Add a test button for debugging
    const debugButton = document.createElement('button');
    debugButton.type = 'button';
    debugButton.className = 'btn btn-sm btn-warning position-fixed';
    debugButton.style.bottom = '20px';
    debugButton.style.right = '20px';
    debugButton.style.zIndex = '9999';
    debugButton.textContent = 'Test Submit';
    debugButton.onclick = function() {
        console.log('Test button clicked');
        console.log('CKEditor instances:', Object.keys(ckEditors).length);
        console.log('Form action:', moduleForm ? moduleForm.action : 'No form');
        
        // Test form submission
        if (moduleForm) {
            // Trigger form submit
            const submitEvent = new Event('submit', { cancelable: true });
            moduleForm.dispatchEvent(submitEvent);
        }
    };
    document.body.appendChild(debugButton);
    
    console.log('Module creation form initialized successfully');
});
</script>
@endpush