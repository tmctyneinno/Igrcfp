@extends('admin.layouts.app')

@section('title', "{$chapter->region} - Leadership Management")

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Manage Leadership: {{ $chapter->region }}</h5>
                    <a href="{{ route('admin.chapters.index') }}" class="btn btn-light">
                        <i class="ri-arrow-left-line me-1"></i> Back to Chapters
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Add New Leader Form -->
                    <div class="border p-4 rounded mb-4 bg-light">
                        <h6 class="mb-3">Add New Leader</h6>
                        <form action="{{ route('admin.chapters.leadership.store', $chapter) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="role" class="form-control" placeholder="Role e.g. President" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="email" name="email" class="form-control" placeholder="Email">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="phone" class="form-control" placeholder="Phone">
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Leaders List -->
                    <h6 class="mb-3">Current Leadership Team</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaders as $leader)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $leader->name }}</td>
                                        <td>{{ $leader->role }}</td>
                                        <td>{{ $leader->email ?? '—' }}</td>
                                        <td>{{ $leader->phone ?? '—' }}</td>
                                        <td>
                                            <form action="{{ route('admin.chapters.leadership.destroy', [$chapter, $leader]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this leader?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3">No leadership members added yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection