@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Course Details: {{ $course->title }}</h6>
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
            <li class="fw-medium">{{ Str::limit($course->title, 30) }}</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row gy-4">
        <div class="col-lg-8">
            <!-- Course Overview -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Course Overview</h6>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-outline-primary">
                                <iconify-icon icon="mdi:pencil"></iconify-icon>
                                Edit
                            </a>
                            <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this course?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <iconify-icon icon="mdi:trash"></iconify-icon>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                @if($course->image)
                                    <img src="{{ asset($course->image) }}" alt="{{ $course->title }}" 
                                         class="rounded-8" style="width: 80px; height: 60px; object-fit: cover;">
                                @endif
                                <div>
                                    <h4 class="mb-1">{{ $course->title }}</h4>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary">{{ $course->code }}</span>
                                        <span class="badge bg-{{ $course->status == 'published' ? 'success' : ($course->status == 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($course->status) }}
                                        </span>
                                        @if($course->is_featured)
                                            <span class="badge bg-info">Featured</span>
                                        @endif
                                        @if($course->is_popular)
                                            <span class="badge bg-warning">Popular</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <h6 class="mb-2">Short Description</h6>
                            <p class="text-muted">{{ $course->short_description }}</p>
                        </div>

                        <div class="col-12">
                            <h6 class="mb-2">Full Description</h6>
                            <div class="course-description">
                                {!! nl2br(e($course->full_description)) !!}
                            </div>
                        </div>

                        <div class="col-12">
                            <h6 class="mb-3">Quick Stats</h6>
                            <div class="row g-3">
                                <div class="col-md-3 col-6">
                                    <div class="text-center p-3 border rounded-8">
                                        <div class="text-primary mb-2">
                                            <iconify-icon icon="mdi:book-open-page-variant" class="icon-2x"></iconify-icon>
                                        </div>
                                        <h5 class="mb-1">{{ $course->total_modules }}</h5>
                                        <small class="text-muted">Modules</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-center p-3 border rounded-8">
                                        <div class="text-success mb-2">
                                            <iconify-icon icon="mdi:clock-outline" class="icon-2x"></iconify-icon>
                                        </div>
                                        <h5 class="mb-1">{{ $course->total_hours }}</h5>
                                        <small class="text-muted">Hours</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-center p-3 border rounded-8">
                                        <div class="text-warning mb-2">
                                            <iconify-icon icon="mdi:school-outline" class="icon-2x"></iconify-icon>
                                        </div>
                                        <h6 class="mb-1">{{ ucfirst($course->level) }}</h6>
                                        <small class="text-muted">Level</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-center p-3 border rounded-8">
                                        <div class="text-info mb-2">
                                            <iconify-icon icon="mdi:certificate-outline" class="icon-2x"></iconify-icon>
                                        </div>
                                        <p class="mb-1 bold">{{ $course->format }}</p>
                                        <small class="text-muted">Format</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Modules -->
            <div class="card mt-24">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Course Modules ({{ $course->modules->count() }})</h6>
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('admin.courses.modules.create', $course->id) }}"
                            class="btn btn-primary d-flex align-items-center gap-1">
                                <iconify-icon icon="mdi:plus"></iconify-icon>
                                Add First Module
                            </a>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    @if($course->modules->count() > 0)
                        <div class="accordion" id="modulesAccordion">
                            @foreach($course->modules as $module)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $module->id }}">
                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $module->id }}" 
                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $module->id }}">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <div>
                                                    <span class="badge bg-primary me-2">Module {{ $module->module_number }}</span>
                                                    {{ $module->title }}
                                                </div>
                                                <div class="text-muted small">
                                                    {{ $module->estimated_hours }} hours
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $module->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                                         aria-labelledby="heading{{ $module->id }}" data-bs-parent="#modulesAccordion">
                                        <div class="accordion-body">
                                            <div class="mb-3">
                                                <strong>Description:</strong>
                                                <p class="mb-2">{{ $module->short_description }}</p>
                                            </div>
                                            
                                            @if($module->learning_objectives)
                                                <div class="mb-3">
                                                    <strong>Learning Objectives:</strong>
                                                    <div class="ms-3">
                                                        {!! nl2br(e($module->learning_objectives)) !!}
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($module->topics_covered)
                                                <div class="mb-3">
                                                    <strong>Topics Covered:</strong>
                                                    <div class="ms-3">
                                                        {!! nl2br(e($module->topics_covered)) !!}
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($module->key_concepts)
                                                <div class="mb-3">
                                                    <strong>Key Concepts:</strong>
                                                    <div class="ms-3">
                                                        {!! nl2br(e($module->key_concepts)) !!}
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($module->case_study)
                                                <div class="mb-3">
                                                    <strong>Case Study:</strong>
                                                    <div class="ms-3">
                                                        {!! nl2br(e($module->case_study)) !!}
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($module->exercise)
                                                <div class="mb-3">
                                                    <strong>Exercise:</strong>
                                                    <div class="ms-3">
                                                        {!! nl2br(e($module->exercise)) !!}
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($module->additional_notes)
                                                <div class="mb-3">
                                                    <strong>Additional Notes:</strong>
                                                    <div class="ms-3">
                                                        {!! nl2br(e($module->additional_notes)) !!}
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="d-flex justify-content-end gap-2 mt-3">
                                                <a href="#" class="btn btn-sm btn-outline-primary">
                                                    <iconify-icon icon="mdi:pencil"></iconify-icon>
                                                    Edit
                                                </a>
                                                <form action="#" method="POST" onsubmit="return confirm('Delete this module?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <iconify-icon icon="mdi:trash"></iconify-icon>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <iconify-icon icon="mdi:book-education-outline" class="icon-4x text-muted mb-3"></iconify-icon>
                            <h5 class="text-muted">No modules added yet</h5>
                            <p class="text-muted mb-4">Start by adding your first module</p>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('admin.courses.modules.create', $course->id) }}"
                                class="btn btn-primary d-flex align-items-center gap-1">
                                    <iconify-icon icon="mdi:plus"></iconify-icon>
                                    Add First Module
                                </a>
                            </div>

                        </div>
                    @endif
                </div>
            </div>

            <!-- Course Materials -->
            <div class="card mt-24">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Course Materials ({{ $course->materials->count() }})</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadMaterialModal">
                            <iconify-icon icon="mdi:upload"></iconify-icon>
                            Upload Materials
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($course->materials->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Downloads</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($course->materials as $material)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @switch($material->file_type)
                                                        @case('pdf')
                                                            <iconify-icon icon="mdi:file-pdf-box" class="icon text-danger"></iconify-icon>
                                                            @break
                                                        @case('doc')
                                                        @case('docx')
                                                            <iconify-icon icon="mdi:file-word-box" class="icon text-primary"></iconify-icon>
                                                            @break
                                                        @case('ppt')
                                                        @case('pptx')
                                                            <iconify-icon icon="mdi:file-powerpoint-box" class="icon text-warning"></iconify-icon>
                                                            @break
                                                        @case('xls')
                                                        @case('xlsx')
                                                            <iconify-icon icon="mdi:file-excel-box" class="icon text-success"></iconify-icon>
                                                            @break
                                                        @case('zip')
                                                        @case('rar')
                                                            <iconify-icon icon="mdi:folder-zip" class="icon text-secondary"></iconify-icon>
                                                            @break
                                                        @default
                                                            <iconify-icon icon="mdi:file-document" class="icon text-muted"></iconify-icon>
                                                    @endswitch
                                                    <span>{{ $material->title }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ ucfirst($material->material_type) }}</span>
                                            </td>
                                            <td>{{ $material->formatted_size }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $material->download_count }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ $material->file_url }}" target="_blank" 
                                                       class="btn btn-sm btn-outline-primary" title="View">
                                                        <iconify-icon icon="mdi:eye"></iconify-icon>
                                                    </a>
                                                    <a href="{{ $material->file_url }}" download 
                                                       class="btn btn-sm btn-outline-success" title="Download">
                                                        <iconify-icon icon="mdi:download"></iconify-icon>
                                                    </a>
                                                    <form action="#" method="POST" 
                                                          onsubmit="return confirm('Delete this material?')">
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
                        <div class="text-center py-5">
                            <iconify-icon icon="mdi:file-document-outline" class="icon-4x text-muted mb-3"></iconify-icon>
                            <h5 class="text-muted">No materials uploaded yet</h5>
                            <p class="text-muted mb-4">Upload course materials like PDFs, presentations, and worksheets</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadMaterialModal">
                                <iconify-icon icon="mdi:upload"></iconify-icon>
                                Upload Materials
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detailed Information -->
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Detailed Information</h6>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        @if($course->programme_overview)
                            <div class="col-12">
                                <h6 class="mb-2">Programme Overview</h6>
                                <div class="bg-light p-3 rounded-8">
                                    {!! nl2br(e($course->programme_overview)) !!}
                                </div>
                            </div>
                        @endif

                        @if($course->programme_architecture)
                            <div class="col-12">
                                <h6 class="mb-2">Programme Architecture</h6>
                                <div class="bg-light p-3 rounded-8">
                                    {!! ($course->programme_architecture) !!}
                                </div>
                            </div>
                        @endif

                        @if($course->learning_outcomes)
                            <div class="col-12">
                                <h6 class="mb-2">Learning Outcomes</h6>
                                <div class="bg-light p-3 rounded-8">
                                    {!! nl2br(e($course->learning_outcomes)) !!}
                                </div>
                            </div>
                        @endif

                        @if($course->target_audience)
                            <div class="col-12">
                                <h6 class="mb-2">Target Audience</h6>
                                <div class="bg-light p-3 rounded-8">
                                    {!! nl2br(e(implode("\n", $course->target_audience))) !!}
                                </div>
                            </div>
                        @endif

                        @if($course->prerequisites)
                            <div class="col-12">
                                <h6 class="mb-2">Prerequisites</h6>
                                <div class="bg-light p-3 rounded-8">
                                    {!! nl2br(e($course->prerequisites)) !!}
                                </div>
                            </div>
                        @endif

                        @if($course->career_pathways)
                            <div class="col-12">
                                <h6 class="mb-2">Career Pathways</h6>
                                <div class="bg-light p-3 rounded-8">
                                    {!! nl2br(e($course->career_pathways)) !!}
                                </div>
                            </div>
                        @endif

                        @if($course->assessment_structure)
                            <div class="col-12">
                                <h6 class="mb-2">Assessment Structure</h6>
                                <div class="bg-light p-3 rounded-8">
                                    {!! nl2br(e($course->assessment_structure)) !!}
                                </div>
                            </div>
                        @endif

                        @if($course->code_of_conduct)
                            <div class="col-12">
                                <h6 class="mb-2">Code of Professional Conduct</h6>
                                <div class="bg-light p-3 rounded-8">
                                    {!! nl2br(e($course->code_of_conduct)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Course Banner & Video -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Media</h6>
                </div>
                <div class="card-body">
                    @if($course->banner_image)
                        <div class="mb-4">
                            <h6 class="mb-2">Banner Image</h6>
                            <img src="{{ asset($course->banner_image) }}" alt="Course banner" 
                                 class="img-fluid rounded-8 border" style="max-height: 150px;">
                        </div>
                    @endif

                    @if($course->hasVideo())
                        <div>
                            <h6 class="mb-2">Course Video</h6>
                            <div class="video-container">
                                @if($course->video_type === 'upload' && $course->video)
                                    <video controls class="w-100 rounded-8" style="max-height: 200px;">
                                        <source src="{{ asset($course->video) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @elseif(in_array($course->video_type, ['youtube', 'vimeo']) && $course->video_embed_url)
                                    <div class="ratio ratio-16x9">
                                        <iframe src="{{ $course->video_embed_url }}" 
                                                frameborder="0" 
                                                allowfullscreen
                                                class="rounded-8"></iframe>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    @if($course->video_type === 'upload')
                                        Uploaded Video
                                    @elseif($course->video_type === 'youtube')
                                        YouTube Video
                                    @elseif($course->video_type === 'vimeo')
                                        Vimeo Video
                                    @endif
                                </small>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <iconify-icon icon="mdi:video-off-outline" class="icon-3x mb-2"></iconify-icon>
                            <p class="mb-0">No video added</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Course Information -->
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Course Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Course Code:</span>
                            <span class="fw-medium">{{ $course->code }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Short Title:</span>
                            <span class="fw-medium">{{ $course->short_title }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Level:</span>
                            <span class="badge bg-{{ $course->level === 'beginner' ? 'info' : ($course->level === 'intermediate' ? 'primary' : ($course->level === 'advanced' ? 'warning' : 'danger')) }}">
                                {{ ucfirst($course->level) }}
                            </span>
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
                            <span class="text-muted">Modules:</span>
                            <span class="fw-medium">{{ $course->total_modules }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Hours:</span>
                            <span class="fw-medium">{{ $course->total_hours }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Certification:</span>
                            <span class="fw-medium">{{ $course->certification_name }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Certifying Body:</span>
                            <span class="fw-medium">{{ $course->certifying_body }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Sort Order:</span>
                            <span class="fw-medium">{{ $course->sort_order }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Created:</span>
                            <span class="fw-medium">{{ $course->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Last Updated:</span>
                            <span class="fw-medium">{{ $course->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Pricing Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Regular Price:</span>
                            @if($course->price > 0)
                                <span class="fw-medium h5 mb-0">${{ number_format($course->price, 2) }}</span>
                            @else
                                <span class="badge bg-success">Free</span>
                            @endif
                        </div>
                        
                        @if($course->discount_price > 0)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Discount Price:</span>
                                <span class="fw-medium h5 text-danger mb-0">${{ number_format($course->discount_price, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">You Save:</span>
                                <span class="fw-medium text-success">{{ $course->discount_percentage }}%</span>
                            </div>
                        @endif
                        
                        <div class="alert alert-info mt-2">
                            <iconify-icon icon="mdi:information" class="icon me-2"></iconify-icon>
                            <small>
                                @if($course->price == 0)
                                    This course is free for all students.
                                @elseif($course->discount_price > 0)
                                    Special discount available. Save {{ $course->discount_percentage }}%.
                                @else
                                    Regular pricing applies.
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status & Actions -->
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">Status & Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <label class="form-label mb-2">Current Status</label>
                            <form action="{{ route('admin.courses.status', $course->id) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <select name="status" class="form-select" onchange="this.form.submit()">
                                        <option value="draft" {{ $course->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ $course->status == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="archived" {{ $course->status == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                </div>
                            </form>
                        </div>

                        <div class="border-top pt-3">
                            <div class="form-check">
                                <form action="{{ route('admin.courses.toggle-featured', $course->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input class="form-check-input" type="checkbox" id="featuredCheck" 
                                           {{ $course->is_featured ? 'checked' : '' }} 
                                           onchange="this.form.submit()">
                                    <label class="form-check-label" for="featuredCheck">
                                        Featured Course
                                    </label>
                                </form>
                            </div>
                        </div>

                        <div>
                            <div class="form-check">
                                <form action="{{ route('admin.courses.toggle-popular', $course->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input class="form-check-input" type="checkbox" id="popularCheck" 
                                           {{ $course->is_popular ? 'checked' : '' }} 
                                           onchange="this.form.submit()">
                                    <label class="form-check-label" for="popularCheck">
                                        Popular Course
                                    </label>
                                </form>
                            </div>
                        </div>

                        <div class="border-top pt-3">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-primary">
                                    <iconify-icon icon="mdi:pencil"></iconify-icon>
                                    Edit Course
                                </a>
                                <a href="#" class="btn btn-outline-secondary" onclick="window.print()">
                                    <iconify-icon icon="mdi:printer"></iconify-icon>
                                    Print Details
                                </a>
                                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                                    <iconify-icon icon="mdi:arrow-left"></iconify-icon>
                                    Back to Courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="card mt-24">
                <div class="card-header">
                    <h6 class="card-title mb-0">SEO Information</h6>
                </div>
                <div class="card-body">
                    @if($course->meta_description || $course->meta_keywords)
                        @if($course->meta_description)
                            <div class="mb-3">
                                <label class="form-label small text-muted">Meta Description</label>
                                <p class="mb-0 small">{{ $course->meta_description }}</p>
                                <div class="text-end">
                                    <small class="text-muted">{{ strlen($course->meta_description) }}/160 characters</small>
                                </div>
                            </div>
                        @endif

                        @if($course->meta_keywords)
                            <div>
                                <label class="form-label small text-muted">Meta Keywords</label>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach(explode(',', $course->meta_keywords) as $keyword)
                                        <span class="badge bg-light text-dark">{{ trim($keyword) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-3">
                            <iconify-icon icon="mdi:search-web" class="icon-3x mb-2"></iconify-icon>
                            <p class="mb-0">No SEO information added</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Material Modal -->
<div class="modal fade" id="uploadMaterialModal" tabindex="-1" aria-labelledby="uploadMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.courses.materials.upload', $course->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadMaterialModalLabel">Upload Course Materials</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Files</label>
                        <input type="file" name="materials[]" class="form-control" multiple 
                               accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar" required>
                        <small class="text-muted">You can select multiple files. Max 10MB each.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Material Type</label>
                        <select name="material_type" class="form-select" required>
                            <option value="manual">Course Manual</option>
                            <option value="presentation">Presentation</option>
                            <option value="worksheet">Worksheet</option>
                            <option value="template">Template</option>
                            <option value="reference">Reference Material</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Assign to Module (Optional)</label>
                        <select name="module_id" class="form-select">
                            <option value="">-- None (General Course Material) --</option>
                            @foreach($course->modules as $module)
                                <option value="{{ $module->id }}">Module {{ $module->module_number }}: {{ $module->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_downloadable" id="is_downloadable" value="1" checked>
                        <label class="form-check-label" for="is_downloadable">
                            Allow students to download
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Materials</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .course-description {
        line-height: 1.8;
        color: #495057;
    }
    
    .accordion-button {
        font-weight: 500;
    }
    
    .accordion-body {
        background-color: #f8f9fa;
        border-top: 1px solid rgba(0,0,0,.125);
    }
    
    .video-container {
        border-radius: 8px;
        overflow: hidden;
        background: #000;
    }
    
    .video-container video,
    .video-container iframe {
        display: block;
    }
    
    .badge {
        font-size: 0.75em;
        padding: 0.35em 0.65em;
    }
    
    .table th {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.875rem;
        text-transform: uppercase;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .icon {
        font-size: 1.25rem;
    }
    
    .icon-2x {
        font-size: 2rem;
    }
    
    .icon-3x {
        font-size: 3rem;
    }
    
    .icon-4x {
        font-size: 4rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-refresh page when status changes (optional)
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            // Show loading indicator
            const submitBtn = this.closest('form').querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<iconify-icon icon="mdi:loading" class="spin"></iconify-icon> Updating...';
                submitBtn.disabled = true;
            }
        });
    }
    
    // Add spinning animation for loading icon
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .spin {
            animation: spin 1s linear infinite;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush