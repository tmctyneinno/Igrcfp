@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create New Course</h6>
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
            <li class="fw-medium">Create Course</li>
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

    <form action="{{ route('admin.courses.store') }}" 
      method="POST" enctype="multipart/form-data" id="courseForm" novalidate>
        @csrf
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
                                       placeholder="Enter course title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Short Title <span class="text-danger">*</span></label>
                                <input type="text" name="short_title" class="form-control @error('short_title') is-invalid @enderror" 
                                       placeholder="e.g., Certified GRC & Financial Crime Specialist" value="{{ old('short_title') }}" required>
                                @error('short_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Course Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" 
                                          rows="3" placeholder="Brief description of the course (max 500 characters)" required>{{ old('short_description') }}</textarea>
                                <div class="character-count" data-target="short_description">0/500</div>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Full Description <span class="text-danger">*</span></label>
                                <textarea id="full_description" name="full_description" class="form-control @error('full_description') is-invalid @enderror" 
                                          rows="8" placeholder="Detailed description of the course...">{{ old('full_description') }}</textarea>
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
                                    </p>
                                </div>
                                @error('banner_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                    <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                    <option value="expert" {{ old('level') == 'expert' ? 'selected' : '' }}>Expert</option>
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Format <span class="text-danger">*</span></label>
                                <select name="format" class="form-select @error('format') is-invalid @enderror" required>
                                    <option value="self_paced" {{ old('format') == 'self_paced' ? 'selected' : '' }}>Self-Paced</option>
                                    <option value="instructor_led" {{ old('format') == 'instructor_led' ? 'selected' : '' }}>Instructor-Led</option>
                                    <option value="hybrid" {{ old('format') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                                @error('format')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Duration <span class="text-danger">*</span></label>
                                <input type="text" name="duration" class="form-control @error('duration') is-invalid @enderror" 
                                       placeholder="e.g., 6 weeks, 40 hours" value="{{ old('duration') }}" required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Total Modules <span class="text-danger">*</span></label>
                                <input type="number" name="total_modules" id="totalModules" class="form-control @error('total_modules') is-invalid @enderror" 
                                       value="{{ old('total_modules') }}" min="1" required>
                                @error('total_modules')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Total Hours <span class="text-danger">*</span></label>
                                <input type="number" name="total_hours" id="totalHours" class="form-control @error('total_hours') is-invalid @enderror" 
                                        value="{{ old('total_hours') }}" min="1" required>
                                @error('total_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Certification Name <span class="text-danger">*</span></label>
                                <input type="text" name="certification_name" class="form-control @error('certification_name') is-invalid @enderror" 
                                       placeholder="e.g., Certified GRC & Financial Crime Specialist" value="{{ old('certification_name') }}" required>
                                @error('certification_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Certifying Body <span class="text-danger">*</span></label>
                                <input type="text" name="certifying_body" class="form-control @error('certifying_body') is-invalid @enderror" 
                                       placeholder="e.g., Institute of GRC and Financial Crime Prevention (IGRCFP)" value="{{ old('certifying_body') }}" required>
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
                            <div class="col-12">
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <iconify-icon icon="mdi:information" class="icon me-2"></iconify-icon>
                                    <div>
                                        <strong>Course Type:</strong> 
                                        <span id="courseTypeIndicator" class="badge bg-success ms-2">FREE COURSE</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Regular Price ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="price" id="priceInput" class="form-control @error('price') is-invalid @enderror" 
                                           placeholder="0.00" value="{{ old('price', '0') }}" step="0.01" min="0">
                                </div>
                                <p class="text-sm mt-1 mb-0 text-muted">Leave as 0 for free courses</p>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Discount Price ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="discount_price" id="discountPrice" class="form-control @error('discount_price') is-invalid @enderror" 
                                           placeholder="0.00" value="{{ old('discount_price', '0') }}" step="0.01" min="0">
                                </div>
                                <p class="text-sm mt-1 mb-0 text-muted">Leave as 0 for no discount</p>
                                @error('discount_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <div id="pricingPreview" class="mt-3 p-3 bg-light rounded-8">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-medium">Final Price:</span>
                                        <span id="finalPriceDisplay" class="h4 mb-0 text-success">FREE</span>
                                    </div>
                                    <div id="discountInfo" class="mt-2" style="display: none;">
                                        <span class="badge bg-danger" id="discountBadge">Save 0%</span>
                                    </div>
                                </div>
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
                                <textarea id="programme_overview" name="programme_overview" class="form-control @error('programme_overview') is-invalid @enderror" rows="6" 
                                          placeholder="Detailed programme overview...">{{ old('programme_overview', '') }}</textarea>
                                @error('programme_overview')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                           <div class="col-12">
                                <label class="form-label">Programme Architecture</label>
                                <textarea id="programme_architecture" name="programme_architecture" class="form-control @error('programme_architecture') is-invalid @enderror" rows="6"
                                    placeholder="Describe the programme tiers and structure...">{{ old('programme_architecture') }}</textarea>
                                @error('programme_architecture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Learning Outcomes (one per line) <span class="text-danger">*</span></label>
                                <textarea id="learning_outcomes" name="learning_outcomes" class="form-control @error('learning_outcomes') is-invalid @enderror" rows="8" 
                                          placeholder="Enter learning outcomes, one per line" required>{{ old('learning_outcomes') }}</textarea>
                                @error('learning_outcomes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Target Audience (one per line) <span class="text-danger">*</span></label>
                                <textarea name="target_audience" class="form-control @error('target_audience') is-invalid @enderror" rows="5" 
                                          placeholder="Enter target audience, one per line" required>{{ old('target_audience') }}</textarea>
                                @error('target_audience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Prerequisites</label>
                                <textarea name="prerequisites" class="form-control @error('prerequisites') is-invalid @enderror" 
                                          rows="3" placeholder="Requirements before taking this course">{{ old('prerequisites') }}</textarea>
                                @error('prerequisites')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Career Pathways (one per line)</label>
                                <textarea name="career_pathways" class="form-control @error('career_pathways') is-invalid @enderror" rows="4" 
                                          placeholder="Enter career pathways, one per line">{{ old('career_pathways') }}</textarea>
                                @error('career_pathways')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Assessment Structure (one per line)</label>
                                <textarea name="assessment_structure" class="form-control @error('assessment_structure') is-invalid @enderror" rows="4" 
                                          placeholder="Enter assessment structure, one per line">{{ old('assessment_structure') }}</textarea>
                                @error('assessment_structure')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Code of Professional Conduct (one per line)</label>
                                <textarea id="code_of_conduct" name="code_of_conduct" class="form-control @error('code_of_conduct') is-invalid @enderror" rows="4" 
                                          placeholder="Enter code of conduct, one per line">{{ old('code_of_conduct') }}</textarea>
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
                        <h6 class="card-title mb-0">Course Modules (Bulk Upload)</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <iconify-icon icon="mdi:information" class="icon me-2"></iconify-icon>
                            <strong>Format Instructions:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Use the format: "Module X: Title" on a new line</li>
                                <li>Follow with module description</li>
                                <li>Add sections like "Objectives:", "Topics:", "Case Study:", "Exercise:"</li>
                                <li>Separate modules with a blank line</li>
                            </ul>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Paste Module Content <span class="text-danger">*</span></label>
                            <textarea name="bulk_modules" class="form-control @error('bulk_modules') is-invalid @enderror" rows="20" 
                                      placeholder="Module 1: Introduction to GRC&#10;This module provides an overview of Governance, Risk, and Compliance...&#10;&#10;Module 2: Risk Management Framework&#10;Learn about risk assessment methodologies and frameworks...">{{ old('bulk_modules') }}</textarea>
                            @error('bulk_modules')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
                                <textarea name="meta_description" id="metaDescription" class="form-control @error('meta_description') is-invalid @enderror" 
                                          rows="3" placeholder="Brief description for search engines (max 160 characters)">{{ old('meta_description') }}</textarea>
                                <div class="character-count" data-target="meta_description">0/160</div>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Meta Keywords</label>
                                <textarea name="meta_keywords" id="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                          rows="3" placeholder="keyword1, keyword2, keyword3" maxlength="200">{{ old('meta_keywords') }}</textarea>
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
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                       placeholder="0" value="{{ old('sort_order', 0) }}">
                                <p class="text-sm mt-1 mb-0 text-muted">Lower numbers appear first</p>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Feature this course
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_popular">
                                        Mark as popular course
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border-top pt-3 mt-3">
                                     <div class="d-flex justify-content-center gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                             Create Course
                                        </button>
                                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
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
                        <h6 class="card-title mb-0">Image Previews</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="mb-3">Course Image</h6>
                            <div id="imagePreview" class="mb-3" style="display: none;">
                                <img id="previewImage" src="#" alt="Course image preview" 
                                     class="img-fluid rounded-8 border" style="max-height: 150px;">
                            </div>
                            <div id="noImagePlaceholder" class="text-muted py-2">
                                <iconify-icon icon="mdi:image-outline" class="icon-2x mb-2"></iconify-icon>
                                <p class="mb-0 small">No image selected</p>
                            </div>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="text-center">
                            <h6 class="mb-3">Banner Image</h6>
                            <div id="bannerImagePreview" class="mb-3" style="display: none;">
                                <img id="previewBannerImage" src="#" alt="Banner image preview" 
                                     class="img-fluid rounded-8 border" style="max-height: 100px;">
                            </div>
                            <div id="noBannerImagePlaceholder" class="text-muted py-2">
                                <iconify-icon icon="mdi:image-outline" class="icon-2x mb-2"></iconify-icon>
                                <p class="mb-0 small">No banner selected</p>
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
                                <iconify-icon icon="mdi:book-education" class="icon-3x text-primary"></iconify-icon>
                            </div>
                            <h6 id="moduleCountPreview">Total Modules: 0</h6>
                            <p class="text-sm text-muted mb-0">Modules will be created from the bulk upload</p>
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
                                <small class="text-muted">Use clear and descriptive course titles</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Keep short description under 500 characters</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Use the bulk module format exactly as shown</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Set to "Draft" first, then publish when ready</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">SEO meta description should be 150-160 characters</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Stats -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Course Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Modules:</span>
                                <span class="fw-medium" id="statsModules">0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Hours:</span>
                                <span class="fw-medium" id="statsHours">0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Level:</span>
                                <span class="fw-medium" id="statsLevel">Beginner</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Format:</span>
                                <span class="fw-medium" id="statsFormat">Self-Paced</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Course Type:</span>
                                <span class="fw-medium" id="statsCourseType">Free</span>
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
    .file-upload-container {
        position: relative;
    }
    .character-count {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    #imagePreview, #bannerImagePreview {
        transition: all 0.3s ease;
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
    .icon-2x {
        font-size: 2rem;
    }
    .icon-3x {
        font-size: 3rem;
    }
    .rounded-8 {
        border-radius: 8px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Document loaded - initializing course form');
    
    // Image preview functionality
    const imageInput = document.getElementById('imageInput');
    const bannerImageInput = document.getElementById('bannerImageInput');
    const imagePreview = document.getElementById('imagePreview');
    const bannerImagePreview = document.getElementById('bannerImagePreview');
    const previewImage = document.getElementById('previewImage');
    const previewBannerImage = document.getElementById('previewBannerImage');
    const noImagePlaceholder = document.getElementById('noImagePlaceholder');
    const noBannerImagePlaceholder = document.getElementById('noBannerImagePlaceholder');

    // Pricing elements
    const priceInput = document.getElementById('priceInput');
    const discountInput = document.getElementById('discountPrice');
    const courseTypeIndicator = document.getElementById('courseTypeIndicator');
    const finalPriceDisplay = document.getElementById('finalPriceDisplay');
    const discountInfo = document.getElementById('discountInfo');
    const discountBadge = document.getElementById('discountBadge');
    const statsCourseType = document.getElementById('statsCourseType');

    // Stats elements
    const totalModulesInput = document.getElementById('totalModules');
    const totalHoursInput = document.getElementById('totalHours');
    const levelSelect = document.querySelector('select[name="level"]');
    const formatSelect = document.querySelector('select[name="format"]');
    const moduleCountPreview = document.getElementById('moduleCountPreview');
    const statsModules = document.getElementById('statsModules');
    const statsHours = document.getElementById('statsHours');
    const statsLevel = document.getElementById('statsLevel');
    const statsFormat = document.getElementById('statsFormat');

    // Handle course image preview
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            handleImagePreview(this, previewImage, imagePreview, noImagePlaceholder, 2);
        });
    }

    // Handle banner image preview
    if (bannerImageInput) {
        bannerImageInput.addEventListener('change', function(e) {
            handleImagePreview(this, previewBannerImage, bannerImagePreview, noBannerImagePlaceholder, 5);
        });
    }

    // Generic image preview handler
    function handleImagePreview(input, previewElement, previewContainer, placeholder, maxSizeMB) {
        const file = input.files[0];
        if (file) {
            if (file.size > maxSizeMB * 1024 * 1024) {
                alert(`Image size should be less than ${maxSizeMB}MB`);
                input.value = '';
                return;
            }
            
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
                if (placeholder) placeholder.style.display = 'none';
            }
            reader.onerror = function() {
                alert('Error reading the image file');
                previewContainer.style.display = 'none';
                if (placeholder) placeholder.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
            if (placeholder) placeholder.style.display = 'block';
        }
    }

    // Pricing display update function
    function updatePricingDisplay() {
        const price = parseFloat(priceInput?.value) || 0;
        const discountPrice = parseFloat(discountInput?.value) || 0;
        
        // Update course type indicator
        if (courseTypeIndicator) {
            if (price === 0) {
                courseTypeIndicator.textContent = 'FREE COURSE';
                courseTypeIndicator.className = 'badge bg-success ms-2';
                if (statsCourseType) statsCourseType.textContent = 'Free';
            } else if (discountPrice > 0 && discountPrice < price) {
                courseTypeIndicator.textContent = 'DISCOUNTED COURSE';
                courseTypeIndicator.className = 'badge bg-warning ms-2';
                if (statsCourseType) statsCourseType.textContent = 'Discounted';
            } else {
                courseTypeIndicator.textContent = 'PAID COURSE';
                courseTypeIndicator.className = 'badge bg-primary ms-2';
                if (statsCourseType) statsCourseType.textContent = 'Paid';
            }
        }
        
        // Update final price display
        if (finalPriceDisplay) {
            let finalPrice = price;
            let displayText = '';
            
            if (price === 0) {
                displayText = 'FREE';
                finalPriceDisplay.className = 'h4 mb-0 text-success';
                if (discountInfo) discountInfo.style.display = 'none';
            } else if (discountPrice > 0 && discountPrice < price) {
                finalPrice = discountPrice;
                displayText = `$${finalPrice.toFixed(2)}`;
                finalPriceDisplay.className = 'h4 mb-0 text-danger';
                
                if (discountInfo && discountBadge) {
                    discountInfo.style.display = 'block';
                    const savings = price - discountPrice;
                    const percentage = Math.round((savings / price) * 100);
                    discountBadge.textContent = `Save ${percentage}% ($${savings.toFixed(2)})`;
                }
            } else {
                displayText = `$${price.toFixed(2)}`;
                finalPriceDisplay.className = 'h4 mb-0 text-primary';
                if (discountInfo) discountInfo.style.display = 'none';
            }
            
            finalPriceDisplay.textContent = displayText;
        }
    }

    // Add pricing event listeners
    if (priceInput) {
        priceInput.addEventListener('input', updatePricingDisplay);
    }
    if (discountInput) {
        discountInput.addEventListener('input', function() {
            const price = parseFloat(priceInput?.value) || 0;
            const discount = parseFloat(this.value) || 0;
            
            if (discount > price) {
                this.setCustomValidity('Discount price cannot be higher than regular price');
            } else {
                this.setCustomValidity('');
            }
            
            updatePricingDisplay();
        });
    }

    // Character count functionality
    function setupCharacterCount(textareaSelector, counterSelector, maxLength) {
        const textarea = document.querySelector(textareaSelector);
        const counter = document.querySelector(counterSelector);
        
        if (textarea && counter) {
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
            updateCount();
        }
    }

    setupCharacterCount('textarea[name="short_description"]', '.character-count[data-target="short_description"]', 500);
    setupCharacterCount('#metaDescription', '.character-count[data-target="meta_description"]', 160);

    // Update stats in real-time
    function updateStats() {
        const modules = parseInt(totalModulesInput?.value) || 0;
        const hours = parseInt(totalHoursInput?.value) || 0;
        const level = levelSelect ? levelSelect.options[levelSelect.selectedIndex]?.text : 'Beginner';
        const format = formatSelect ? formatSelect.options[formatSelect.selectedIndex]?.text : 'Self-Paced';
        
        if (moduleCountPreview) {
            moduleCountPreview.textContent = `Total Modules: ${modules}`;
        }
        if (statsModules) {
            statsModules.textContent = modules;
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

    // Module count from bulk content
    function updateModuleCount() {
        const bulkModulesTextarea = document.querySelector('textarea[name="bulk_modules"]');
        if (bulkModulesTextarea) {
            bulkModulesTextarea.addEventListener('input', function() {
                const content = this.value;
                const moduleCount = (content.match(/Module\s+\d+:/gi) || []).length;
                if (moduleCount > 0) {
                    if (moduleCountPreview) moduleCountPreview.textContent = `Total Modules: ${moduleCount}`;
                    if (statsModules) statsModules.textContent = moduleCount;
                    if (totalModulesInput) totalModulesInput.value = moduleCount;
                }
            });
        }
    }
    
    updateModuleCount();
    updateStats();
    updatePricingDisplay();

    // Form validation
    const courseForm = document.getElementById('courseForm');
    if (courseForm) {
        courseForm.addEventListener('submit', function(e) {
            const inputs = this.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.setCustomValidity('');
            });

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

            const bulkModulesTextarea = document.querySelector('textarea[name="bulk_modules"]');
            if (bulkModulesTextarea && !bulkModulesTextarea.value.trim()) {
                e.preventDefault();
                alert('Please enter module content in the bulk modules section');
                bulkModulesTextarea.focus();
                return;
            }
            
            if (!confirm('Are you sure you want to create this course? This will create all modules from the bulk content.')) {
                e.preventDefault();
                return;
            }
        });
    }

    console.log('All event listeners attached');
});
</script>
@endpush