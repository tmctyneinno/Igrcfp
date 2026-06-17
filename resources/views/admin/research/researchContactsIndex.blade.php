@extends('admin.layouts.app')

@section('title', 'Research Contact Submissions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Research Contact Submissions</h4>
                    <a href="{{ route('admin.research.research-contacts.export.csv') }}" class="btn btn-success">
                        <i class="ri-file-excel-line me-1"></i> Export CSV
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Search Filter --}}
                    <form action="{{ route('admin.research.research-contacts.index') }}" method="GET" class="mb-4 p-3 bg-light rounded">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-8">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by name, email, organisation or document..." 
                                       value="{{ $filters['search'] ?? '' }}">
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-50">
                                    <i class="ri-filter-line me-1"></i> Search
                                </button>
                                <a href="{{ route('admin.research.research-contacts.index') }}" class="btn btn-secondary w-50">
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
                                    <th>Full Name</th>
                                    <th>Organisation</th>
                                    <th>Email</th>
                                    <th>Document</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $index => $contact)
                                    <tr>
                                        <td>{{ $contacts->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $contact->full_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $contact->title }}</small>
                                        </td>
                                        <td>{{ $contact->organisation }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td>
                                            <span class="d-inline-block text-truncate" style="max-width: 200px;">
                                                {{ $contact->document_title }}
                                            </span>
                                            <br>
                                            <small class="text-muted">ID: {{ $contact->document_id }}</small>
                                        </td>
                                        <td>
                                            {{ $contact->created_at->format('d M Y') }}
                                            <br>
                                            <small class="text-muted">{{ $contact->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.research.research-contacts.show', $contact->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="ri-eye-line"></i> View
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            No submissions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $contacts->appends($filters)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection