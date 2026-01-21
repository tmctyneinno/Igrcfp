@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Edit Course: {{ $course->title }}</h6>
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
                <a href="{{ route('admin.courses.show', $course->slug) }}" class="hover-text-primary">{{ Str::limit($course->title, 20) }}</a>
            </li>
            <li>-</li>
            <li class="fw-medium">Edit</li>
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

    <form action="{{ route('admin.courses.update', $course->slug) }}" method="POST" enctype="multipart/form-data" id="courseForm">
        @csrf
        @method('PUT') 
        <div class="row gy-4">
            <div class="col-lg-8">
                <!-- Course Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Course Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Course Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       placeholder="Enter course title" value="{{ old('title', $course->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
{{-- 
                            <div class="col-md-6">
                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                       placeholder="e.g., CGFCS" value="{{ old('code', $course->code) }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <div class="col-md-12">
                                <label class="form-label">Short Title <span class="text-danger">*</span></label>
                                <input type="text" name="short_title" class="form-control rich-editor @error('short_title') is-invalid @enderror" 
                                       placeholder="e.g., Certified GRC & Financial Crime Specialist" value="{{ old('short_title', $course->short_title) }}" required>
                                @error('short_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                <textarea name="short_description" class="form-control rich-editor @error('short_description') is-invalid @enderror" 
                                          rows="3" placeholder="Brief description of the course (max 500 characters)" required maxlength="500">{{ old('short_description', $course->short_description) }}</textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Maximum 500 characters</small>
                                    <small class="character-count" data-target="short_description">0/500</small>
                                </div>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Full Description <span class="text-danger">*</span></label>
                                <textarea id="full_description" name="full_description" class="form-control rich-editor @error('full_description') is-invalid @enderror" 
                                          rows="8" placeholder="Detailed description of the course...">{{ old('full_description', $course->full_description) }}</textarea>
                                @error('full_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Course Image</label>
                                <div class="file-upload-container">
                                    <input class="form-control @error('image') is-invalid @enderror" 
                                           type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    <p class="text-sm mt-1 mb-0 text-muted">
                                        Recommended size: 400x300px. Supported formats: JPG, PNG, GIF, WEBP. Max size: 2MB
                                        @if($course->image)
                                            <br><span class="text-success">Current image is set</span>
                                        @endif
                                    </p>
                                </div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Banner Image</label>
                                <div class="file-upload-container">
                                    <input class="form-control @error('banner_image') is-invalid @enderror" 
                                           type="file" name="banner_image" id="bannerImageInput" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    <p class="text-sm mt-1 mb-0 text-muted">
                                        Recommended size: 1200x400px. Max size: 5MB
                                        @if($course->banner_image)
                                            <br><span class="text-success">Current banner is set</span>
                                        @endif
                                    </p>
                                </div>
                                @error('banner_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Upload Section -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Course Video</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <iconify-icon icon="mdi:information" class="icon me-2"></iconify-icon>
                            <strong>Upload Limits:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Maximum video size: 20MB</li>
                                <li>Allowed formats: MP4, MOV, AVI, WMV, MKV</li>
                                <li>Server limit: {{ ini_get('upload_max_filesize') }}</li>
                            </ul>
                        </div>
                        
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Video Type</label>
                                <select name="video_type" class="form-select @error('video_type') is-invalid @enderror" id="videoTypeSelect">
                                    <option value="none" {{ old('video_type', $course->video_type) == 'none' ? 'selected' : '' }}>No Video</option>
                                    <option value="upload" {{ old('video_type', $course->video_type) == 'upload' ? 'selected' : '' }}>Upload Video</option>
                                    <option value="youtube" {{ old('video_type', $course->video_type) == 'youtube' ? 'selected' : '' }}>YouTube Video</option>
                                    <option value="vimeo" {{ old('video_type', $course->video_type) == 'vimeo' ? 'selected' : '' }}>Vimeo Video</option>
                                </select>
                                @error('video_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Upload Video Field -->
                            <div class="col-12 video-upload-field" style="display: {{ in_array(old('video_type', $course->video_type), ['upload']) ? 'block' : 'none' }};">
                                <label class="form-label">Upload Video File</label>
                                <input class="form-control @error('video') is-invalid @enderror" 
                                    type="file" name="video" id="videoFileInput" accept="video/mp4,video/mov,video/avi,video/wmv,video/mkv">
                                <p class="text-sm mt-1 mb-0 text-muted">
                                    Supported formats: MP4, MOV, AVI, WMV, MKV. Max size: 20MB
                                    @if($course->video_type == 'upload' && $course->video)
                                        <br><span class="text-success">Current video is set</span>
                                    @endif
                                </p>
                                @error('video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- YouTube/Vimeo URL Field -->
                            <div class="col-12 video-url-field" style="display: {{ in_array(old('video_type', $course->video_type), ['youtube', 'vimeo']) ? 'block' : 'none' }};">
                                <label class="form-label">Video URL</label>
                                <input type="url" name="video_url" id="videoUrlInput" class="form-control @error('video_url') is-invalid @enderror" 
                                    placeholder="https://www.youtube.com/watch?v=..." value="{{ old('video_url', $course->video_url) }}">
                                <p class="text-sm mt-1 mb-0 text-muted" id="videoUrlHelp">
                                    Enter the full YouTube or Vimeo video URL
                                </p>
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Video Preview -->
                            <div class="col-12">
                                <div id="videoPreview" class="mt-3" style="display: {{ $course->hasVideo() ? 'block' : 'none' }};">
                                    <div class="video-preview-container">
                                        <div id="videoPlayer">
                                            @if($course->hasVideo())
                                                @if($course->video_type == 'upload' && $course->video)
                                                    <video controls style="width: 100%; max-height: 200px; border-radius: 8px;">
                                                        <source src="{{ asset($course->video) }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @elseif(in_array($course->video_type, ['youtube', 'vimeo']) && $course->video_embed_url)
                                                    <iframe src="{{ $course->video_embed_url }}" 
                                                            width="100%" 
                                                            height="100%" 
                                                            frameborder="0" 
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                            allowfullscreen 
                                                            style="border-radius: 8px;">
                                                    </iframe>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Details -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Course Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Level <span class="text-danger">*</span></label>
                                <select name="level" class="form-select @error('level') is-invalid @enderror" required>
                                    <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                    <option value="expert" {{ old('level', $course->level) == 'expert' ? 'selected' : '' }}>Expert</option>
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Format <span class="text-danger">*</span></label>
                                <select name="format" class="form-select @error('format') is-invalid @enderror" required>
                                    <option value="self_paced" {{ old('format', $course->format) == 'self_paced' ? 'selected' : '' }}>Self-Paced</option>
                                    <option value="instructor_led" {{ old('format', $course->format) == 'instructor_led' ? 'selected' : '' }}>Instructor-Led</option>
                                    <option value="hybrid" {{ old('format', $course->format) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                                @error('format')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Duration <span class="text-danger">*</span></label>
                                <input type="text" name="duration" class="form-control @error('duration') is-invalid @enderror" 
                                       placeholder="e.g., 6 weeks, 40 hours" value="{{ old('duration', $course->duration) }}" required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Total Modules <span class="text-danger">*</span></label>
                                <input type="number" name="total_modules" id="totalModules" class="form-control @error('total_modules') is-invalid @enderror" 
                                       placeholder="10" value="{{ old('total_modules', $course->total_modules) }}" min="1" required>
                                @error('total_modules')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Total Hours <span class="text-danger">*</span></label>
                                <input type="number" name="total_hours" id="totalHours" class="form-control @error('total_hours') is-invalid @enderror" 
                                       placeholder="40" value="{{ old('total_hours', $course->total_hours) }}" min="1" required>
                                @error('total_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Certification Name <span class="text-danger">*</span></label>
                                <input type="text" name="certification_name" class="form-control @error('certification_name') is-invalid @enderror" 
                                       placeholder="e.g., Certified GRC & Financial Crime Specialist" value="{{ old('certification_name', $course->certification_name) }}" required>
                                @error('certification_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Certifying Body <span class="text-danger">*</span></label>
                                <input type="text" name="certifying_body" class="form-control @error('certifying_body') is-invalid @enderror" 
                                       placeholder="e.g., Institute of GRC and Financial Crime Prevention (IGRCFP)" value="{{ old('certifying_body', $course->certifying_body) }}" required>
                                @error('certifying_body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Regular Price ($)</label>
                                <input type="number" name="price" id="priceInput" class="form-control @error('price') is-invalid @enderror" 
                                       placeholder="0.00" value="{{ old('price', $course->price) }}" step="0.01" min="0">
                                <p class="text-sm mt-1 mb-0 text-muted">Leave as 0 for free courses</p>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Discount Price ($)</label>
                                <input type="number" name="discount_price" id="discountPrice" class="form-control @error('discount_price') is-invalid @enderror" 
                                       placeholder="0.00" value="{{ old('discount_price', $course->discount_price) }}" step="0.01" min="0">
                                <p class="text-sm mt-1 mb-0 text-muted">Leave as 0 for no discount</p>
                                @error('discount_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Course Information -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Detailed Course Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Programme Overview</label>
                                <textarea id="programme_overview" name="programme_overview" class="form-control rich-editor @error('programme_overview') is-invalid @enderror" rows="6" 
                                          placeholder="Detailed programme overview...">{{ old('programme_overview', $course->programme_overview) }}</textarea>
                                @error('programme_overview')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Programme Architecture</label>
                                <textarea id="programme_architecture" name="programme_architecture" class="form-control rich-editor @error('programme_architecture') is-invalid @enderror" rows="6" 
                                          placeholder="Describe the programme tiers and structure...">{{ old('programme_architecture', $course->programme_architecture) }}</textarea>
                                @error('programme_architecture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Learning Outcomes (one per line) <span class="text-danger">*</span></label>
                                <textarea id="learning_outcomes" name="learning_outcomes" class="form-control rich-editor  @error('learning_outcomes') is-invalid @enderror" rows="8" 
                                           required>{{ old('learning_outcomes', $course->learning_outcomes) }}</textarea>
                                @error('learning_outcomes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Target Audience (one per line) <span class="text-danger">*</span></label>
                                <textarea name="target_audience"
                                    class="form-control rich-editor @error('target_audience') is-invalid @enderror"
                                    rows="5"
                                    placeholder=""
                                    required>{{ old('target_audience', is_array($course->target_audience) ? implode("\n", $course->target_audience) : $course->target_audience) }}</textarea>

                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Prerequisites</label>
                                <textarea id="prerequisites" name="prerequisites" class="form-control rich-editor @error('prerequisites') is-invalid @enderror" 
                                          rows="3" placeholder="Requirements before taking this course">{{ old('prerequisites', $course->prerequisites) }}</textarea>
                                @error('prerequisites')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Career Pathways (one per line)</label>
                                <textarea id="career_pathways" name="career_pathways" class="form-control rich-editor @error('career_pathways') is-invalid @enderror" rows="4" 
                                          >{{ old('career_pathways', $course->career_pathways) }}</textarea>
                                @error('career_pathways')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Assessment Structure (one per line)</label>
                                <textarea id="assessment_structure" name="assessment_structure" class="form-control rich-editor @error('assessment_structure') is-invalid @enderror" rows="4" 
                                          placeholder="Module quizzes
Practical assignments
Final examination">{{ old('assessment_structure', $course->assessment_structure) }}</textarea>
                                @error('assessment_structure')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Code of Professional Conduct (one per line)</label>
                                <textarea id="code_of_conduct" name="code_of_conduct" class="form-control rich-editor @error('code_of_conduct') is-invalid @enderror" rows="4" 
                                          >{!!  $course->code_of_conduct !!}</textarea>
                                @error('code_of_conduct')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Modules Upload -->
                <div class="card mt-24">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">Course Modules</h6>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.courses.modules.create', $course->slug) }}" class="btn btn-sm btn-primary">
                                    Add Module
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkUpdateModal">
                                    Bulk Update
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($course->modules->count() > 0)
                            <div class="alert alert-info">
                                <iconify-icon icon="mdi:information" class="icon me-2"></iconify-icon>
                                <strong>Existing Modules:</strong> {{ $course->modules->count() }} modules found. Use bulk update to replace or add to existing modules.
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Current Modules Preview</label>
                                <div class="border rounded-8 p-3" style="max-height: 200px; overflow-y: auto;">
                                    @foreach($course->modules->sortBy('module_number') as $module)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="badge bg-primary me-2">Module {{ $module->module_number }}</span>
                                                <strong>{{ $module->title }}</strong>
                                            </div> 
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.courses.modules.edit', ['course' => $course->slug, 'module' => $module->id]) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <iconify-icon icon="mdi:pencil"></iconify-icon>
                                                </a>
                                                <form action="{{ route('admin.courses.modules.destroy', ['course' => $course->slug, 'module' => $module->id]) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Delete this module?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <iconify-icon icon="mdi:trash"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <div class="alert alert-warning">
                            <iconify-icon icon="mdi:alert-circle" class="icon me-2"></iconify-icon>
                            <strong>Note:</strong> Bulk update will process the formatted text below. Make sure to follow the format instructions.
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Bulk Module Update Content</label>
                            <textarea name="bulk_modules" class="form-control rich-editor @error('bulk_modules') is-invalid @enderror" rows="15" 
                                      >{{ old('bulk_modules') }}</textarea>
                            @error('bulk_modules')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-text">
                            <strong>Format Instructions:</strong>
                            <ul class="mb-2">
                                <li>Use the format: "Module X: Title" on a new line</li>
                                <li>Follow with module description</li>
                                <li>Add sections like "Objectives:", "Topics:", "Case Study:", "Exercise:"</li>
                                <li>Separate modules with a blank line</li>
                                <li>Existing modules with same numbers will be updated</li>
                            </ul>
                            <strong>Quick Format Tips:</strong>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <span class="badge bg-light text-dark">Module X: Title</span>
                                <span class="badge bg-light text-dark">Objectives:</span>
                                <span class="badge bg-light text-dark">Topics:</span>
                                <span class="badge bg-light text-dark">Case Study:</span>
                                <span class="badge bg-light text-dark">Exercise:</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Materials Management -->
                <div class="card mt-24">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">Course Materials ({{ $course->materials->count() }})</h6>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadMaterialModal">
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
                                                              onsubmit="return confirm('Delete this material?')" class="d-inline">
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
                                <iconify-icon icon="mdi:file-document-outline" class="icon-3x text-muted mb-3"></iconify-icon>
                                <h6 class="text-muted">No materials uploaded yet</h6>
                                <p class="text-muted small mb-0">Upload course materials like PDFs, presentations, and worksheets</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">SEO Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" id="metaDescription" class="form-control rich-editor @error('meta_description') is-invalid @enderror" 
                                          rows="3" placeholder="Brief description for search engines (max 160 characters)" maxlength="160">{{ old('meta_description', $course->meta_description) }}</textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Recommended: 150-160 characters</small>
                                    <small class="character-count" data-target="meta_description">0/160</small>
                                </div>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Meta Keywords</label>
                                    <textarea name="meta_keywords" id="meta_keywords" class="form-control rich-editor @error('meta_keywords') is-invalid @enderror" 
                                          rows="3"  maxlength="160">{{ old('meta_keywords', $course->meta_keywords) }}</textarea>

                                <p class="text-sm mt-1 mb-0 text-muted">Separate keywords with commas</p>
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Course Settings -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Course Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status', $course->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                       placeholder="0" value="{{ old('sort_order', $course->sort_order) }}">
                                <p class="text-sm mt-1 mb-0 text-muted">Lower numbers appear first</p>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $course->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Feature this course
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular', $course->is_popular) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_popular">
                                        Mark as popular course
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border-top pt-3 mt-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                            Update Course
                                        </button>
                                        <a href="{{ route('admin.courses.show', $course->slug) }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Preview -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Current Images</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="mb-3">Course Image</h6>
                            @if($course->image)
                                <div id="currentImagePreview" class="mb-3">
                                    <img src="{{ asset('storage/' . $course->image) }}" alt="Current course image" 
                                         class="img-fluid rounded-8 border" style="max-height: 150px;">
                                </div> 
                                <small class="text-muted d-block mb-3">Current image</small>
                            @else   
                                <div id="noImagePlaceholder" class="text-muted py-2">
                                    <iconify-icon icon="mdi:image-outline" class="icon-2x mb-2"></iconify-icon>
                                    <p class="mb-0 small">No image set</p>
                                </div>
                            @endif
                            <div id="imagePreview" class="mb-3" style="display: none;">
                                <img id="previewImage" src="#" alt="New image preview" 
                                     class="img-fluid rounded-8 border" style="max-height: 150px;">
                                <small class="text-muted d-block mt-1">New image preview</small>
                            </div>
                        </div>  
                        
                        <hr class="my-3">
                        
                        <div class="text-center">
                            <h6 class="mb-3">Banner Image</h6>
                            @if($course->banner_image) 
                                <div id="currentBannerPreview" class="mb-3">
                                    <img src="{{ asset('storage/' . $course->banner_image);  }}" alt="Current banner image" 
                                         class="img-fluid rounded-8 border" style="max-height: 100px;">
                                </div>
                                <small class="text-muted d-block mb-3">Current banner</small>
                            @else
                                <div id="noBannerImagePlaceholder" class="text-muted py-2">
                                    <iconify-icon icon="mdi:image-outline" class="icon-2x mb-2"></iconify-icon>
                                    <p class="mb-0 small">No banner set</p>
                                </div>
                            @endif
                            <div id="bannerImagePreview" class="mb-3" style="display: none;">
                                <img id="previewBannerImage" src="#" alt="New banner preview" 
                                     class="img-fluid rounded-8 border" style="max-height: 100px;">
                                <small class="text-muted d-block mt-1">New banner preview</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module Stats -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Module Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="mb-3">
                                <iconify-icon icon="mdi:book-education" class="icon-3x text-primary"></iconify-icon>
                            </div>
                            <h6 id="moduleCountPreview">Total Modules: {{ $course->modules->count() }}</h6>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ min(100, ($course->modules->count() / $course->total_modules) * 100) }}%; background-color: #0A1F44;" 
                                     aria-valuenow="{{ $course->modules->count() }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $course->total_modules }}"></div>
                            </div>
                            <p class="text-sm text-muted mb-0">{{ $course->modules->count() }} of {{ $course->total_modules }} modules configured</p>
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
                            <a href="{{ route('admin.courses.show', $course->slug) }}" class="btn btn-outline-primary">
                                <iconify-icon icon="mdi:eye"></iconify-icon>
                                View Course
                            </a>
                            <a href="{{ route('admin.courses.modules.create', $course->slug) }}" class="btn btn-outline-success">
                                <iconify-icon icon="mdi:plus"></iconify-icon>
                                Add New Module
                            </a>
                            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#uploadMaterialModal">
                                <iconify-icon icon="mdi:upload"></iconify-icon>
                                Upload Materials
                            </button>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                                <iconify-icon icon="mdi:arrow-left"></iconify-icon>
                                Back to Courses
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Course Stats -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Course Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Modules:</span>
                                <span class="fw-medium" id="statsModules">{{ $course->modules->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Materials:</span>
                                <span class="fw-medium">{{ $course->materials->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Hours:</span>
                                <span class="fw-medium" id="statsHours">{{ $course->total_hours }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Level:</span>
                                <span class="fw-medium" id="statsLevel">{{ ucfirst($course->level) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Format:</span>
                                <span class="fw-medium" id="statsFormat">{{ ucfirst(str_replace('_', ' ', $course->format)) }}</span>
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

                <!-- Pricing Preview -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Pricing Preview</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            @if($course->price > 0)
                                @if($course->discount_price > 0)
                                    <div class="mb-2">
                                        <span class="text-decoration-line-through text-muted">${{ number_format($course->price, 2) }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <h4 class="text-danger mb-0">${{ number_format($course->discount_price, 2) }}</h4>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-success">Save {{ $course->discount_percentage }}%</span>
                                    </div>
                                @else
                                    <h4 class="mb-0">${{ number_format($course->price, 2) }}</h4>
                                @endif
                            @else
                                <h4 class="text-success mb-0">FREE</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Upload Material Modal -->
<div class="modal fade" id="uploadMaterialModal" tabindex="-1" aria-labelledby="uploadMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.courses.materials.upload', $course->slug) }}" method="POST" enctype="multipart/form-data">
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
                                <option value="{{ $module->slug }}">Module {{ $module->module_number }}: {{ $module->title }}</option>
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

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1" aria-labelledby="bulkUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUpdateModalLabel">Bulk Module Update Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <iconify-icon icon="mdi:alert-circle" class="icon me-2"></iconify-icon>
                    <strong>Warning:</strong> Bulk updates can affect existing modules. Choose your option carefully.
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Update Method</label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="update_method" id="updateReplace" value="replace" checked>
                        <label class="form-check-label" for="updateReplace">
                            <strong>Replace All Modules</strong>
                            <small class="d-block text-muted">Delete all existing modules and create new ones from bulk content</small>
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="update_method" id="updateMerge" value="merge">
                        <label class="form-check-label" for="updateMerge">
                            <strong>Merge with Existing</strong>
                            <small class="d-block text-muted">Update existing modules with same numbers, add new ones</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="update_method" id="updateAddOnly" value="add_only">
                        <label class="form-check-label" for="updateAddOnly">
                            <strong>Add New Only</strong>
                            <small class="d-block text-muted">Only add new modules, don't modify existing ones</small>
                        </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Document Import (Optional)</label>
                    <input type="file" name="bulk_document" class="form-control" accept=".txt,.doc,.docx,.pdf">
                    <small class="text-muted">Upload a document containing module content</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="processBulkUpdate()">Process Bulk Update</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .file-upload-container {
        position: relative;
    }
    .character-count {
        font-size: 0.75rem;
        color: #6c757d;
    }
    #imagePreview, #bannerImagePreview {
        transition: all 0.3s ease;
    }
    .video-preview-container {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        height: 0;
        overflow: hidden;
        border-radius: 8px;
        background: #f8f9fa;
    }

    .video-preview-container iframe,
    .video-preview-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    .current-video video {
        max-height: 200px;
        width: 100%;
        object-fit: contain;
        background: #000;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    textarea.form-control {
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        line-height: 1.5;
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
</style>
@endpush

@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', function() {
    console.log('Document loaded - initializing edit form');

    // Initialize CKEditor for all textareas with rich-editor class
    document.querySelectorAll('textarea.rich-editor').forEach(textarea => {
        ClassicEditor
            .create(textarea)
            .catch(error => {
                console.error(`Error initializing CKEditor for ${textarea.id}:`, error);
            });
    });

    // Initialize CKEditor 5 if needed
    if (typeof ClassicEditor !== 'undefined') {
        // You can initialize CKEditor here if needed
        // ClassicEditor.create(document.querySelector('#editor1')).catch(error => { console.error(error); });
    } 

    // Image preview functionality
    const imageInput = document.getElementById('imageInput');
    const bannerImageInput = document.getElementById('bannerImageInput');
    const imagePreview = document.getElementById('imagePreview');
    const bannerImagePreview = document.getElementById('bannerImagePreview');
    const previewImage = document.getElementById('previewImage');
    const previewBannerImage = document.getElementById('previewBannerImage');
    const noImagePlaceholder = document.getElementById('noImagePlaceholder');
    const noBannerImagePlaceholder = document.getElementById('noBannerImagePlaceholder');

    // Handle course image preview
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            console.log('Course image input changed');
            handleImagePreview(this, previewImage, imagePreview, 5);
        });
    }

    // Handle banner image preview
    if (bannerImageInput) {
        bannerImageInput.addEventListener('change', function(e) {
            console.log('Banner image input changed');
            handleImagePreview(this, previewBannerImage, bannerImagePreview, 5);
        });
    }

    // Generic image preview handler
    function handleImagePreview(input, previewElement, previewContainer, maxSizeMB) {
        const file = input.files[0];
        if (file) {
            // Validate file size
            if (file.size > maxSizeMB * 1024 * 1024) {
                alert(`Image size should be less than ${maxSizeMB}MB`);
                input.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPG, PNG, GIF, WEBP)');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewElement.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.onerror = function() {
                alert('Error reading the image file');
                previewContainer.style.display = 'none';
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    }

    // Character count functionality
    function setupCharacterCount(textareaSelector, counterSelector) {
        const textarea = document.querySelector(textareaSelector);
        const counter = document.querySelector(counterSelector);
        
        if (textarea && counter) {
            const maxLength = textarea.getAttribute('maxlength') || 160;
            
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

    // Set up character counters
    setupCharacterCount('textarea[name="short_description"]', '.character-count[data-target="short_description"]');
    setupCharacterCount('#metaDescription', '.character-count[data-target="meta_description"]');

    // Update stats in real-time
    const totalModulesInput = document.getElementById('totalModules');
    const totalHoursInput = document.getElementById('totalHours');
    const levelSelect = document.querySelector('select[name="level"]');
    const formatSelect = document.querySelector('select[name="format"]');
    const moduleCountPreview = document.getElementById('moduleCountPreview');
    const statsModules = document.getElementById('statsModules');
    const statsHours = document.getElementById('statsHours');
    const statsLevel = document.getElementById('statsLevel');
    const statsFormat = document.getElementById('statsFormat');

    function updateStats() {
        const modules = parseInt(totalModulesInput.value) || {{ $course->total_modules }};
        const hours = parseInt(totalHoursInput.value) || {{ $course->total_hours }};
        const level = levelSelect ? levelSelect.options[levelSelect.selectedIndex].text : '{{ ucfirst($course->level) }}';
        const format = formatSelect ? formatSelect.options[formatSelect.selectedIndex].text : '{{ ucfirst(str_replace('_', ' ', $course->format)) }}';
        
        if (moduleCountPreview) {
            moduleCountPreview.textContent = `Total Modules: {{ $course->modules->count() }} of ${modules}`;
        }
        if (statsModules) {
            statsModules.textContent = {{ $course->modules->count() }};
        }
        if (statsHours) {
            statsHours.textContent = hours;
        }
        if (statsLevel) {
            statsLevel.textContent = level;
        }
        if (statsFormat) {
            statsFormat.textContent = format;
        }
    }

    if (totalModulesInput) totalModulesInput.addEventListener('input', updateStats);
    if (totalHoursInput) totalHoursInput.addEventListener('input', updateStats);
    if (levelSelect) levelSelect.addEventListener('change', updateStats);
    if (formatSelect) formatSelect.addEventListener('change', updateStats);
    updateStats(); // Initial update

    // Price validation
    const priceInput = document.getElementById('priceInput');
    const discountInput = document.getElementById('discountPrice');

    if (priceInput && discountInput) {
        discountInput.addEventListener('input', function() {
            const price = parseFloat(priceInput.value) || 0;
            const discount = parseFloat(this.value) || 0;
            
            if (discount > price) {
                this.setCustomValidity('Discount price cannot be higher than regular price');
            } else {
                this.setCustomValidity('');
            }
        });
    }

    // VIDEO FUNCTIONALITY
    const videoTypeSelect = document.getElementById('videoTypeSelect');
    const videoUploadField = document.querySelector('.video-upload-field');
    const videoUrlField = document.querySelector('.video-url-field');
    const videoUrlHelp = document.getElementById('videoUrlHelp');
    const videoPreview = document.getElementById('videoPreview');
    const videoPlayer = document.getElementById('videoPlayer');
    const videoFileInput = document.getElementById('videoFileInput');
    const videoUrlInput = document.getElementById('videoUrlInput');

    // Function to show/hide video fields based on selection
    function updateVideoFields() {
        if (!videoTypeSelect) return;
        
        const selectedType = videoTypeSelect.value;
        
        // Always hide both fields first
        if (videoUploadField) videoUploadField.style.display = 'none';
        if (videoUrlField) videoUrlField.style.display = 'none';
        
        // Show appropriate field based on selection
        if (selectedType === 'upload') {
            if (videoUploadField) {
                videoUploadField.style.display = 'block';
            }
        } else if (selectedType === 'youtube' || selectedType === 'vimeo') {
            if (videoUrlField) {
                videoUrlField.style.display = 'block';
            }
            
            // Update help text
            if (videoUrlHelp) {
                if (selectedType === 'youtube') {
                    videoUrlHelp.textContent = 'Enter the full YouTube video URL (e.g., https://www.youtube.com/watch?v=VIDEO_ID)';
                } else {
                    videoUrlHelp.textContent = 'Enter the full Vimeo video URL (e.g., https://vimeo.com/VIDEO_ID)';
                }
            }
        }
    }

    // Initialize video fields on page load
    updateVideoFields();

    // Handle video type change
    if (videoTypeSelect) {
        videoTypeSelect.addEventListener('change', function() {
            updateVideoFields();
            
            // Clear video preview if switching away from current type
            if (this.value !== '{{ $course->video_type }}') {
                if (videoPlayer) {
                    videoPlayer.innerHTML = '';
                }
                if (videoPreview) {
                    videoPreview.style.display = 'none';
                }
            }
        });
    }

    // Handle video URL input for YouTube/Vimeo
    if (videoUrlInput) {
        videoUrlInput.addEventListener('input', function() {
            const url = this.value.trim();
            const videoType = videoTypeSelect ? videoTypeSelect.value : 'none';
            
            if (!url || videoType === 'none' || videoType === 'upload') {
                if (videoPreview) videoPreview.style.display = 'none';
                return;
            }
            
            // Wait a bit before processing to avoid too many updates
            setTimeout(() => {
                updateVideoPreview(url, videoType);
            }, 500);
        });
    }

    // Handle video file input
    if (videoFileInput) {
        videoFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (!file) {
                if (videoPreview) videoPreview.style.display = 'none';
                return;
            }
            
            // Validate file size (20MB max)
            if (file.size > 20 * 1024 * 1024) {
                alert(`File is too large (${(file.size / (1024 * 1024)).toFixed(2)}MB). Maximum size is 20MB.`);
                this.value = '';
                return;
            }
            
            // Show preview
            try {
                const url = URL.createObjectURL(file);
                if (videoPlayer) {
                    videoPlayer.innerHTML = `
                        <video controls style="width: 100%; max-height: 200px; border-radius: 8px;">
                            <source src="${url}" type="${file.type}">
                            Your browser does not support the video tag.
                        </video>
                    `;
                }
                
                if (videoPreview) {
                    videoPreview.style.display = 'block';
                }
                
            } catch (error) {
                console.error('Error creating preview:', error);
                alert('Error previewing video. Please try a different file.');
                this.value = '';
            }
        });
    }

    // Function to update video preview
    function updateVideoPreview(url, videoType) {
        if (!videoPlayer) return;
        
        let embedUrl = null;
        
        if (videoType === 'youtube') {
            const videoId = extractYouTubeId(url);
            if (videoId) {
                embedUrl = `https://www.youtube.com/embed/${videoId}`;
            }
        } else if (videoType === 'vimeo') {
            const videoId = extractVimeoId(url);
            if (videoId) {
                embedUrl = `https://player.vimeo.com/video/${videoId}`;
            }
        }
        
        if (embedUrl) {
            videoPlayer.innerHTML = `
                <iframe src="${embedUrl}" 
                        width="100%" 
                        height="100%" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen 
                        style="border-radius: 8px;">
                </iframe>
            `;
            
            // Show preview container
            if (videoPreview) {
                videoPreview.style.display = 'block';
            }
        }
    }

    // Helper functions to extract video IDs
    function extractYouTubeId(url) {
        const patterns = [
            /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&?\/]+)/,
            /youtube\.com\/.*[?&]v=([^&]+)/,
            /youtu\.be\/([^?]+)/
        ];
        
        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match && match[1]) {
                return match[1];
            }
        }
        return null;
    }

    function extractVimeoId(url) {
        const pattern = /vimeo\.com\/(?:video\/)?(\d+)/;
        const match = url.match(pattern);
        return match ? match[1] : null;
    }

    // Form validation
    const courseForm = document.getElementById('courseForm');
    if (courseForm) {
        courseForm.addEventListener('submit', function(e) {
            // Clear previous custom validity messages
            const inputs = this.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.setCustomValidity('');
            });

            // Validate discount price
            if (discountInput && priceInput) {
                const price = parseFloat(priceInput.value) || 0;
                const discount = parseFloat(discountInput.value) || 0;
                if (discount > price) {
                    discountInput.setCustomValidity('Discount price cannot be higher than regular price');
                    e.preventDefault();
                    discountInput.reportValidity();
                    return;
                }
            }

            // Validate video fields
            const videoType = videoTypeSelect ? videoTypeSelect.value : 'none';
            if (videoType === 'youtube' || videoType === 'vimeo') {
                if (!videoUrlInput || !videoUrlInput.value.trim()) {
                    e.preventDefault();
                    alert('Please enter a video URL for the selected video type');
                    if (videoUrlInput) videoUrlInput.focus();
                    return;
                }
            }
            
            // Confirm before submitting
            if (!confirm('Are you sure you want to update this course?')) {
                e.preventDefault();
                return;
            }
        });
    }

    // Bulk update processing
    function processBulkUpdate() {
        const updateMethod = document.querySelector('input[name="update_method"]:checked').value;
        const bulkDocument = document.querySelector('input[name="bulk_document"]');
        
        // You can add AJAX call here to process bulk update
        // For now, we'll just close the modal and show a message
        $('#bulkUpdateModal').modal('hide');
        alert(`Bulk update will be processed using "${updateMethod}" method. This feature requires server-side implementation.`);
    }

    console.log('All event listeners attached');
});
</script>
@endpush