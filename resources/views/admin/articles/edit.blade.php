@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Edit Article</h6>
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
            <li class="fw-medium">Edit Article</li>
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

    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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
                                       placeholder="Enter article title" value="{{ old('title', $article->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Excerpt <span class="text-danger">*</span></label>
                                <textarea name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" 
                                          rows="3" placeholder="Brief summary of the article (max 300 characters)" required>{{ old('excerpt', $article->excerpt) }}</textarea>
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
                                <input type="hidden" name="content" id="content" value="{{ old('content', $article->content) }}">
                                
                                <!-- CKEditor 5 Container -->
                                <div id="editor" class="form-control @error('content') is-invalid @enderror" 
                                    style="min-height: 500px; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.5rem;">
                                    {!! old('content', $article->content) !!}
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
                                
                                @if($article->image_path)
                                    <div class="mt-2">
                                        <small>Current Image:</small>
                                        <img src="{{ asset('storage/' . $article->image_path) }}" alt="Current article image" 
                                             class="img-fluid rounded mt-1" style="max-height: 100px;">
                                    </div>
                                @endif
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
                                <div class="input-group">
                                    <select name="article_category_id" id="article_category_id" class="form-select @error('article_category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('article_category_id', $article->article_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                        <iconify-icon icon="mdi:plus"></iconify-icon>
                                    </button>
                                </div>
                                @error('article_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Author <span class="text-danger">*</span></label>
                                <select name="author_id" class="form-select @error('author_id') is-invalid @enderror" required>
                                    <option value="">Select Author</option>
                                    @foreach($authors as $author)
                                        <option value="{{ $author->id }}" {{ old('author_id', $article->author_id) == $author->id ? 'selected' : '' }}>
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
                                @php
                                    $published_at = old('published_at', $article->published_at);
                                    if ($published_at) {
                                        $published_at = \Carbon\Carbon::parse($published_at)->format('Y-m-d\TH:i');
                                    }
                                @endphp
                                <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" 
                                       value="{{ $published_at }}">
                                <small class="text-muted">Leave empty to save as draft</small>
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Read Time (minutes) <span class="text-danger">*</span></label>
                                <input type="number" name="read_time" class="form-control @error('read_time') is-invalid @enderror" 
                                       value="{{ old('read_time', $article->read_time) }}" min="1" max="60" required>
                                @error('read_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Tags</label>
                                <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror" 
                                       placeholder="compliance, risk-management, regulatory-updates" value="{{ old('tags', $article->tags) }}">
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
                                       placeholder="Optimized title for search engines" value="{{ old('meta_title', $article->meta_title) }}">
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
                                          rows="3" placeholder="Brief description for search engines (max 160 characters)">{{ old('meta_description', $article->meta_description) }}</textarea>
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
                                    <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status', $article->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" 
                                           {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
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
                                            Update Article
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
                            <div id="imagePreview" class="mb-3" style="{{ $article->image_path ? 'display: block;' : 'display: none;' }}">
                                @if($article->image_path)
                                    <img id="previewImage" src="{{ asset('storage/' . $article->image_path) }}" alt="Article image preview" 
                                         class="img-fluid rounded-8 border" style="max-height: 200px;">
                                @else
                                    <img id="previewImage" src="#" alt="Article image preview" 
                                         class="img-fluid rounded-8 border" style="max-height: 200px;">
                                @endif
                                <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImage">
                                    Remove
                                </button>
                            </div>
                            <div id="noImagePlaceholder" class="text-muted py-4" style="{{ $article->image_path ? 'display: none;' : 'display: block;' }}">
                                <p class="mb-0">No image selected</p>
                                <small class="text-muted">Preview will appear here</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Article Information -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Article Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted">Created:</span>
                                <span class="fw-medium">{{ $article->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted">Last Updated:</span>
                                <span class="fw-medium">{{ $article->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted">Views:</span>
                                <span class="fw-medium">{{ number_format($article->views) }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted">Slug:</span>
                                <span class="fw-medium text-truncate" style="max-width: 150px;">{{ $article->slug }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card mt-24">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.articles.show', $article->id) }}" class="btn btn-outline-info">
                                Preview Article
                            </a>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                Delete Article
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Create Category Modal (same as before) -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <!-- Same as before, no changes needed -->
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this article? This action cannot be undone.</p>
                <p class="fw-semibold">"{{ $article->title }}"</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Article</button>
                </form>
            </div>
        </div>
    </div>
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
                // If no file selected but there's an existing image, keep showing it
                if (!previewImage.src.includes('{{ asset("storage/") }}')) {
                    imagePreview.style.display = 'none';
                    noImagePlaceholder.style.display = 'block';
                }
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
            
            // Also add a hidden field to indicate image removal if needed
            // You might want to add this in your form:
            // <input type="hidden" name="remove_image" id="removeImageField" value="0">
            // And set it to 1 here:
            // document.getElementById('removeImageField').value = '1';
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

    // Create Category Modal functionality (same as before)
    const createCategoryForm = document.getElementById('createCategoryForm');
    const categorySelect = document.getElementById('article_category_id');
    const createCategoryModal = document.getElementById('createCategoryModal');
    const saveCategoryBtn = document.getElementById('saveCategoryBtn');
    const categorySaveText = document.getElementById('categorySaveText');
    const categoryLoadingSpinner = document.getElementById('categoryLoadingSpinner');

    if (createCategoryForm) {
        createCategoryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            saveCategoryBtn.disabled = true;
            categorySaveText.classList.add('d-none');
            categoryLoadingSpinner.classList.remove('d-none');
            
            // Clear previous errors
            document.getElementById('category_name_error').textContent = '';
            document.getElementById('category_name').classList.remove('is-invalid');
            
            // Submit form via AJAX
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new category to select
                    const newOption = new Option(data.category.name, data.category.id, true, true);
                    categorySelect.appendChild(newOption);
                    
                    // Reset form
                    createCategoryForm.reset();
                    document.getElementById('category_is_active').checked = true;
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(createCategoryModal);
                    modal.hide();
                    
                    // Show success message
                    showToast('success', 'Category created successfully!');
                } else {
                    // Show validation errors
                    if (data.errors && data.errors.name) {
                        document.getElementById('category_name').classList.add('is-invalid');
                        document.getElementById('category_name_error').textContent = data.errors.name[0];
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred. Please try again.');
            })
            .finally(() => {
                // Reset button state
                saveCategoryBtn.disabled = false;
                categorySaveText.classList.remove('d-none');
                categoryLoadingSpinner.classList.add('d-none');
            });
        });
    }

    // Reset modal when closed
    if (createCategoryModal) {
        createCategoryModal.addEventListener('hidden.bs.modal', function () {
            createCategoryForm.reset();
            document.getElementById('category_is_active').checked = true;
            document.getElementById('category_name').classList.remove('is-invalid');
            document.getElementById('category_name_error').textContent = '';
        });
    }

    // Toast notification function
    function showToast(type, message) {
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            // Create toast container if it doesn't exist
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '1055';
            document.body.appendChild(container);
        }
        
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        document.getElementById('toast-container').insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
        toast.show();
        
        // Remove toast from DOM after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function () {
            this.remove();
        });
    }

    // Initialize CKEditor 5
    ClassicEditor
        .create(document.querySelector('#editor'), {
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
            },
            licenseKey: '',
        })
        .then(editor => {
            window.editor = editor;
            
            // Update hidden input when editor content changes
            editor.model.document.on('change:data', () => {
                document.getElementById('content').value = editor.getData();
            });
            
            // Initialize with existing content
            document.getElementById('content').value = editor.getData();
        })
        .catch(error => {
            console.error(error);
        });
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

.input-group .btn-outline-primary {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.toast-container {
    z-index: 1056;
}
</style>
@endpush