@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create Diploma Project</h6>
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
            <li class="fw-medium">Create Diploma Project</li>
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

    <form action="{{ route('admin.assessments.store') }}" method="POST" enctype="multipart/form-data" id="diplomaForm">
        @csrf
        <input type="hidden" name="assessment_level" value="diploma">
        <input type="hidden" name="type" value="project">
        <input type="hidden" name="needs_manual_marking" value="1">
        <input type="hidden" name="requires_identity_verification" value="1">

        <div class="row gy-4">
            <!-- Left Column - Basic Info & Project Details -->
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
                                       value="{{ old('title') }}" placeholder="e.g., Diploma Project: GRC Implementation Case Study" required>
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
                        </div>
                    </div>
                </div>

                <!-- Project Brief Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Project Brief</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Project Description <span class="text-danger">*</span></label>
                            <textarea name="project_brief" class="form-control @error('project_brief') is-invalid @enderror" 
                                      rows="8" placeholder="Describe the project/case study requirements in detail. Include background information, scenario, and specific tasks." required>{{ old('project_brief') }}</textarea>
                            @error('project_brief') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Deliverables</label>
                            <textarea name="deliverables" class="form-control" rows="4" 
                                      placeholder="List expected deliverables (one per line)&#10;e.g.:&#10;- Written Report (20-25 pages)&#10;- Presentation Slides&#10;- Risk Assessment Matrix&#10;- Implementation Plan">{{ old('deliverables') }}</textarea>
                            <p class="text-sm text-muted mt-1">Enter each deliverable on a new line</p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Submission Guidelines</label>
                            <textarea name="description" class="form-control" rows="3" 
                                      placeholder="Format requirements, submission process, etc.">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Grading Rubric Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Grading Rubric</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-sm text-muted mb-3">Define the criteria for manual marking</p>
                        
                        <div id="rubric-container">
                            <!-- Rubric items will be added here -->
                        </div>
                        
                        <button type="button" class="btn btn-outline-primary mt-3" onclick="addRubricItem()">
                            <iconify-icon icon="ic:baseline-plus" class="icon me-1"></iconify-icon>
                            Add Rubric Criterion
                        </button>
                        
                        <input type="hidden" name="rubric" id="rubric-json">
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
                            <input type="number" name="weight" class="form-control" value="{{ old('weight', 100) }}" min="1" max="100">
                        </div>
                    </div>
                </div>

                <!-- Due Date Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Submission Deadline</h6>
                    </div>
                    <div class="card-body">
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

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="allow_late_submissions" id="allowLate" value="1">
                                <label class="form-check-label" for="allowLate">
                                    Allow Late Submissions
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="card mt-24 border-purple">
                    <div class="card-header bg-purple text-white">
                        <h6 class="card-title mb-0">Diploma Requirements</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <iconify-icon icon="solar:shield-check-bold" class="icon-2x text-purple"></iconify-icon>
                            <div>
                                <p class="fw-bold mb-1">Identity Verification Required</p>
                                <p class="text-sm text-muted mb-0">Students must verify identity</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <iconify-icon icon="solar:pen-2-bold" class="icon-2x text-purple"></iconify-icon>
                            <div>
                                <p class="fw-bold mb-1">Manual Marking</p>
                                <p class="text-sm text-muted mb-0">Instructors will grade submissions</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <iconify-icon icon="solar:medal-ribbon-bold" class="icon-2x text-purple"></iconify-icon>
                            <div>
                                <p class="fw-bold mb-1">Diploma Certification</p>
                                <p class="text-sm text-muted mb-0">Leads to diploma certification</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload Card -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Project Resources</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload Project Brief (PDF)</label>
                            <input type="file" name="assessment_file" class="form-control" accept=".pdf">
                            <p class="text-sm mt-1 text-muted">Upload detailed project brief as PDF</p>
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
                            <button type="submit" class="btn btn-purple px-5 text-white">Create Diploma Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Rubric Item Template -->
<template id="rubric-template">
    <div class="rubric-item card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5 mb-2">
                    <label class="form-label">Criteria</label>
                    <input type="text" class="form-control rubric-criteria" placeholder="e.g., Research Quality">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Points</label>
                    <input type="number" class="form-control rubric-points" placeholder="25" min="1">
                </div>
                <div class="col-md-5 mb-2">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control rubric-description" placeholder="Brief description">
                </div>
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRubricItem(this)">
                        <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                        Remove
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

@endsection

@push('styles')
<style>
    .bg-purple {
        background-color: #6f42c1 !important;
    }
    .btn-purple {
        background-color: #6f42c1;
        border-color: #6f42c1;
    }
    .btn-purple:hover {
        background-color: #5e34b1;
        border-color: #5e34b1;
    }
    .border-purple {
        border-color: #6f42c1 !important;
    }
    .text-purple {
        color: #6f42c1 !important;
    }
</style>
@endpush

@push('scripts')
<script>
let rubricItems = [];

function addRubricItem() {
    const container = document.getElementById('rubric-container');
    const template = document.getElementById('rubric-template');
    
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = template.innerHTML;
    const rubricElement = tempDiv.firstElementChild;
    
    container.appendChild(rubricElement);
    updateRubricJSON();
}

function removeRubricItem(button) {
    const rubricItem = button.closest('.rubric-item');
    rubricItem.remove();
    updateRubricJSON();
}

function updateRubricJSON() {
    const items = document.querySelectorAll('.rubric-item');
    const rubric = [];
    
    items.forEach(item => {
        const criteria = item.querySelector('.rubric-criteria').value;
        const points = item.querySelector('.rubric-points').value;
        const description = item.querySelector('.rubric-description').value;
        
        if (criteria && points) {
            rubric.push({
                criteria: criteria,
                points: parseInt(points),
                description: description
            });
        }
    });
    
    document.getElementById('rubric-json').value = JSON.stringify(rubric);
}

// Update JSON when inputs change
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('rubric-criteria') || 
        e.target.classList.contains('rubric-points') || 
        e.target.classList.contains('rubric-description')) {
        updateRubricJSON();
    }
});

// Add initial rubric items
document.addEventListener('DOMContentLoaded', function() {
    addRubricItem();
    addRubricItem();
    addRubricItem();
});

// Form validation
document.getElementById('diplomaForm').addEventListener('submit', function(e) {
    const projectBrief = document.querySelector('textarea[name="project_brief"]').value;
    
    if (!projectBrief.trim()) {
        e.preventDefault();
        alert('Please enter a project description.');
        return false;
    }
    
    // Update rubric JSON before submit
    updateRubricJSON();
    
    return true;
});
</script>
@endpush