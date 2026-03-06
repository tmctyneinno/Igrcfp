@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">{{ isset($category) ? 'Edit' : 'Create' }} Category</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.course-categories.index') }}" class="hover-text-primary">Categories</a>
            </li>
            <li>-</li>
            <li class="fw-medium">{{ isset($category) ? 'Edit' : 'Create' }}</li>
        </ul>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center gap-2">
                <iconify-icon icon="mdi:check-circle" class="icon text-xl"></iconify-icon>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center gap-2">
                <iconify-icon icon="mdi:alert-circle" class="icon text-xl"></iconify-icon>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center gap-2 mb-2">
                <iconify-icon icon="mdi:alert-circle" class="icon text-xl"></iconify-icon>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="mb-0 ps-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h6 class="card-title mb-0">{{ isset($category) ? 'Edit Category' : 'Create New Category' }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ isset($category) ? route('admin.course-categories.update', $category) : route('admin.course-categories.store') }}" 
                  method="POST">
                @csrf
                @if(isset($category)) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $category->name ?? '') }}" 
                               placeholder="Enter category name" required>
                        @error('name') 
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">The name will be used to generate the slug automatically</small>
                    </div>

                    
                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4" placeholder="Enter category description">{{ old('description', $category->description ?? '') }}</textarea>
                        @error('description') 
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                               value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0" step="1">
                        @error('sort_order') 
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Lower numbers appear first</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                   value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <span class="text-dark fw-medium">Active</span>
                                <p class="text-muted small mb-0">Enable this category for use in courses</p>
                            </label>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <hr>
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary px-4">
                                {{ isset($category) ? 'Update' : 'Create' }} Category
                            </button>
                            <a href="{{ route('admin.course-categories.index') }}" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                            @if(isset($category))
                                <button type="button" class="btn btn-outline-danger ms-auto" 
                                        onclick="confirmDelete()">
                                    Delete Category
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            @if(isset($category))
            <!-- Delete Form (hidden) -->
            <form id="delete-form" action="{{ route('admin.course-categories.destroy', $category) }}" 
                  method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .alert {
        border-left-width: 4px;
    }
    .alert-success {
        border-left-color: #28a745;
        background-color: #f0fff4;
    }
    .alert-danger {
        border-left-color: #dc3545;
        background-color: #fff5f5;
    }
    .input-group-text {
        background-color: #f8f9fa;
    }
    .input-group-text .icon {
        font-size: 1.2rem;
    }
    hr {
        opacity: 0.2;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Preview icon when typing
    const iconInput = document.querySelector('input[name="icon"]');
    const iconPreview = document.querySelector('.input-group-text iconify-icon');
    
    if (iconInput && iconPreview) {
        iconInput.addEventListener('input', function() {
            const iconName = this.value || 'mdi:folder';
            iconPreview.setAttribute('icon', iconName);
        });
    }
});

// Confirm delete
function confirmDelete() {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush