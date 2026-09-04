@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
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
        <h6 class="fw-semibold mb-0">Cohort Application #{{ $application->id }}</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.cohort-applications.index') }}" class="btn btn-outline-primary">Back to List</a>
        </div>
    </div>

    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Applicant Details</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <tr>
                            <th width="220">Full Name</th>
                            <td>{{ $application->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $application->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $application->phone ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <th>Country</th>
                            <td>{{ $application->country }}</td>
                        </tr>
                        <tr>
                            <th>Cohort</th>
                            <td>{{ $application->cohort }}</td>
                        </tr>
                        <tr>
                            <th>Level</th>
                            <td>{{ $application->level }}</td>
                        </tr>
                        <tr>
                            <th>Discipline</th>
                            <td>{{ $application->discipline ?: 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th>Submission Date</th>
                            <td>{{ $application->created_at->format('F j, Y g:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Current Status</th>
                            <td>
                                <span class="badge bg-{{
                                    $application->status == 'new' ? 'secondary' :
                                    ($application->status == 'reviewing' ? 'info' :
                                    ($application->status == 'admitted' ? 'success' :
                                    ($application->status == 'rejected' ? 'danger' : 'warning')))
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    @if($application->message)
                        <div class="mt-4">
                            <h6 class="mb-2">Applicant Message</h6>
                            <div class="bg-light border rounded p-3">{{ $application->message }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Update Application Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cohort-applications.update-status', $application->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" id="cohortStatusSelect">
                                <option value="new" {{ $application->status == 'new' ? 'selected' : '' }}>New</option>
                                <option value="reviewing" {{ $application->status == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                                <option value="admitted" {{ $application->status == 'admitted' ? 'selected' : '' }}>Admitted</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="withdrawn" {{ $application->status == 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                            </select>
                        </div>

                        <div class="mb-3" id="cohortRejectionReasonDiv" style="display: {{ $application->status == 'rejected' ? 'block' : 'none' }};">
                            <label class="form-label">Rejection Reason</label>
                            <textarea name="rejection_reason" class="form-control" rows="4" placeholder="Add rejection reason to send to applicant...">{{ old('rejection_reason', $application->rejection_reason) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="4" placeholder="Optional internal note...">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save Status & Notify Applicant</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.getElementById('cohortStatusSelect');
        const rejectionDiv = document.getElementById('cohortRejectionReasonDiv');
        const rejectionTextarea = document.querySelector('textarea[name="rejection_reason"]');

        function toggleRejection() {
            if (statusSelect && statusSelect.value === 'rejected') {
                rejectionDiv.style.display = 'block';
                if (rejectionTextarea) {
                    rejectionTextarea.required = true;
                }
            } else {
                rejectionDiv.style.display = 'none';
                if (rejectionTextarea) {
                    rejectionTextarea.required = false;
                }
            }
        }

        if (statusSelect) {
            statusSelect.addEventListener('change', toggleRejection);
            toggleRejection();
        }
    });
</script>
@endsection
