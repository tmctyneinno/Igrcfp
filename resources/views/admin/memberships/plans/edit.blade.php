@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <h6 class="fw-semibold mb-24">Edit Membership Plan</h6>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.membership-plans.update', $plan) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Tier *</label>
                    <select name="tier_id" class="form-select" required>
                        @foreach($tiers as $tier)
                            <option value="{{ $tier->id }}" @selected($plan->tier_id === $tier->id)>{{ $tier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Plan Name *</label>
                    <input type="text" name="name" value="{{ old('name', $plan->name) }}" class="form-control" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Price *</label>
                        <input type="number" name="price" value="{{ old('price', $plan->price) }}" class="form-control" step="0.01" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Currency *</label>
                        <input type="text" name="currency" value="{{ old('currency', $plan->currency) }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Duration (months)</label>
                        <input type="number" name="duration_months" value="{{ old('duration_months', $plan->duration_months) }}" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Billing Interval *</label>
                    <input type="text" name="billing_interval" value="{{ old('billing_interval', $plan->billing_interval) }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Benefits (one per line)</label>
                    <textarea name="benefits" class="form-control" rows="4">{{ old('benefits', is_array($plan->benefits) ? implode("\n", $plan->benefits) : '') }}</textarea>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update Plan</button>
                <a href="{{ route('admin.membership-plans.index') }}" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
