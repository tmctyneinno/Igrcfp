@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Mentor Management</h6>
        <a href="{{ route('admin.mentors.create') }}" class="btn btn-primary">Add Mentor</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card h-100 p-0 radius-12">
        <div class="card-body p-24">
            <div class="table-responsive">
                <table class="table bordered-table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Domain</th>
                            <th>Region</th>
                            <th>Status</th>
                            <th>Availability</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mentors as $mentor)
                            <tr>
                                <td>{{ $mentor->user->name }}</td>
                                <td>{{ $mentor->domain }}</td>
                                <td>{{ $mentor->region }}</td>
                                <td>
                                    <span class="badge {{ $mentor->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $mentor->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $mentor->availability_status === 'taking' ? 'Taking' : 'Not taking' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.mentors.edit', $mentor) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('admin.mentors.toggle', $mentor) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            {{ $mentor->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No mentors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $mentors->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>
@endsection
