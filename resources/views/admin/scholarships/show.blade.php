{{-- resources/views/admin/scholarships/show.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Scholarship Application #{{ $application->id }}</h6>
        <a href="{{ route('admin.scholarships.index') }}" class="btn btn-outline-primary">Back to List</a>
    </div>

    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Applicant Details</h6></div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr><th width="200">Full Name</th><td>{{ $application->full_name }}</td></tr>
                        <tr><th>Email</th><td>{{ $application->email }}</td></tr>
                        <tr><th>Phone</th><td>{{ $application->phone_number }}</td></tr>
                        <tr><th>Nationality</th><td>{{ $application->nationality }}</td></tr>
                        <tr><th>Country</th><td>{{ $application->country_of_residence }}</td></tr>
                        <tr><th>Academic Background</th><td>{{ $application->academic_background }}</td></tr>
                        <tr><th>Qualification</th><td>{{ $application->highest_qualification }}</td></tr>
                        <tr><th>Institution</th><td>{{ $application->institution }}</td></tr>
                        <tr><th>Year Completed</th><td>{{ $application->year_completed }}</td></tr>
                        <tr><th>Current Role</th><td>{{ $application->current_role }}</td></tr>
                        <tr><th>Organisation</th><td>{{ $application->organisation }}</td></tr>
                    </table>

                    <h6 class="mt-4">Selected Programmes</h6>
                    <ul>
                        @foreach($application->preferred_programmes as $prog)
                            <li>{{ $prog }}</li>
                        @endforeach
                    </ul>

                    <h6 class="mt-4">Personal Statement</h6>
                    <div class="bg-light p-3 rounded">{{ $application->personal_statement }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Update Status</h6></div>
                <div class="card-body">
                    <form action="{{ route('admin.scholarships.update-status', $application->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="under_review" {{ $application->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="3">{{ $application->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>

            <div class="card mt-24">
                <div class="card-header"><h6 class="card-title mb-0">Actions</h6></div>
                <div class="card-body">
                    <form action="{{ route('admin.scholarships.destroy', $application->id) }}" method="POST" onsubmit="return confirm('Delete this application?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">Delete Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
