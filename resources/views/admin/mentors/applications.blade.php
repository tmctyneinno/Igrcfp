@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <h6 class="fw-semibold mb-24">Mentor Applications</h6>

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
                            <th>User</th>
                            <th>Domain</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Feedback</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>{{ $application->user->name }}</td>
                                <td>{{ $application->domain }}</td>
                                <td>{{ ucfirst($application->status) }}</td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td>{{ $application->admin_feedback }}</td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('admin.mentor-applications.approve', $application) }}" class="d-inline">
                                        @csrf
                                        <input type="text" name="admin_feedback" class="form-control form-control-sm d-inline w-auto" placeholder="Feedback">
                                        <button type="submit" class="btn btn-sm btn-success mt-2">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.mentor-applications.decline', $application) }}" class="d-inline">
                                        @csrf
                                        <input type="text" name="admin_feedback" class="form-control form-control-sm d-inline w-auto" placeholder="Feedback">
                                        <button type="submit" class="btn btn-sm btn-outline-danger mt-2">Decline</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $applications->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>
@endsection
