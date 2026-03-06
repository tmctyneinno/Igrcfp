

@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">{{ isset($category) ? 'Edit' : 'Create' }} Category</h6>
        <ul class="d-flex align-items-center gap-2">
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li>-</li>
            <li><a href="{{ route('admin.course-categories.index') }}">Categories</a></li>
            <li>-</li>
            <li>{{ isset($category) ? 'Edit' : 'Create' }}</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ isset($category) ? route('admin.course-categories.update', $category) : route('admin.course-categories.store') }}" 
                  method="POST">
                @csrf
                @if(isset($category)) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $category->name ?? '') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                   

                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3">{{ old('description', $category->description ?? '') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                               value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
                        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                   value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($category) ? 'Update' : 'Create' }} Category
                        </button>
                        <a href="{{ route('admin.course-categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection