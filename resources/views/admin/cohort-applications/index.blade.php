@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Cohort Applications</h6>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.cohort-applications.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                    <a href="{{ route('admin.cohort-applications.index', ['status' => 'new']) }}" class="btn btn-sm {{ request('status') == 'new' ? 'btn-warning' : 'btn-outline-warning' }}">New</a>
                    <a href="{{ route('admin.cohort-applications.index', ['status' => 'reviewing']) }}" class="btn btn-sm {{ request('status') == 'reviewing' ? 'btn-info' : 'btn-outline-info' }}">Reviewing</a>
                    <a href="{{ route('admin.cohort-applications.index', ['status' => 'admitted']) }}" class="btn btn-sm {{ request('status') == 'admitted' ? 'btn-success' : 'btn-outline-success' }}">Admitted</a>
                    <a href="{{ route('admin.cohort-applications.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">Rejected</a>
                </div>

                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search applicant or email" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-primary">Search</button>
                </form>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Cohort</th>
                            <th>Level</th>
                            <th>Country</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>{{ $application->id }}</td>
                                <td>{{ $application->full_name }}</td>
                                <td>{{ $application->email }}</td>
                                <td>{{ $application->cohort }}</td>
                                <td>{{ $application->level }}</td>
                                <td>{{ $application->country }}</td>
                                <td>
                                    <span class="badge bg-{{
                                        $application->status == 'new' ? 'secondary' :
                                        ($application->status == 'reviewing' ? 'info' :
                                        ($application->status == 'admitted' ? 'success' :
                                        ($application->status == 'rejected' ? 'danger' : 'warning')))
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.cohort-applications.show', $application->id) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No cohort applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection
