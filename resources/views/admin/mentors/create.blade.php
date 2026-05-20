@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <h6 class="fw-semibold mb-24">Create Mentor Profile</h6>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.mentors.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">User *</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                @include('admin.mentors.partials.form', ['mentor' => null])
                <button type="submit" class="btn btn-primary">Save Mentor</button>
                <a href="{{ route('admin.mentors.index') }}" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
