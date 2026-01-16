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

    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" id="courseForm">
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

                            <div class="col-md-6">
                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                       placeholder="e.g., CGFCS" value="{{ old('code') }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Short Title <span class="text-danger">*</span></label>
                                <input type="text" name="short_title" class="form-control @error('short_title') is-invalid @enderror" 
                                       placeholder="e.g., Certified GRC & Financial Crime Specialist" value="{{ old('short_title') }}" required>
                                @error('short_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" 
                                          rows="3" placeholder="Brief description of the course (max 500 characters)" required maxlength="500">{{ old('short_description') }}</textarea>
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
                                <textarea name="full_description" class="form-control @error('full_description') is-invalid @enderror" 
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
                                    <option value="none" {{ old('video_type') == 'none' ? 'selected' : '' }}>No Video</option>
                                    <option value="upload" {{ old('video_type') == 'upload' ? 'selected' : '' }}>Upload Video</option>
                                    <option value="youtube" {{ old('video_type') == 'youtube' ? 'selected' : '' }}>YouTube Video</option>
                                    <option value="vimeo" {{ old('video_type') == 'vimeo' ? 'selected' : '' }}>Vimeo Video</option>
                                </select>
                                @error('video_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Upload Video Field -->
                            <div class="col-12 video-upload-field" style="display: {{ old('video_type') == 'upload' ? 'block' : 'none' }};">
                                <label class="form-label">Upload Video File</label>
                                <input class="form-control @error('video') is-invalid @enderror" 
                                    type="file" name="video" id="videoFileInput" accept="video/mp4,video/mov,video/avi,video/wmv,video/mkv">
                                <p class="text-sm mt-1 mb-0 text-muted">
                                    Supported formats: MP4, MOV, AVI, WMV, MKV. Max size: 20MB
                                </p>
                                @error('video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- YouTube/Vimeo URL Field -->
                            <div class="col-12 video-url-field" style="display: {{ in_array(old('video_type'), ['youtube', 'vimeo']) ? 'block' : 'none' }};">
                                <label class="form-label">Video URL</label>
                                <input type="url" name="video_url" id="videoUrlInput" class="form-control @error('video_url') is-invalid @enderror" 
                                    placeholder="https://www.youtube.com/watch?v=..." value="{{ old('video_url') }}">
                                <p class="text-sm mt-1 mb-0 text-muted" id="videoUrlHelp">
                                    Enter the full YouTube or Vimeo video URL
                                </p>
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Video Preview -->
                            <div class="col-12">
                                <div id="videoPreview" class="mt-3" style="display: none;">
                                    <div class="video-preview-container">
                                        <div id="videoPlayer"></div>
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
                                       placeholder="10" value="{{ old('total_modules') }}" min="1" required>
                                @error('total_modules')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Total Hours <span class="text-danger">*</span></label>
                                <input type="number" name="total_hours" id="totalHours" class="form-control @error('total_hours') is-invalid @enderror" 
                                       placeholder="40" value="{{ old('total_hours') }}" min="1" required>
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
                            <div class="col-md-6">
                                <label class="form-label">Regular Price ($)</label>
                                <input type="number" name="price" id="priceInput" class="form-control @error('price') is-invalid @enderror" 
                                       placeholder="0.00" value="{{ old('price') }}" step="0.01" min="0">
                                <p class="text-sm mt-1 mb-0 text-muted">Leave as 0 for free courses</p>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Discount Price ($)</label>
                                <input type="number" name="discount_price" id="discountPrice" class="form-control @error('discount_price') is-invalid @enderror" 
                                       placeholder="0.00" value="{{ old('discount_price') }}" step="0.01" min="0">
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
                                <textarea name="programme_overview" class="form-control @error('programme_overview') is-invalid @enderror" rows="6" 
                                          placeholder="Detailed programme overview...">{{ old('programme_overview', 'The Certified GRC & Financial Crime Specialist (CGFCS) is a professional certification designed to equip practitioners with deep, practical, and strategic knowledge across Governance, Risk, Compliance (GRC) and Financial Crime Prevention.') }}</textarea>
                                @error('programme_overview')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                           <div class="col-12">
                                <label class="form-label">Programme Architecture</label>
                                <textarea 
                                    id="editor1"
                                    name="programme_architecture"
                                    class="form-control @error('programme_architecture') is-invalid @enderror"
                                    rows="6"
                                    placeholder="Describe the programme tiers and structure..."
                                ></textarea>

                                @error('programme_architecture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Learning Outcomes (one per line) <span class="text-danger">*</span></label>
                                <textarea name="learning_outcomes" class="form-control @error('learning_outcomes') is-invalid @enderror" rows="8" 
                                          placeholder="By the end of this course, participants will be able to:
- Design and manage GRC frameworks
- Identify and assess enterprise and financial crime risks
- Implement compliance and AML programmes" required>{{ old('learning_outcomes', 'By the end of this course, participants will be able to:
Design and manage GRC frameworks
Identify and assess enterprise and financial crime risks
Implement compliance and AML programmes
Investigate and prevent financial crime
Apply technology in GRC and crime prevention
Uphold ethical governance and accountability') }}</textarea>
                                @error('learning_outcomes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Target Audience (one per line) <span class="text-danger">*</span></label>
                                <textarea name="target_audience" class="form-control @error('target_audience') is-invalid @enderror" rows="5" 
                                          placeholder="Compliance Officers
Risk Managers
Fraud & Financial Crime Analysts" required>{{ old('target_audience', 'Compliance Officers
Risk Managers
Fraud & Financial Crime Analysts
Internal Auditors
Regulators & Supervisors
Fintech & RegTech Professionals
Consultants and Advisors') }}</textarea>
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
                                          placeholder="Graduates may work as:
- Compliance Officer
- Risk Manager
- AML Analyst">{{ old('career_pathways', 'Graduates may work as:
Compliance Officer
Risk Manager
AML Analyst
Fraud Investigator
GRC Consultant
Regulatory Advisor') }}</textarea>
                                @error('career_pathways')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Assessment Structure (one per line)</label>
                                <textarea name="assessment_structure" class="form-control @error('assessment_structure') is-invalid @enderror" rows="4" 
                                          placeholder="Module quizzes
Practical assignments
Final examination">{{ old('assessment_structure', 'Module quizzes
Practical assignments
Final examination
Capstone project') }}</textarea>
                                @error('assessment_structure')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Code of Professional Conduct (one per line)</label>
                                <textarea name="code_of_conduct" class="form-control @error('code_of_conduct') is-invalid @enderror" rows="4" 
                                          placeholder="CGFCS holders must:
- Act with integrity
- Maintain confidentiality">{{ old('code_of_conduct', 'CGFCS holders must:
Act with integrity
Maintain confidentiality
Avoid conflicts of interest
Uphold laws and ethics
Commit to continuous learning') }}</textarea>
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
                                      placeholder="Example:
Module 1: Foundations of Governance, Risk and Compliance
This module provides the fundamental understanding of GRC concepts...

Objectives:
- Understand GRC concepts and history
- Explain the purpose of governance

Topics:
- Evolution of GRC
- Integrated GRC model
- Stakeholder theory

Case Study:
A multinational bank fails due to weak board oversight. Analyse where governance failed.

Exercise:
Map GRC responsibilities in your organisation.

Module 2: Corporate Governance and Ethics
This module covers corporate governance principles and ethical decision-making...

Objectives:
- Apply governance principles
- Promote ethical culture

Topics:
- Board structures
- Fiduciary duties
- Ethics and culture
- Conflicts of interest

Case Study:
Corporate scandal involving false reporting. Identify ethical breakdowns.

Exercise:
Draft a basic code of ethics outline." required>{{ old('bulk_modules', 'Module 1: Foundations of Governance, Risk and Compliance
This module introduces the core concepts of Governance, Risk, and Compliance (GRC) as an integrated framework.

Objectives:
Understand GRC concepts and history
Explain the purpose of governance
Define risk and compliance roles

Topics:
Evolution of GRC
Integrated GRC model
Stakeholder theory
Role of boards and executives

Case Study:
A multinational bank fails due to weak board oversight. Analyse where governance failed.

Exercise:
Map GRC responsibilities in your organisation.

Module 2: Corporate Governance and Ethics
This module covers the principles of corporate governance and the importance of ethical decision-making.

Objectives:
Apply governance principles
Promote ethical culture

Topics:
Board structures
Committees and charters
Codes of conduct
Conflicts of interest
Whistleblowing

Case Study:
Corporate scandal involving false reporting. Identify ethical breakdowns.

Exercise:
Draft a basic code of ethics outline.

Module 3: Enterprise Risk Management
Learn to identify, assess, and manage enterprise risks using established frameworks.

Objectives:
Identify and assess risks
Apply ERM frameworks

Topics:
COSO and ISO 31000
Risk appetite
Risk registers
Inherent vs residual risk
Risk treatment strategies

Case Study:
Technology failure impacts service delivery.

Exercise:
Create a simple risk register.

Module 4: Regulatory Compliance Frameworks
Understand global regulatory structures and build effective compliance programmes.

Objectives:
Understand global regulatory structures
Build compliance programmes

Topics:
Financial regulation overview
Compliance lifecycle
Policies and procedures
Monitoring and reporting
Regulatory inspections

Case Study:
Regulator fines a firm for reporting failures.

Exercise:
Design a compliance monitoring plan.

Module 5: Financial Crime Landscape
Explore different types of financial crimes and their impact on organizations.

Objectives:
Identify types of financial crime
Understand global impact

Topics:
Money laundering
Terrorist financing
Fraud and scams
Market abuse
Sanctions evasion

Case Study:
Ponzi scheme investigation.

Exercise:
Classify crimes in provided scenarios.

Module 6: Anti-Money Laundering & Counter-Terrorist Financing
Build comprehensive AML/CTF frameworks and conduct risk assessments.

Objectives:
Build AML/CTF frameworks
Conduct risk assessments

Topics:
FATF standards
Customer Due Diligence
KYC and EDD
Transaction monitoring
Suspicious Activity Reports

Case Study:
Bank fined for AML failures.

Exercise:
Perform a basic AML risk assessment.

Module 7: Fraud, Corruption and Bribery Prevention
Learn to detect, prevent, and investigate fraud, corruption, and bribery.

Objectives:
Detect and prevent fraud
Build anti-corruption programmes

Topics:
Fraud triangle
Internal controls
Bribery laws
Gifts and hospitality
Investigations

Case Study:
Procurement fraud case.

Exercise:
Design a fraud prevention checklist.

Module 8: Cybercrime and Digital Financial Crime
Understand cyber threats and protect digital systems from financial crime.

Objectives:
Understand cyber threats
Protect digital systems

Topics:
Phishing and ransomware
Identity theft
Crypto crime
Data protection
Incident response

Case Study:
Cyber attack on payment system.

Exercise:
Create a cyber risk response plan.

Module 9: Investigations and Enforcement
Learn proper investigation techniques and understand enforcement actions.

Objectives:
Conduct investigations
Understand enforcement actions

Topics:
Evidence collection
Interviews
Reporting
Regulatory actions
Prosecution process

Case Study:
Internal fraud investigation.

Exercise:
Draft an investigation plan.

Module 10: Technology, AI and the Future of GRC
Explore the role of technology, AI, and RegTech in modern GRC practices.

Objectives:
Apply technology to GRC
Understand AI risks

Topics:
RegTech and SupTech
Data analytics
AI governance
Automation
Future skills

Case Study:
AI used in transaction monitoring.

Exercise:
Evaluate a GRC technology tool.') }}</textarea>
                            @error('bulk_modules')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Or Upload Document</label>
                            <input type="file" name="document" class="form-control" accept=".txt,.doc,.docx,.pdf">
                            <small class="text-muted">Supported formats: TXT, DOC, DOCX, PDF (Max: 10MB)</small>
                        </div>
                        
                        <div class="form-text">
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

                <!-- Course Materials Upload -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Course Materials</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload Materials</label>
                            <input type="file" name="materials[]" class="form-control" multiple 
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar">
                            <small class="text-muted">Supported formats: PDF, DOC, DOCX, PPT, XLS, TXT, ZIP, RAR (Max: 10MB each)</small>
                        </div>
                        
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Material Type</label>
                                <select name="material_type" class="form-select">
                                    <option value="manual">Course Manual</option>
                                    <option value="presentation">Presentation</option>
                                    <option value="worksheet">Worksheet</option>
                                    <option value="template">Template</option>
                                    <option value="reference">Reference Material</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check mt-4 pt-2">
                                    <input class="form-check-input" type="checkbox" name="is_downloadable" id="is_downloadable" value="1" checked>
                                    <label class="form-check-label" for="is_downloadable">
                                        Allow students to download
                                    </label>
                                </div>
                            </div>
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
                                          rows="3" placeholder="Brief description for search engines (max 160 characters)" maxlength="160">{{ old('meta_description') }}</textarea>
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
                                <input type="text" name="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                       placeholder="grc certification, financial crime prevention, compliance training, risk management" value="{{ old('meta_keywords', 'grc certification, financial crime prevention, compliance training, risk management, aml certification, fraud prevention, corporate governance, regulatory compliance') }}">
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
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                            <iconify-icon icon="mdi:content-save" class="icon"></iconify-icon>
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
                            <h6 id="moduleCountPreview">Total Modules: 10</h6>
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
                                <small class="text-muted">Pre-filled with CGFCS content - modify as needed</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">All 10 modules are pre-loaded in the correct format</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Upload course manual PDF in the materials section</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Review module content before creating the course</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Set to "Draft" first, then publish when ready</small>
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
                                <span class="fw-medium" id="statsModules">10</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Hours:</span>
                                <span class="fw-medium" id="statsHours">40</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Level:</span>
                                <span class="fw-medium" id="statsLevel">Expert</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Format:</span>
                                <span class="fw-medium" id="statsFormat">Self-Paced</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Certification:</span>
                                <span class="fw-medium">CGFCS</span>
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
    
    #bulkModulesPreview {
        max-height: 400px;
        overflow-y: auto;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Document loaded - initializing course form');

     // Initialize CKEditor 5
    ClassicEditor
    .create(document.querySelector('#editor2'))
    .catch(error => { console.error(error); });
    ClassicEditor
    .create(document.querySelector('#editor1'), {
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'bulletedList', 'numberedList', '|',
                'alignment', '|',
                'link', 'imageUpload', 'blockQuote', 'insertTable', '|',
                'undo', 'redo', '|',
                'codeBlock', 'highlight', '|',
                'fontSize', 'fontColor', 'fontBackgroundColor'
            ]
        },
        language: 'en',
        image: {
            toolbar: [
                'imageTextAlternative',
                'imageStyle:inline',
                'imageStyle:block',
                'imageStyle:side'
            ]
        },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells'
            ]
        }
    })
    .catch(error => {
        console.error(error);
    });


    
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
            handleImagePreview(this, previewImage, imagePreview, noImagePlaceholder, 2);
        });
    }

    // Handle banner image preview
    if (bannerImageInput) {
        bannerImageInput.addEventListener('change', function(e) {
            console.log('Banner image input changed');
            handleImagePreview(this, previewBannerImage, bannerImagePreview, noBannerImagePlaceholder, 5);
        });
    }

    // Generic image preview handler
    function handleImagePreview(input, previewElement, previewContainer, placeholder, maxSizeMB) {
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
                placeholder.style.display = 'none';
            }
            reader.onerror = function() {
                alert('Error reading the image file');
                previewContainer.style.display = 'none';
                placeholder.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
            placeholder.style.display = 'block';
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
        const modules = parseInt(totalModulesInput.value) || 10;
        const hours = parseInt(totalHoursInput.value) || 40;
        const level = levelSelect ? levelSelect.options[levelSelect.selectedIndex].text : 'Expert';
        const format = formatSelect ? formatSelect.options[formatSelect.selectedIndex].text : 'Self-Paced';
        
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

    console.log('Video elements:', {
        videoTypeSelect: !!videoTypeSelect,
        videoUploadField: !!videoUploadField,
        videoUrlField: !!videoUrlField,
        videoPreview: !!videoPreview,
        videoPlayer: !!videoPlayer,
        videoFileInput: !!videoFileInput,
        videoUrlInput: !!videoUrlInput
    });

    // Function to show/hide video fields based on selection
    function updateVideoFields() {
        if (!videoTypeSelect) return;
        
        const selectedType = videoTypeSelect.value;
        console.log('Video type selected:', selectedType);
        
        // Always hide both fields first
        if (videoUploadField) videoUploadField.style.display = 'none';
        if (videoUrlField) videoUrlField.style.display = 'none';
        if (videoPreview) videoPreview.style.display = 'none';
        if (videoPlayer) videoPlayer.innerHTML = '';
        
        // Show appropriate field based on selection
        if (selectedType === 'upload') {
            if (videoUploadField) {
                videoUploadField.style.display = 'block';
                console.log('Showing upload field');
            }
        } else if (selectedType === 'youtube' || selectedType === 'vimeo') {
            if (videoUrlField) {
                videoUrlField.style.display = 'block';
                console.log('Showing URL field');
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
            console.log('Video type changed to:', this.value);
            updateVideoFields();
            
            // Clear any existing preview
            if (videoPlayer) {
                videoPlayer.innerHTML = '';
            }
        });
    }

    // Handle video URL input for YouTube/Vimeo
    if (videoUrlInput) {
        videoUrlInput.addEventListener('input', function() {
            const url = this.value.trim();
            const videoType = videoTypeSelect ? videoTypeSelect.value : 'none';
            
            if (!url || videoType === 'none' || videoType === 'upload') {
                if (videoPlayer) videoPlayer.innerHTML = '';
                if (videoPreview) videoPreview.style.display = 'none';
                return;
            }
            
            // Wait a bit before processing to avoid too many updates
            setTimeout(() => {
                updateVideoPreview(url, videoType);
            }, 500);
        });
        
        // Also handle blur event for immediate update
        videoUrlInput.addEventListener('blur', function() {
            const url = this.value.trim();
            const videoType = videoTypeSelect ? videoTypeSelect.value : 'none';
            
            if (url && (videoType === 'youtube' || videoType === 'vimeo')) {
                updateVideoPreview(url, videoType);
            }
        });
    }

    // Handle video file input
    if (videoFileInput) {
        videoFileInput.addEventListener('change', function(e) {
            console.log('Video file selected');
            const file = e.target.files[0];
            
            if (!file) {
                if (videoPlayer) videoPlayer.innerHTML = '';
                if (videoPreview) videoPreview.style.display = 'none';
                return;
            }
            
            // Show file info
            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            console.log(`File: ${file.name}, Size: ${fileSizeMB}MB, Type: ${file.type}`);
            
            // Validate file size (20MB max)
            if (file.size > 20 * 1024 * 1024) {
                alert(`File is too large (${fileSizeMB}MB). Maximum size is 20MB.`);
                this.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv', 'video/x-matroska'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid video file (MP4, MOV, AVI, WMV, MKV)');
                this.value = '';
                return;
            }
            
            // Show preview
            try {
                const url = URL.createObjectURL(file);
                if (videoPlayer) {
                    videoPlayer.innerHTML = `
                        <div class="text-center p-3">
                            <div class="mb-2">
                                <iconify-icon icon="mdi:video" class="icon-2x text-primary"></iconify-icon>
                            </div>
                            <div class="mb-2">
                                <strong>${file.name}</strong>
                            </div>
                            <div class="text-muted small mb-3">
                                ${fileSizeMB} MB • ${file.type}
                            </div>
                            <video controls style="width: 100%; max-height: 200px; border-radius: 8px;">
                                <source src="${url}" type="${file.type}">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    `;
                }
                
                if (videoPreview) {
                    videoPreview.style.display = 'block';
                }
                
                console.log('Video preview created successfully');
                
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
            
            console.log('Video preview updated:', embedUrl);
        } else {
            videoPlayer.innerHTML = '<p class="text-center text-muted py-4">Enter a valid video URL to see preview</p>';
            if (videoPreview) {
                videoPreview.style.display = 'block';
            }
        }
    }

    // Helper functions to extract video IDs
    function extractYouTubeId(url) {
        // Handle various YouTube URL formats
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
            console.log('Form submitted');
            
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
            
            // Validate bulk modules
            const bulkModulesTextarea = document.querySelector('textarea[name="bulk_modules"]');
            if (bulkModulesTextarea && !bulkModulesTextarea.value.trim()) {
                e.preventDefault();
                alert('Please enter module content in the bulk modules section');
                bulkModulesTextarea.focus();
                return;
            }
            
            // Confirm before submitting
            if (!confirm('Are you sure you want to create this course? This will create all modules from the bulk content.')) {
                e.preventDefault();
                return;
            }
            
            console.log('Form validation passed');
        });
    }

    // Module preview count
    function updateModuleCount() {
        const bulkModulesTextarea = document.querySelector('textarea[name="bulk_modules"]');
        if (bulkModulesTextarea) {
            bulkModulesTextarea.addEventListener('input', function() {
                const content = this.value;
                const moduleCount = (content.match(/Module\s+\d+:/gi) || []).length;
                if (moduleCount > 0) {
                    document.getElementById('moduleCountPreview').textContent = `Total Modules: ${moduleCount}`;
                    document.getElementById('statsModules').textContent = moduleCount;
                    document.getElementById('totalModules').value = moduleCount;
                }
            });
        }
    }
    
    updateModuleCount();

    console.log('All event listeners attached');


});
</script>
@endpush

