@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Contact Messages</h6>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                        All <span class="ms-1">{{ $counts['all'] }}</span>
                    </a>
                    <a href="{{ route('admin.contacts.index', ['status' => 'new']) }}" class="btn btn-sm {{ request('status') === 'new' ? 'btn-warning' : 'btn-outline-warning' }}">
                        New <span class="ms-1">{{ $counts['new'] }}</span>
                    </a>
                    <a href="{{ route('admin.contacts.index', ['status' => 'in_progress']) }}" class="btn btn-sm {{ request('status') === 'in_progress' ? 'btn-info' : 'btn-outline-info' }}">
                        In Progress <span class="ms-1">{{ $counts['in_progress'] }}</span>
                    </a>
                    <a href="{{ route('admin.contacts.index', ['status' => 'resolved']) }}" class="btn btn-sm {{ request('status') === 'resolved' ? 'btn-success' : 'btn-outline-success' }}">
                        Resolved <span class="ms-1">{{ $counts['resolved'] }}</span>
                    </a>
                    <a href="{{ route('admin.contacts.index', ['status' => 'spam']) }}" class="btn btn-sm {{ request('status') === 'spam' ? 'btn-danger' : 'btn-outline-danger' }}">
                        Spam <span class="ms-1">{{ $counts['spam'] }}</span>
                    </a>
                </div>

                <form method="GET" class="d-flex gap-2">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search messages..." value="{{ request('search') }}">
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr>
                                <td>#{{ $message->id }}</td>
                                <td>
                                    <div class="fw-medium">{{ $message->full_name }}</div>
                                    <small class="text-muted d-block text-truncate" style="max-width: 280px;">
                                        {{ \Illuminate\Support\Str::limit($message->message, 70) }}
                                    </small>
                                </td>
                                <td>{{ $message->email }}</td>
                                <td>{{ $message->formatted_phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{
                                        $message->status === 'new' ? 'warning' :
                                        ($message->status === 'in_progress' ? 'info' :
                                        ($message->status === 'resolved' ? 'success' : 'danger'))
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $message->status)) }}
                                    </span>
                                </td>
                                <td>{{ $message->created_at->format('M d, Y g:i A') }}</td>
                                <td>
                                    <a href="{{ route('admin.contacts.show', $message) }}" class="btn btn-sm btn-primary">View</a>
                                </td> 
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No contact messages found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection
