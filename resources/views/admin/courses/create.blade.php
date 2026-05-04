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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.courses.store') }}"
        method="POST" enctype="multipart/form-data" id="courseForm" novalidate>
        @csrf
        <div class="row gy-4">
            <div class="col-lg-8">

                {{-- ======================================================
                     COURSE BASIC INFORMATION
                     ====================================================== --}}
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Course Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-12">
                                <label class="form-label">Course Title <span class="text-danger">*</span></label>
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Enter course title"
                                    value="{{ old('title') }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Short Title <span class="text-danger">*</span></label>
                                <input type="text" name="short_title"
                                    class="form-control @error('short_title') is-invalid @enderror"
                                    placeholder="e.g., Certified GRC & Financial Crime Specialist"
                                    value="{{ old('short_title') }}" required>
                                @error('short_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Course Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">IGRCFP Category</label>
                                <select name="igrcfp_category" class="form-select">
                                    <option value="">Select IGRCFP Category</option>
                                    <option value="IGRCFP Certificates"                       {{ old('igrcfp_category') == 'IGRCFP Certificates'                       ? 'selected' : '' }}>IGRCFP Certificates</option>
                                    <option value="IGRCFP Diploma"                            {{ old('igrcfp_category') == 'IGRCFP Diploma'                            ? 'selected' : '' }}>IGRCFP Diploma</option>
                                    <option value="IGRCFP Advanced Diploma"                   {{ old('igrcfp_category') == 'IGRCFP Advanced Diploma'                   ? 'selected' : '' }}>IGRCFP Advanced Diploma</option>
                                    <option value="Certified GRC &amp; Financial Crime Specialist" {{ old('igrcfp_category') == 'Certified GRC & Financial Crime Specialist' ? 'selected' : '' }}>Certified GRC &amp; Financial Crime Specialist</option>
                                    <option value="IGRCFP Fellowship"                         {{ old('igrcfp_category') == 'IGRCFP Fellowship'                         ? 'selected' : '' }}>IGRCFP Fellowship</option>
                                </select>
                                <p class="text-sm mt-1 mb-0 text-muted">Separate from the standard course category.</p>
                            </div>

                            {{-- Short description: plain textarea, NO rich-editor class --}}
                            <div class="col-12">
                                <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                <textarea name="short_description"
                                    class="form-control @error('short_description') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Brief description of the course (max 500 characters)"
                                    required>{{ old('short_description') }}</textarea>
                                <div class="character-count" data-target="short_description">0/500</div>
                                @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Full description: CKEditor --}}
                            <div class="col-12">
                                <label class="form-label">Full Description <span class="text-danger">*</span></label>
                                <textarea id="full_description" name="full_description"
                                    class="form-control rich-editor @error('full_description') is-invalid @enderror"
                                    rows="8"
                                    placeholder="Detailed description of the course...">{{ old('full_description') }}</textarea>
                                @error('full_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Course Image</label>
                                <div class="file-upload-container">
                                    <input class="form-control @error('image') is-invalid @enderror"
                                        type="file" name="image" id="imageInput"
                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    <p class="text-sm mt-1 mb-0 text-muted">
                                        Recommended: 400×300px. JPG/PNG/GIF/WEBP. Max 2 MB.
                                    </p>
                                </div>
                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Banner Image</label>
                                <div class="file-upload-container">
                                    <input class="form-control @error('banner_image') is-invalid @enderror"
                                        type="file" name="banner_image" id="bannerImageInput"
                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    <p class="text-sm mt-1 mb-0 text-muted">
                                        Recommended: 1200×400px. Max 5 MB.
                                    </p>
                                </div>
                                @error('banner_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ======================================================
                     COURSE DETAILS
                     ====================================================== --}}
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Course Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-md-6">
                                <label class="form-label">Level <span class="text-danger">*</span></label>
                                <select name="level" class="form-select @error('level') is-invalid @enderror" required>
                                    <option value="beginner"     {{ old('level') == 'beginner'     ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced"     {{ old('level') == 'advanced'     ? 'selected' : '' }}>Advanced</option>
                                    <option value="expert"       {{ old('level') == 'expert'       ? 'selected' : '' }}>Expert</option>
                                </select>
                                @error('level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Format <span class="text-danger">*</span></label>
                                <select name="format" class="form-select @error('format') is-invalid @enderror" required>
                                    <option value="self_paced"     {{ old('format') == 'self_paced'     ? 'selected' : '' }}>Self-Paced</option>
                                    <option value="instructor_led" {{ old('format') == 'instructor_led' ? 'selected' : '' }}>Instructor-Led</option>
                                    <option value="hybrid"         {{ old('format') == 'hybrid'         ? 'selected' : '' }}>Hybrid</option>
                                </select>
                                @error('format')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Duration <span class="text-danger">*</span></label>
                                <input type="text" name="duration"
                                    class="form-control @error('duration') is-invalid @enderror"
                                    placeholder="e.g., 6 weeks, 40 hours"
                                    value="{{ old('duration') }}" required>
                                @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Total Modules <span class="text-danger">*</span></label>
                                <input type="number" name="total_modules" id="totalModules"
                                    class="form-control @error('total_modules') is-invalid @enderror"
                                    value="{{ old('total_modules') }}" min="1" required>
                                @error('total_modules')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Total Hours <span class="text-danger">*</span></label>
                                <input type="number" name="total_hours" id="totalHours"
                                    class="form-control @error('total_hours') is-invalid @enderror"
                                    value="{{ old('total_hours') }}" min="1" required>
                                @error('total_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Certification Name <span class="text-danger">*</span></label>
                                <input type="text" name="certification_name"
                                    class="form-control @error('certification_name') is-invalid @enderror"
                                    placeholder="e.g., Certified GRC & Financial Crime Specialist"
                                    value="{{ old('certification_name') }}" required>
                                @error('certification_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Certifying Body <span class="text-danger">*</span></label>
                                {{-- Plain input, not a textarea/rich-editor --}}
                                <input type="text" name="certifying_body"
                                    class="form-control @error('certifying_body') is-invalid @enderror"
                                    placeholder="e.g., Institute of GRC and Financial Crime Prevention (IGRCFP)"
                                    value="{{ old('certifying_body') }}" required>
                                @error('certifying_body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ======================================================
                     PRICING
                     ====================================================== --}}
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
                                    <input type="number" name="price" id="priceInput"
                                        class="form-control @error('price') is-invalid @enderror"
                                        placeholder="0.00" value="{{ old('price', '0') }}"
                                        step="0.01" min="0">
                                </div>
                                <p class="text-sm mt-1 mb-0 text-muted">Leave as 0 for free courses</p>
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Discount Price ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="discount_price" id="discountPrice"
                                        class="form-control @error('discount_price') is-invalid @enderror"
                                        placeholder="0.00" value="{{ old('discount_price', '0') }}"
                                        step="0.01" min="0">
                                </div>
                                <p class="text-sm mt-1 mb-0 text-muted">Leave as 0 for no discount</p>
                                @error('discount_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

                {{-- ======================================================
                     DETAILED COURSE INFORMATION  (CKEditor fields)
                     ====================================================== --}}
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Detailed Course Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-12">
                                <label class="form-label">Programme Overview</label>
                                <textarea id="programme_overview" name="programme_overview"
                                    class="form-control rich-editor @error('programme_overview') is-invalid @enderror"
                                    rows="6"
                                    placeholder="Detailed programme overview...">{{ old('programme_overview', '') }}</textarea>
                                @error('programme_overview')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Programme Architecture</label>
                                <textarea id="programme_architecture" name="programme_architecture"
                                    class="form-control rich-editor @error('programme_architecture') is-invalid @enderror"
                                    rows="6"
                                    placeholder="Describe the programme tiers and structure...">{{ old('programme_architecture') }}</textarea>
                                @error('programme_architecture')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Learning Outcomes <span class="text-danger">*</span></label>
                                <textarea id="learning_outcomes" name="learning_outcomes"
                                    class="form-control rich-editor @error('learning_outcomes') is-invalid @enderror"
                                    rows="8"
                                    placeholder="Enter learning outcomes, one per line"
                                    required>{{ old('learning_outcomes') }}</textarea>
                                @error('learning_outcomes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Target Audience <span class="text-danger">*</span></label>
                                <textarea name="target_audience"
                                    class="form-control rich-editor @error('target_audience') is-invalid @enderror"
                                    rows="5"
                                    placeholder="Enter target audience, one per line"
                                    required>{{ old('target_audience') }}</textarea>
                                @error('target_audience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Prerequisites</label>
                                <textarea name="prerequisites"
                                    class="form-control rich-editor @error('prerequisites') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Requirements before taking this course">{{ old('prerequisites') }}</textarea>
                                @error('prerequisites')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Career Pathways</label>
                                <textarea name="career_pathways"
                                    class="form-control rich-editor @error('career_pathways') is-invalid @enderror"
                                    rows="4"
                                    placeholder="Enter career pathways, one per line">{{ old('career_pathways') }}</textarea>
                                @error('career_pathways')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Assessment Structure</label>
                                <textarea name="assessment_structure"
                                    class="form-control rich-editor @error('assessment_structure') is-invalid @enderror"
                                    rows="4"
                                    placeholder="Enter assessment structure, one per line">{{ old('assessment_structure') }}</textarea>
                                @error('assessment_structure')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Code of Professional Conduct</label>
                                <textarea id="code_of_conduct" name="code_of_conduct"
                                    class="form-control rich-editor @error('code_of_conduct') is-invalid @enderror"
                                    rows="4"
                                    placeholder="Enter code of conduct, one per line">{{ old('code_of_conduct') }}</textarea>
                                @error('code_of_conduct')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ======================================================
                     BULK MODULES UPLOAD
                     CRITICAL: NO rich-editor class on this textarea.
                     CKEditor must NOT touch it — it must stay plain text
                     so the module parser can read "Module 1: Title" lines.
                     ====================================================== --}}
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Course Modules (Bulk Upload)</h6>
                    </div>
                    <div class="card-body">

                        <div class="alert alert-info">
                            <iconify-icon icon="mdi:information" class="icon me-2"></iconify-icon>
                            <strong>Format Instructions (plain text only):</strong>
                            <ul class="mb-0 mt-2">
                                <li>Start each module with: <code>Module X: Title</code></li>
                                <li>Follow with module description</li>
                                <li>Add optional sections: <code>Objectives:</code>, <code>Topics:</code>, <code>Case Study:</code>, <code>Exercise:</code></li>
                                <li>Separate modules with a blank line</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Paste Module Content <span class="text-danger">*</span></label>
                            {{-- NO rich-editor class — this must remain a plain textarea --}}
                            <textarea name="bulk_modules"
                                class="form-control plain-textarea @error('bulk_modules') is-invalid @enderror"
                                rows="20"
                                placeholder="Module 1: Introduction to GRC&#10;This module provides an overview of Governance, Risk, and Compliance...&#10;&#10;Module 2: Risk Management Framework&#10;Learn about risk assessment methodologies and frameworks...">{{ old('bulk_modules') }}</textarea>
                            @error('bulk_modules')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>

                {{-- ======================================================
                     SEO SETTINGS  (plain textareas — no CKEditor)
                     ====================================================== --}}
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">SEO Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-12">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" id="metaDescription"
                                    class="form-control @error('meta_description') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Brief description for search engines (max 160 characters)">{{ old('meta_description') }}</textarea>
                                <div class="character-count" data-target="meta_description">0/160</div>
                                @error('meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Meta Keywords</label>
                                <textarea name="meta_keywords" id="meta_keywords"
                                    class="form-control @error('meta_keywords') is-invalid @enderror"
                                    rows="3"
                                    placeholder="keyword1, keyword2, keyword3"
                                    maxlength="200">{{ old('meta_keywords') }}</textarea>
                                <p class="text-sm mt-1 mb-0 text-muted">Separate keywords with commas</p>
                                @error('meta_keywords')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                </div>

            </div>{{-- /col-lg-8 --}}

            <div class="col-lg-4">

                {{-- ======================================================
                     COURSE SETTINGS
                     ====================================================== --}}
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Course Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-12">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="draft"     {{ old('status') == 'draft'     ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived"  {{ old('status') == 'archived'  ? 'selected' : '' }}>Archived</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order"
                                    class="form-control @error('sort_order') is-invalid @enderror"
                                    placeholder="0" value="{{ old('sort_order', 0) }}">
                                <p class="text-sm mt-1 mb-0 text-muted">Lower numbers appear first</p>
                                @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="is_featured" id="is_featured" value="1"
                                        {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Feature this course</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="is_popular" id="is_popular" value="1"
                                        {{ old('is_popular') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_popular">Mark as popular course</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border-top pt-3 mt-3">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1" id="submitBtn">
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

                {{-- IMAGE PREVIEW --}}
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

                {{-- MODULE PREVIEW --}}
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

                {{-- QUICK TIPS --}}
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
                                <small class="text-muted">Paste plain text into the bulk modules box</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Set to "Draft" first, then publish when ready</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">SEO meta description should be 150–160 characters</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- COURSE STATS --}}
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

            </div>{{-- /col-lg-4 --}}
        </div>{{-- /row --}}
    </form>
</div>
@endsection

@push('styles')
<style>
.file-upload-container { position: relative; }
.character-count { font-size: 0.75rem; color: #6c757d; margin-top: 0.25rem; }
.invalid-feedback { display: block; width: 100%; margin-top: 0.25rem; font-size: 0.875em; color: #dc3545; }
.form-control.is-invalid, .form-select.is-invalid { border-color: #dc3545; }
textarea.plain-textarea { font-family: 'Courier New', monospace; font-size: 0.9rem; line-height: 1.5; }
.icon-2x { font-size: 2rem; }
.icon-3x { font-size: 3rem; }
.rounded-8 { border-radius: 8px; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ------------------------------------------------------------------
    // CKEditor initialisation
    // Only textareas with class "rich-editor" get CKEditor.
    // bulk_modules, short_description, meta_description, meta_keywords
    // deliberately do NOT have that class and stay as plain textareas.
    // ------------------------------------------------------------------
    const ckEditorInstances = new Map(); // textarea name → editor instance

    function initializeCKEditors() {
        if (typeof ClassicEditor === 'undefined') {
            console.error('CKEditor not loaded');
            return;
        }

        document.querySelectorAll('textarea.rich-editor:not([data-ck-initialized])').forEach(function (textarea) {
            ClassicEditor.create(textarea)
                .then(function (editor) {
                    textarea.setAttribute('data-ck-initialized', 'true');
                    ckEditorInstances.set(textarea.name, editor);
                })
                .catch(function (err) {
                    console.error('CKEditor init error for [' + textarea.name + ']:', err);
                });
        });
    }

    setTimeout(initializeCKEditors, 150);

    // ------------------------------------------------------------------
    // Form submit — sync CKEditor instances, then validate
    // ------------------------------------------------------------------
    const courseForm = document.getElementById('courseForm');
    if (courseForm) {
        courseForm.addEventListener('submit', function (e) {
            // Sync every CKEditor instance back to its textarea
            ckEditorInstances.forEach(function (editor, name) {
                const textarea = courseForm.querySelector('textarea[name="' + name + '"]');
                if (textarea) {
                    textarea.value = editor.getData();
                }
            });

            // Client-side discount check
            const price    = parseFloat(document.getElementById('priceInput')?.value)   || 0;
            const discount = parseFloat(document.getElementById('discountPrice')?.value) || 0;

            if (discount > 0 && discount > price) {
                e.preventDefault();
                const di = document.getElementById('discountPrice');
                di.setCustomValidity('Discount price cannot be higher than the regular price');
                di.reportValidity();
                return;
            }

            // Require bulk_modules to have content
            const bulkTextarea = document.querySelector('textarea[name="bulk_modules"]');
            if (bulkTextarea && !bulkTextarea.value.trim()) {
                e.preventDefault();
                alert('Please enter module content in the bulk modules section');
                bulkTextarea.focus();
                return;
            }

            if (!confirm('Create this course and generate all modules from the bulk content?')) {
                e.preventDefault();
                return;
            }

            // Disable button to prevent double-submit
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.disabled    = true;
                submitBtn.textContent = 'Creating…';
            }
        });
    }

    // ------------------------------------------------------------------
    // Image previews
    // ------------------------------------------------------------------
    function handleImagePreview(inputId, previewImgId, previewContainerId, placeholderId, maxSizeMB) {
        const input       = document.getElementById(inputId);
        const previewImg  = document.getElementById(previewImgId);
        const container   = document.getElementById(previewContainerId);
        const placeholder = document.getElementById(placeholderId);

        if (!input) return;

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) {
                container.style.display   = 'none';
                if (placeholder) placeholder.style.display = 'block';
                return;
            }

            if (file.size > maxSizeMB * 1024 * 1024) {
                alert('Image size must be less than ' + maxSizeMB + ' MB');
                this.value = '';
                container.style.display = 'none';
                if (placeholder) placeholder.style.display = 'block';
                return;
            }

            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPG, PNG, GIF, WEBP)');
                this.value = '';
                container.style.display = 'none';
                if (placeholder) placeholder.style.display = 'block';
                return;
            }

            const reader = new FileReader();
            reader.onload  = function (e) {
                previewImg.src             = e.target.result;
                container.style.display   = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.onerror = function () {
                container.style.display = 'none';
                if (placeholder) placeholder.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    }

    handleImagePreview('imageInput',      'previewImage',       'imagePreview',       'noImagePlaceholder',       2);
    handleImagePreview('bannerImageInput', 'previewBannerImage', 'bannerImagePreview', 'noBannerImagePlaceholder', 5);

    // ------------------------------------------------------------------
    // Pricing display
    // ------------------------------------------------------------------
    const priceInput          = document.getElementById('priceInput');
    const discountInput       = document.getElementById('discountPrice');
    const courseTypeIndicator = document.getElementById('courseTypeIndicator');
    const finalPriceDisplay   = document.getElementById('finalPriceDisplay');
    const discountInfo        = document.getElementById('discountInfo');
    const discountBadge       = document.getElementById('discountBadge');
    const statsCourseType     = document.getElementById('statsCourseType');

    function updatePricingDisplay() {
        const price    = parseFloat(priceInput?.value)    || 0;
        const discount = parseFloat(discountInput?.value) || 0;

        if (courseTypeIndicator) {
            if (price === 0) {
                courseTypeIndicator.textContent = 'FREE COURSE';
                courseTypeIndicator.className   = 'badge bg-success ms-2';
                if (statsCourseType) statsCourseType.textContent = 'Free';
            } else if (discount > 0 && discount < price) {
                courseTypeIndicator.textContent = 'DISCOUNTED COURSE';
                courseTypeIndicator.className   = 'badge bg-warning ms-2';
                if (statsCourseType) statsCourseType.textContent = 'Discounted';
            } else {
                courseTypeIndicator.textContent = 'PAID COURSE';
                courseTypeIndicator.className   = 'badge bg-primary ms-2';
                if (statsCourseType) statsCourseType.textContent = 'Paid';
            }
        }

        if (finalPriceDisplay) {
            if (price === 0) {
                finalPriceDisplay.textContent = 'FREE';
                finalPriceDisplay.className   = 'h4 mb-0 text-success';
                if (discountInfo) discountInfo.style.display = 'none';
            } else if (discount > 0 && discount < price) {
                finalPriceDisplay.textContent = '$' + discount.toFixed(2);
                finalPriceDisplay.className   = 'h4 mb-0 text-danger';
                if (discountInfo && discountBadge) {
                    discountInfo.style.display = 'block';
                    const pct = Math.round(((price - discount) / price) * 100);
                    discountBadge.textContent = 'Save ' + pct + '% ($' + (price - discount).toFixed(2) + ')';
                }
            } else {
                finalPriceDisplay.textContent = '$' + price.toFixed(2);
                finalPriceDisplay.className   = 'h4 mb-0 text-primary';
                if (discountInfo) discountInfo.style.display = 'none';
            }
        }
    }

    priceInput?.addEventListener('input', updatePricingDisplay);
    discountInput?.addEventListener('input', function () {
        const price    = parseFloat(priceInput?.value) || 0;
        const discount = parseFloat(this.value)        || 0;
        this.setCustomValidity(discount > price ? 'Discount price cannot be higher than regular price' : '');
        updatePricingDisplay();
    });

    // ------------------------------------------------------------------
    // Character counts (plain textareas only)
    // ------------------------------------------------------------------
    function setupCharacterCount(selector, counterSelector, max) {
        const textarea = document.querySelector(selector);
        const counter  = document.querySelector(counterSelector);
        if (!textarea || !counter) return;

        function update() {
            const len = textarea.value.length;
            counter.textContent = len + '/' + max;
            counter.style.color = len > max ? '#dc3545' : (len > max * 0.9 ? '#ffc107' : '#6c757d');
        }

        textarea.addEventListener('input', update);
        update();
    }

    setupCharacterCount('textarea[name="short_description"]', '.character-count[data-target="short_description"]', 500);
    setupCharacterCount('#metaDescription',                    '.character-count[data-target="meta_description"]',  160);

    // ------------------------------------------------------------------
    // Live stats panel
    // ------------------------------------------------------------------
    const totalModulesInput = document.getElementById('totalModules');
    const totalHoursInput   = document.getElementById('totalHours');
    const levelSelect       = document.querySelector('select[name="level"]');
    const formatSelect      = document.querySelector('select[name="format"]');

    function updateStats() {
        const modules = parseInt(totalModulesInput?.value) || 0;
        const hours   = parseInt(totalHoursInput?.value)   || 0;
        const level   = levelSelect  ? levelSelect.options[levelSelect.selectedIndex]?.text   : 'Beginner';
        const format  = formatSelect ? formatSelect.options[formatSelect.selectedIndex]?.text : 'Self-Paced';

        const moduleCountPreview = document.getElementById('moduleCountPreview');
        if (moduleCountPreview) moduleCountPreview.textContent = 'Total Modules: ' + modules;
        const statsModules = document.getElementById('statsModules');
        const statsHours   = document.getElementById('statsHours');
        const statsLevel   = document.getElementById('statsLevel');
        const statsFormat  = document.getElementById('statsFormat');
        if (statsModules) statsModules.textContent = modules;
        if (statsHours)   statsHours.textContent   = hours;
        if (statsLevel)   statsLevel.textContent   = level;
        if (statsFormat)  statsFormat.textContent  = format;
    }

    totalModulesInput?.addEventListener('input', updateStats);
    totalHoursInput?.addEventListener('input', updateStats);
    levelSelect?.addEventListener('change', updateStats);
    formatSelect?.addEventListener('change', updateStats);

    // Auto-count modules from plain-text bulk content
    const bulkTextarea = document.querySelector('textarea[name="bulk_modules"]');
    if (bulkTextarea) {
        bulkTextarea.addEventListener('input', function () {
            const count = (this.value.match(/^Module\s+\d+[:\s]/gim) || []).length;
            if (count > 0) {
                const moduleCountPreview = document.getElementById('moduleCountPreview');
                const statsModules       = document.getElementById('statsModules');
                if (moduleCountPreview) moduleCountPreview.textContent = 'Total Modules: ' + count;
                if (statsModules)       statsModules.textContent       = count;
                if (totalModulesInput)  totalModulesInput.value        = count;
            }
        });
    }

    updateStats();
    updatePricingDisplay();

}); // end DOMContentLoaded
</script>
@endpush