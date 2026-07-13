@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <!-- Add this success message display -->
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

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Scholarship Applications</h6>
    </div>


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
                            <th width="60">ID</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Country</th>
                            <th>Programmes</th>
                            <th width="100">Status</th>
                            <th width="110">Date</th>
                            <th width="160" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                        <tr>
                            <td>
                                {{ $loop->iteration + ($applications->currentPage() - 1) * $applications->perPage() }}
                            </td>
                            <td>{{ $app->full_name }}</td>
                            <td>{{ $app->email }}</td>
                            <td>{{ $app->country_of_residence }}</td>
                            <td>
                                @foreach($app->preferred_programmes as $prog)
                                    <span class="badge bg-light text-dark me-1 mb-1">{{ $prog }}</span>
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
                            <td class="text-nowrap">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.scholarships.show', $app->id) }}" class="btn btn-sm btn-primary">View</a>
                                    <a href="{{ route('admin.scholarships.download-pdf', $app->id) }}" class="btn btn-sm btn-outline-success" target="_blank">PDF</a>
                                </div>
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
@push('scripts')
<script>
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 5000);
</script>
@endpush