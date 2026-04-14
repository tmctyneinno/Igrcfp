@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Membership Tiers</h6>
        <a href="{{ route('admin.membership-tiers.create') }}" class="btn btn-primary">Add Tier</a>
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
                            <th>Active</th>
                            <th>Sort</th>
                            <th>Benefits</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tiers as $tier)
                            <tr>
                                <td>{{ $tier->name }}</td>
                                <td>
                                    <span class="badge {{ $tier->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $tier->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $tier->sort_order }}</td>
                                <td>{{ is_array($tier->benefits) ? count($tier->benefits) : 0 }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.membership-tiers.edit', $tier) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.membership-tiers.destroy', $tier) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this tier?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No tiers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $tiers->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>
@endsection
