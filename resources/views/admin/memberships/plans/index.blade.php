@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Membership Plans</h6>
        <a href="{{ route('admin.membership-plans.create') }}" class="btn btn-primary">Add Plan</a>
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
                            <th>Tier</th>
                            <th>Price</th>
                            <th>Interval</th>
                            <th>Active</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td>{{ $plan->name }}</td>
                                <td>{{ $plan->tier?->name }}</td>
                                <td>{{ $plan->currency }} {{ number_format($plan->price, 2) }}</td>
                                <td>{{ $plan->billing_interval }}</td>
                                <td>
                                    <span class="badge {{ $plan->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.membership-plans.edit', $plan) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.membership-plans.destroy', $plan) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this plan?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No plans found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $plans->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>
@endsection
