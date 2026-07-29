@extends('admin.layouts.app')

@section('title', 'Research & White Papers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Research & White Papers</h4>
                    @if(auth()->guard('admin')->user()?->isAdmin())
                        <a href="{{ route('admin.research.create') }}" class="btn btn-primary">
                            <i class="ri-upload-line me-1"></i> Upload New
                        </a>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Filter Form --}}
                    <form action="{{ route('admin.research.index') }}" method="GET" class="mb-4 p-3 bg-light rounded">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Document Type</label>
                                <select name="type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="research" {{ request('type') == 'research' ? 'selected' : '' }}>Research Content</option>
                                    <option value="whitepaper" {{ request('type') == 'whitepaper' ? 'selected' : '' }}>White Paper</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-control">
                                    <option value="">All Categories</option>
                                    @php
                                        $categories = \App\Models\Research::distinct()->whereNotNull('category')->pluck('category');
                                    @endphp
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-50">
                                    <i class="ri-filter-line me-1"></i> Filter
                                </button>
                                <a href="{{ route('admin.research.index') }}" class="btn btn-secondary w-50">
                                    <i class="ri-refresh-line me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>File</th>
                                    <th>Status</th>
                                    <th>Uploaded</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $index => $doc)
                                    <tr>
                                        <td>{{ $documents->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $doc->title }}</strong>
                                            @if($doc->description)
                                                <p class="text-muted small mb-0">{{ Str::limit($doc->description, 80) }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            @if($doc->document_type === 'research')
                                                <span class="badge bg-info">Research</span>
                                            @else
                                                <span class="badge bg-primary">White Paper</span>
                                            @endif
                                        </td>
                                        <td>{{ $doc->category ?? 'Uncategorized' }}</td>
                                        <td>
                                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-decoration-none">
                                                <i class="ri-file-pdf-line me-1"></i> View / Download
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ number_format($doc->file_size / 1024, 2) }} KB</small>
                                        </td>
                                        <td>
                                            @if($doc->is_published)
                                                <span class="badge bg-success">Published</span>
                                            @else
                                                <span class="badge bg-secondary">Draft</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $doc->created_at->format('d M Y') }}
                                            <br>
                                            <small class="text-muted">by {{ $doc->admin->name ?? 'Admin' }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                @if(auth()->guard('admin')->user()?->isAdmin())
                                                    <a href="{{ route('admin.research.edit', $doc->id) }}" class="btn btn-sm btn-outline-warning">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    <form action="{{ route('admin.research.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            No documents uploaded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
