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

    <form action="{{ route('admin.courses.modules.store', $course->slug) }}" method="POST" id="moduleForm">
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
                                <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" 
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
                                <textarea name="full_content" class="form-control @error('full_content') is-invalid @enderror" 
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
                            <textarea id="editor1" name="learning_objectives" class="form-control @error('learning_objectives') is-invalid @enderror" 
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
                            <textarea id="editor2" name="topics_covered" class="form-control @error('topics_covered') is-invalid @enderror" 
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
                            <textarea id="editor3" name="key_concepts" class="form-control @error('key_concepts') is-invalid @enderror" 
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
                            <textarea id="editor4" name="case_study" class="form-control @error('case_study') is-invalid @enderror" 
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
                            <textarea id="editor5" name="exercise" class="form-control @error('exercise') is-invalid @enderror" 
                                      rows="5" placeholder="Design a practical exercise for this module">
                            </textarea>
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
                            <textarea id="editor6" name="additional_notes" class="form-control @error('additional_notes') is-invalid @enderror" 
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
                                        <button type="submit" class="btn btn-primary flex-grow-1">
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
     // Initialize CKEditor 5
    ClassicEditor
    .create(document.querySelector('#editor1'))
    .catch(error => { console.error(error); });
    ClassicEditor
    .create(document.querySelector('#editor2'))
    .catch(error => { console.error(error); });
    ClassicEditor
    .create(document.querySelector('#editor3'))
    .catch(error => { console.error(error); });
    ClassicEditor
    .create(document.querySelector('#editor4'))
    .catch(error => { console.error(error); });
    ClassicEditor
    .create(document.querySelector('#editor5'))
    .catch(error => { console.error(error); });
    ClassicEditor
    .create(document.querySelector('#editor6'))
    .catch(error => { console.error(error); });
   

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
    const moduleTitleInput = document.querySelector('input[name="title"]');
    const previewModuleNumber = document.getElementById('previewModuleNumber');
    const previewEstimatedHours = document.getElementById('previewEstimatedHours');

    function updatePreview() {
        const moduleNumber = moduleNumberInput ? moduleNumberInput.value : {{ $nextModuleNumber }};
        const estimatedHours = estimatedHoursInput ? estimatedHoursInput.value : 2;
        
        if (previewModuleNumber) {
            previewModuleNumber.textContent = `Module ${moduleNumber}`;
        }
        if (previewEstimatedHours) {
            previewEstimatedHours.textContent = `${estimatedHours} hour${estimatedHours > 1 ? 's' : ''}`;
        }
    }

    if (moduleNumberInput) moduleNumberInput.addEventListener('input', updatePreview);
    if (estimatedHoursInput) estimatedHoursInput.addEventListener('input', updatePreview);
    updatePreview(); // Initial update

    // Form validation
    const moduleForm = document.getElementById('moduleForm');
    if (moduleForm) {
        moduleForm.addEventListener('submit', function(e) {
            // Clear previous custom validity messages
            const inputs = this.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.setCustomValidity('');
            });

            // Validate estimated hours
            if (estimatedHoursInput) {
                const hours = parseInt(estimatedHoursInput.value) || 0;
                if (hours < 1 || hours > 100) {
                    estimatedHoursInput.setCustomValidity('Estimated hours must be between 1 and 100');
                    e.preventDefault();
                    estimatedHoursInput.reportValidity();
                    return;
                }
            }

            // Confirm before submitting
            if (!confirm('Are you sure you want to create this module?')) {
                e.preventDefault();
                return;
            }
        });
    }

    

    console.log('Module creation form initialized');
});
</script>
@endpush