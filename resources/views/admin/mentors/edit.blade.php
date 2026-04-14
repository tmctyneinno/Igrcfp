@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <h6 class="fw-semibold mb-24">Edit Mentor Profile</h6>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.mentors.update', $mentor) }}">
                @csrf
                @method('PUT')
                @include('admin.mentors.partials.form', ['mentor' => $mentor])
                <button type="submit" class="btn btn-primary">Update Mentor</button>
                <a href="{{ route('admin.mentors.index') }}" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
