@extends('admin.layouts.app')

@section('title', 'Upload Research / White Paper')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Upload New Document</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.research.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Document Title</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Document Type</label>
                            <select name="document_type" class="form-control @error('document_type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                <option value="research" {{ old('document_type') == 'research' ? 'selected' : '' }}>Research Content</option>
                                <option value="whitepaper" {{ old('document_type') == 'whitepaper' ? 'selected' : '' }}>White Paper</option>
                            </select>
                            @error('document_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}" placeholder="e.g., AML, Compliance, Risk">
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Document File (PDF, DOC, DOCX - Max 10MB)</label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                            @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_published" class="form-check-input" value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                                <label class="form-check-label">Publish immediately</label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Upload Document</button>
                            <a href="{{ route('admin.research.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection