@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <h6 class="fw-semibold mb-24">Membership Approvals</h6>

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
                            <th>Plan</th>
                            <th>Tier</th>
                            <th>Purchased</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($memberships as $membership)
                            <tr>
                                <td>{{ $membership->user->name }}</td>
                                <td>{{ $membership->plan?->name }}</td>
                                <td>{{ $membership->plan?->tier?->name }}</td>
                                <td>{{ optional($membership->purchased_at)->format('M d, Y') ?? $membership->created_at->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('admin.membership-approvals.approve', $membership) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.membership-approvals.decline', $membership) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Decline</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No pending memberships.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $memberships->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>
@endsection
