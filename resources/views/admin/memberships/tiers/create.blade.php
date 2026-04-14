@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <h6 class="fw-semibold mb-24">Create Membership Tier</h6>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.membership-tiers.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Benefits (one per line)</label>
                    <textarea name="benefits" class="form-control" rows="4">{{ old('benefits') }}</textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control">
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Tier</button>
                <a href="{{ route('admin.membership-tiers.index') }}" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
