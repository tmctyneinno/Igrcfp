@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create New Article</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li> 
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.articles.index') }}" class="hover-text-primary">Articles</a>
            </li>
            <li>-</li>
            <li class="fw-medium">Create Article</li>
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

    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row gy-4">
            <div class="col-lg-8">
                <!-- Article Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Article Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Article Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       placeholder="Enter article title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            

                            <div class="col-12">
                                <label class="form-label">Excerpt <span class="text-danger">*</span></label>
                                <textarea name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" 
                                          rows="3" placeholder="Brief summary of the article (max 300 characters)" required>{{ old('excerpt') }}</textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Appears in article listings</small>
                                    <small class="excerpt-count text-muted">0/300</small>
                                </div>
                                @error('excerpt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Content <span class="text-danger">*</span></label>
                                
                                <!-- Hidden input to store the HTML -->
                                <input type="hidden" name="content" id="content" value="{{ old('content') }}">
                                
                                <!-- CKEditor 5 Container -->
                                <div id="editor" class="form-control @error('content') is-invalid @enderror" 
                                    style="min-height: 500px; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.5rem;">
                                    {!! old('content') !!}
                                </div>
                                
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Featured Image</label>
                                <input class="form-control @error('image') is-invalid @enderror" 
                                       type="file" name="image" id="articleImage" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                <p class="text-sm mt-1 mb-0 text-muted">
                                    Recommended size: 1200x630px. Supported formats: JPG, PNG, GIF, WEBP. Max size: 2MB
                                </p>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Article Metadata -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Article Metadata</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
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

                            <div class="col-md-6">
                                <label class="form-label">Author <span class="text-danger">*</span></label>
                                <select name="author_id" class="form-select @error('author_id') is-invalid @enderror" required>
                                    <option value="">Select Author</option>
                                    @foreach($authors as $author)
                                        <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                            {{ $author->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('author_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Publish Date & Time</label>
                                <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" 
                                       value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                                <small class="text-muted">Leave empty to save as draft</small>
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Read Time (minutes) <span class="text-danger">*</span></label>
                                <input type="number" name="read_time" class="form-control @error('read_time') is-invalid @enderror" 
                                       value="{{ old('read_time', 5) }}" min="1" max="60" required>
                                @error('read_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Tags</label>
                                <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror" 
                                       placeholder="compliance, risk-management, regulatory-updates" value="{{ old('tags') }}">
                                <small class="text-muted">Separate tags with commas</small>
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror" 
                                       placeholder="Optimized title for search engines" value="{{ old('meta_title') }}">
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Recommended: 50-60 characters</small>
                                    <small class="meta-title-count text-muted">0/60</small>
                                </div>
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" 
                                          rows="3" placeholder="Brief description for search engines (max 160 characters)">{{ old('meta_description') }}</textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Recommended: 150-160 characters</small>
                                    <small class="meta-description-count text-muted">0/160</small>
                                </div>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Article Settings -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Article Settings</h6>
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
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Feature this article
                                    </label>
                                    <small class="text-muted d-block mt-1">Featured articles appear prominently on the news page</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border-top pt-3 mt-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                            <iconify-icon icon="mdi:content-save" class="icon"></iconify-icon>
                                            Save Article
                                        </button>
                                        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">
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
                        <h6 class="card-title mb-0">Featured Image Preview</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div id="imagePreview" class="mb-3" style="display: none;">
                                <img id="previewImage" src="#" alt="Article image preview" 
                                     class="img-fluid rounded-8 border" style="max-height: 200px;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImage">
                                    <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                                    Remove
                                </button>
                            </div>
                            <div id="noImagePlaceholder" class="text-muted py-4">
                                <iconify-icon icon="mdi:newspaper-variant-outline" class="icon-3x mb-2"></iconify-icon>
                                <p class="mb-0">No image selected</p>
                                <small class="text-muted">Preview will appear here</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Tips -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Writing Tips</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Use clear, compelling headlines that summarize the article</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Include relevant keywords naturally in content and meta tags</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Add 3-5 relevant tags for better categorization</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Use headings (H2, H3) to structure your content</small>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <iconify-icon icon="mdi:lightbulb-on-outline" class="icon text-warning mt-1"></iconify-icon>
                                <small class="text-muted">Save as draft to review before publishing</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Articles -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Recent Articles</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            @forelse($recentArticles as $recent)
                                <div class="d-flex align-items-start gap-2">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $recent->image_url }}" alt="{{ $recent->title }}" 
                                             class="rounded-4" style="width: 40px; height: 40px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <a href="{{ route('admin.articles.edit', $recent->id) }}" 
                                           class="text-dark hover-text-primary d-block text-sm mb-1 line-clamp-2">
                                            {{ Str::limit($recent->title, 50) }}
                                        </a>
                                        <small class="text-muted d-flex align-items-center gap-1">
                                            <iconify-icon icon="mdi:calendar-outline" class="icon"></iconify-icon>
                                            {{ $recent->published_at?->format('M d, Y') ?? 'Draft' }}
                                        </small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3">
                                    <iconify-icon icon="mdi:newspaper-remove-outline" class="icon-2x mb-2"></iconify-icon>
                                    <p class="mb-0">No articles yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    const imageInput = document.querySelector('input[name="image"]');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const noImagePlaceholder = document.getElementById('noImagePlaceholder');
    const removeImageBtn = document.getElementById('removeImage');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }

                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, PNG, GIF, WEBP)');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    imagePreview.style.display = 'block';
                    noImagePlaceholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
                noImagePlaceholder.style.display = 'block';
            }
        });
    }

    // Remove image button
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            if (imageInput) {
                imageInput.value = '';
            }
            imagePreview.style.display = 'none';
            noImagePlaceholder.style.display = 'block';
        });
    }

    // Character count for excerpt
    const excerptTextarea = document.querySelector('textarea[name="excerpt"]');
    const excerptCount = document.querySelector('.excerpt-count');

    if (excerptTextarea && excerptCount) {
        excerptTextarea.addEventListener('input', function() {
            const length = this.value.length;
            excerptCount.textContent = length + '/300';
            
            if (length > 300) {
                excerptCount.style.color = '#dc3545';
                this.value = this.value.substring(0, 300);
                excerptCount.textContent = '300/300';
            } else if (length > 280) {
                excerptCount.style.color = '#ffc107';
            } else {
                excerptCount.style.color = '#6c757d';
            }
        });

        // Initialize count
        excerptTextarea.dispatchEvent(new Event('input'));
    }

    // Character count for meta title
    const metaTitleInput = document.querySelector('input[name="meta_title"]');
    const metaTitleCount = document.querySelector('.meta-title-count');

    if (metaTitleInput && metaTitleCount) {
        metaTitleInput.addEventListener('input', function() {
            const length = this.value.length;
            metaTitleCount.textContent = length + '/60';
            
            if (length > 60) {
                metaTitleCount.style.color = '#dc3545';
            } else if (length > 50) {
                metaTitleCount.style.color = '#ffc107';
            } else {
                metaTitleCount.style.color = '#6c757d';
            }
        });

        // Initialize count
        metaTitleInput.dispatchEvent(new Event('input'));
    }

    // Character count for meta description
    const metaDescription = document.querySelector('textarea[name="meta_description"]');
    const metaDescriptionCount = document.querySelector('.meta-description-count');

    if (metaDescription && metaDescriptionCount) {
        metaDescription.addEventListener('input', function() {
            const length = this.value.length;
            metaDescriptionCount.textContent = length + '/160';
            
            if (length > 160) {
                metaDescriptionCount.style.color = '#dc3545';
            } else if (length > 150) {
                metaDescriptionCount.style.color = '#ffc107';
            } else {
                metaDescriptionCount.style.color = '#6c757d';
            }
        });

        // Initialize count
        metaDescription.dispatchEvent(new Event('input'));
    }

    // Auto-generate slug from title
    const titleInput = document.querySelector('input[name="title"]');
    const slugInput = document.querySelector('input[name="slug"]');

    if (titleInput && slugInput && !slugInput.value) {
        titleInput.addEventListener('blur', function() {
            if (!slugInput.value) {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/--+/g, '-')
                    .trim();
                slugInput.value = slug;
            }
        });
    }

    
});
</script>
@endpush

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ck-editor__editable {
    min-height: 400px;
}

#noImagePlaceholder {
    border: 2px dashed #dee2e6;
    border-radius: 0.5rem;
}

#removeImage {
    width: 100%;
}
</style>
@endpush