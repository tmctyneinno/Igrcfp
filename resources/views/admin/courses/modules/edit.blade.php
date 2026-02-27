@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Edit Module: {{ $module->title }}</h6>
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
            <li class="fw-medium">Edit Module</li>
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

    <form action="{{ route('admin.courses.modules.update', ['course' => $course->slug, 'module' => $module->id]) }}" method="POST" id="moduleForm">
        @csrf
        @method('PUT')
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
                                       value="{{ old('module_number', $module->module_number) }}" min="1" required>
                                @error('module_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Module Code</label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                       placeholder="e.g., MOD1, FOUNDATIONS" value="{{ old('code', $module->code) }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Module Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       placeholder="e.g., Foundations of Governance, Risk and Compliance" 
                                       value="{{ old('title', $module->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                <textarea id="short_description" name="short_description" class="form-control @error('short_description') is-invalid @enderror" 
                                          rows="3" placeholder="Brief description of the module (max 500 characters)" 
                                          required maxlength="500">{{ old('short_description', $module->short_description) }}</textarea>
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
                                <textarea id="full_content" name="full_content" class="form-control @error('full_content') is-invalid @enderror" 
                                          rows="10" placeholder="Detailed content for the module...">{{ old('full_content', $module->full_content) }}</textarea>
                                @error('full_content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Estimated Hours <span class="text-danger">*</span></label>
                                <input type="number" name="estimated_hours" class="form-control @error('estimated_hours') is-invalid @enderror" 
                                       placeholder="2" value="{{ old('estimated_hours', $module->estimated_hours) }}" min="1" max="100" required>
                                @error('estimated_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                       value="{{ old('sort_order', $module->sort_order) }}">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========== LESSONS MANAGEMENT SECTION - INSERT HERE ========== -->
                <div class="card mt-24">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">Module Lessons</h6>
                            <a href="{{ route('admin.courses.modules.lessons.create', [$course->id, $module->id]) }}" 
                               class="btn btn-sm btn-primary">
                                <iconify-icon icon="mdi:plus"></iconify-icon>
                                Add Lesson
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($module->lessons->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Lesson Title</th>
                                            <th>Duration</th>
                                            <th>Preview</th>
                                            <th>Status</th>
                                            <th width="150">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lessonSortable">
                                        @foreach($module->lessons->sortBy('sort_order') as $lesson)
                                            <tr data-id="{{ $lesson->id }}">
                                                <td>
                                                    <span class="sort-handle" style="cursor: move;">
                                                        <iconify-icon icon="mdi:drag"></iconify-icon>
                                                        {{ $loop->iteration }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $lesson->title }}</strong>
                                                        @if($lesson->short_description)
                                                            <p class="text-muted small mb-0">{{ Str::limit($lesson->short_description, 50) }}</p>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $lesson->formatted_duration ?? $lesson->duration . ' min' }}</td>
                                                <td>
                                                    @if($lesson->is_free)
                                                        <span class="badge bg-success">Free Preview</span>
                                                    @else
                                                        <span class="badge bg-secondary">Enrolled Only</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($lesson->is_published)
                                                        <span class="badge bg-success">Published</span>
                                                    @else
                                                        <span class="badge bg-warning">Draft</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('admin.courses.modules.lessons.edit', [$course->id, $module->id, $lesson->id]) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                                            <iconify-icon icon="mdi:pencil"></iconify-icon>
                                                        </a>
                                                        <a href="#" class="btn btn-sm btn-outline-info" title="Preview" target="_blank">
                                                            <iconify-icon icon="mdi:eye"></iconify-icon>
                                                        </a>
                                                        <form action="{{ route('admin.courses.modules.lessons.destroy', [$course->id, $module->id, $lesson->id]) }}" 
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Delete this lesson? This action cannot be undone.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                                <iconify-icon icon="mdi:trash"></iconify-icon>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <iconify-icon icon="mdi:play-box-outline" class="icon-3x text-muted mb-3"></iconify-icon>
                                <h6 class="text-muted">No lessons yet</h6>
                                <p class="text-muted small mb-3">Start adding lessons to this module</p>
                                <a href="{{ route('admin.courses.modules.lessons.create', [$course->id, $module->id]) }}" 
                                   class="btn btn-primary">
                                    Add First Lesson
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- ========== END LESSONS MANAGEMENT SECTION ========== -->


                <!-- Learning Objectives -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Learning Objectives</h6>
                    </div>
                    <div class="card-body">
                        <textarea id="learning_objectives" name="learning_objectives" class="form-control">
                            {!! $module->learning_objectives !!}
                        </textarea>
                    </div>
                </div>

                <!-- Topics Covered -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Topics Covered</h6>
                    </div>
                    <div class="card-body">
                        <textarea id="topics_Covered" name="topics_covered" class="form-control @error('topics_covered') is-invalid @enderror" 
                                  rows="5" placeholder="List the topics that will be covered">{{ old('topics_covered', $module->topics_covered) }}</textarea>
                        @error('topics_covered')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Key Concepts -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Key Concepts</h6>
                    </div>
                    <div class="card-body">
                        <textarea id="key_concepts" name="key_concepts" class="form-control @error('key_concepts') is-invalid @enderror" 
                                  rows="5" placeholder="List the key concepts students should understand">{{ old('key_concepts', $module->key_concepts) }}</textarea>
                        @error('key_concepts')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Case Study -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Case Study</h6>
                    </div>
                    <div class="card-body">
                        <textarea id="case_study" name="case_study" class="form-control @error('case_study') is-invalid @enderror" 
                                  rows="5" placeholder="Provide a relevant case study">{{ old('case_study', $module->case_study) }}</textarea>
                        @error('case_study')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Exercise -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Practical Exercise</h6>
                    </div>
                    <div class="card-body">
                        <textarea id="exercise" name="exercise" class="form-control @error('exercise') is-invalid @enderror" 
                                  rows="5" placeholder="Design a practical exercise for this module">{{ old('exercise', $module->exercise) }}</textarea>
                        @error('exercise')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Additional Notes</h6>
                    </div>
                    <div class="card-body">
                        <textarea id="additional_notes" name="additional_notes" class="form-control @error('additional_notes') is-invalid @enderror" 
                                  rows="5" placeholder="Additional information for instructors or students">{{ old('additional_notes', $module->additional_notes) }}</textarea>
                        @error('additional_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" 
                                           {{ old('is_active', $module->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Module
                                    </label>
                                    <p class="text-sm text-muted mb-0">Inactive modules are hidden from students</p>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border-top pt-3 mt-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                            Update Module
                                        </button>
                                        <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border-top pt-3 mt-3">
                                    <div class="d-grid gap-2">
                                        <!-- Toggle Active Status -->
                                        <form action="{{ route('admin.courses.modules.toggle-active', ['course' => $course->id, 'module' => $module->id]) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $module->is_active ? 'warning' : 'success' }} w-100">
                                                <iconify-icon icon="mdi:power"></iconify-icon>
                                                {{ $module->is_active ? 'Deactivate Module' : 'Activate Module' }}
                                            </button>
                                        </form>

                                        <!-- Duplicate Module -->
                                        <form action="{{ route('admin.courses.modules.duplicate', ['course' => $course->id, 'module' => $module->id]) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-info w-100" 
                                                    onclick="return confirm('Duplicate this module?')">
                                                <iconify-icon icon="mdi:content-copy"></iconify-icon>
                                                Duplicate Module
                                            </button>
                                        </form>

                                        <!-- Delete Module -->
                                        <form action="{{ route('admin.courses.modules.destroy', ['course' => $course->id, 'module' => $module->id]) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100" 
                                                    onclick="return confirm('Are you sure you want to delete this module? This action cannot be undone.')">
                                                <iconify-icon icon="mdi:trash"></iconify-icon>
                                                Delete Module
                                            </button>
                                        </form>
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
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-{{ $course->status === 'published' ? 'success' : ($course->status === 'draft' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module Statistics -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Module Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Module Number:</span>
                                <span class="fw-medium">{{ $module->module_number }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Estimated Hours:</span>
                                <span class="fw-medium">{{ $module->estimated_hours }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-{{ $module->is_active ? 'success' : 'secondary' }}">
                                    {{ $module->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Sort Order:</span>
                                <span class="fw-medium">{{ $module->sort_order }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Created:</span>
                                <span class="fw-medium">{{ $module->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Last Updated:</span>
                                <span class="fw-medium">{{ $module->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-sm btn-outline-primary">
                                <iconify-icon icon="mdi:arrow-left"></iconify-icon>
                                Back to Course
                            </a>
                            <a href="{{ route('admin.courses.modules.create', $course->id) }}" class="btn btn-sm btn-outline-success">
                                <iconify-icon icon="mdi:plus"></iconify-icon>
                                Add New Module
                            </a>
                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-outline-info">
                                <iconify-icon icon="mdi:pencil"></iconify-icon>
                                Edit Course
                            </a>
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  
  ClassicEditor
        .create(document.querySelector('#short_description'))
        .catch(error => console.error(error));
   ClassicEditor
        .create(document.querySelector('#full_content'))
        .catch(error => console.error(error));
    ClassicEditor
        .create(document.querySelector('#learning_objectives'))
        .catch(error => console.error(error));
    ClassicEditor
        .create(document.querySelector('#topics_Covered'))
        .catch(error => console.error(error));
    ClassicEditor
        .create(document.querySelector('#key_concepts'))
        .catch(error => console.error(error));
    ClassicEditor
        .create(document.querySelector('#case_study'))
        .catch(error => console.error(error));
    ClassicEditor
        .create(document.querySelector('#exercise'))
        .catch(error => console.error(error));
    ClassicEditor
        .create(document.querySelector('#additional_notes'))
        .catch(error => console.error(error));
                      
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
            const estimatedHoursInput = document.querySelector('input[name="estimated_hours"]');
            if (estimatedHoursInput) {
                const hours = parseInt(estimatedHoursInput.value) || 0;
                if (hours < 1 || hours > 100) {
                    estimatedHoursInput.setCustomValidity('Estimated hours must be between 1 and 100');
                    e.preventDefault();
                    estimatedHoursInput.reportValidity();
                    return;
                }
            }

            // Validate module number
            const moduleNumberInput = document.querySelector('input[name="module_number"]');
            if (moduleNumberInput) {
                const moduleNumber = parseInt(moduleNumberInput.value) || 0;
                if (moduleNumber < 1) {
                    moduleNumberInput.setCustomValidity('Module number must be at least 1');
                    e.preventDefault();
                    moduleNumberInput.reportValidity();
                    return;
                }
            }

            // Confirm before submitting
            if (!confirm('Are you sure you want to update this module?')) {
                e.preventDefault();
                return;
            }
        });
    }

    // Delete confirmation enhancement
    const deleteForm = document.querySelector('form[action*="destroy"]');
    if (deleteForm) {
        const deleteButton = deleteForm.querySelector('button[type="submit"]');
        if (deleteButton) {
            deleteButton.addEventListener('click', function(e) {
                if (!confirm('Are you absolutely sure you want to delete this module?\n\nThis action cannot be undone and will permanently delete:\n• All module content\n• Associated materials\n• Student progress data\n\nType "DELETE" to confirm:')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                
                const userInput = prompt('Please type "DELETE" to confirm deletion:');
                if (userInput !== 'DELETE') {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('Deletion cancelled. Module was not deleted.');
                    return false;
                }
            });
        }
    }

    // Auto-save draft feature (optional)
    let autoSaveTimeout;
    const autoSave = function() {
        const formData = new FormData(moduleForm);
        
        // Remove the _method field for auto-save
        formData.delete('_method');
        
        fetch('{{ route("admin.courses.modules.update", ["course" => $course->id, "module" => $module->id]) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Auto-saved successfully');
            }
        })
        .catch(error => {
            console.error('Auto-save error:', error);
        });
    };

    // Enable auto-save on form changes (every 30 seconds)
    const formInputs = moduleForm.querySelectorAll('input, textarea, select');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(autoSave, 30000); // 30 seconds
        });
    });

    // Show auto-save indicator
    const autoSaveIndicator = document.createElement('div');
    autoSaveIndicator.className = 'position-fixed bottom-0 end-0 m-3';
    autoSaveIndicator.innerHTML = `
        <div class="alert alert-info alert-dismissible fade" role="alert" id="autoSaveAlert">
            <div class="d-flex align-items-center">
                <iconify-icon icon="mdi:content-save" class="icon me-2"></iconify-icon>
                <span>Auto-saved successfully</span>
            </div>
        </div>
    `;
    document.body.appendChild(autoSaveIndicator);

    console.log('Module edit form initialized');
});
</script>
@endpush