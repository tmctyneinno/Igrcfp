@extends('admin.layouts.app')

@section('title', 'Edit Document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Document</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.research.update', $research->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Document Title</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $research->title) }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $research->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Document Type</label>
                            <select name="document_type" class="form-control @error('document_type') is-invalid @enderror" required>
                                <option value="research" {{ old('document_type', $research->document_type) == 'research' ? 'selected' : '' }}>Research Content</option>
                                <option value="whitepaper" {{ old('document_type', $research->document_type) == 'whitepaper' ? 'selected' : '' }}>White Paper</option>
                            </select>
                            @error('document_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', $research->category) }}" placeholder="e.g., AML, Compliance, Risk">
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Current File</label>
                            <div class="border p-3 rounded bg-light">
                                <a href="{{ Storage::url($research->file_path) }}" target="_blank" class="text-decoration-none">
                                    <i class="ri-file-pdf-line me-1"></i> {{ $research->file_name }}
                                </a>
                                <span class="text-muted small ms-3">({{ number_format($research->file_size / 1024, 2) }} KB)</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Replace File (optional)</label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx">
                            <small class="text-muted">Leave empty to keep the current file. Max 10MB.</small>
                            @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_published" class="form-check-input" value="1" {{ old('is_published', $research->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label">Published</label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Document</button>
                            <a href="{{ route('admin.research.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection