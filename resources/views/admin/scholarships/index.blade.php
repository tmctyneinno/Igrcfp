{{-- resources/views/admin/scholarships/index.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Scholarship Applications</h6>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.scholarships.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                    <a href="{{ route('admin.scholarships.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">Pending</a>
                    <a href="{{ route('admin.scholarships.index', ['status' => 'under_review']) }}" class="btn btn-sm {{ request('status') == 'under_review' ? 'btn-info' : 'btn-outline-info' }}">Under Review</a>
                    <a href="{{ route('admin.scholarships.index', ['status' => 'accepted']) }}" class="btn btn-sm {{ request('status') == 'accepted' ? 'btn-success' : 'btn-outline-success' }}">Accepted</a>
                    <a href="{{ route('admin.scholarships.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">Rejected</a>
                </div>
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-primary">Search</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Country</th>
                            <th>Programmes</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                        <tr>
                            <td>#{{ $app->id }}</td>
                            <td>{{ $app->full_name }}</td>
                            <td>{{ $app->email }}</td>
                            <td>{{ $app->country_of_residence }}</td>
                            <td>
                                @foreach($app->preferred_programmes as $prog)
                                    <span class="badge bg-light text-dark">{{ $prog }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-{{
                                    $app->status == 'pending' ? 'warning' :
                                    ($app->status == 'under_review' ? 'info' :
                                    ($app->status == 'accepted' ? 'success' : 'danger'))
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                </span>
                            </td>
                            <td>{{ $app->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.scholarships.show', $app->id) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection