{{-- resources/views/admin/certificates/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Certificate Details - #' . $enrollment->certificate_number)

@push('styles')
<style>
    .grade-distinction { color: #059669; }
    .grade-merit { color: #2563eb; }
    .grade-pass { color: #0891b2; }
    .grade-referral { color: #d97706; }
    .grade-fail { color: #dc2626; }
    .cert-verified-badge {
        position: relative;
        display: inline-block;
    }
    .cert-verified-badge::after {
        content: '✓';
        position: absolute;
        top: -5px;
        right: -5px;
        background: #10b981;
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="fas fa-certificate text-primary"></i> Certificate Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.certificates.index') }}">Certificates</a></li>
                    <li class="breadcrumb-item active">#{{ $enrollment->certificate_number ?? 'N/A' }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.certificates.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Back to Certificates
            </a>
            @if($enrollment->certificate_generated)
                <a href="{{ route('admin.certificates.edit', $enrollment) }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-edit fa-sm"></i> Edit
                </a>
            @else
                <a href="{{ route('admin.certificates.edit', $enrollment) }}" class="btn btn-sm btn-success shadow-sm">
                    <i class="fas fa-certificate fa-sm"></i> Generate Certificate
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        {{-- Main Content Column --}}
        <div class="col-lg-8">
            
            {{-- Certificate Status Banner --}}
            @if($enrollment->certificate_generated)
                <div class="alert alert-{{ $enrollment->certificate_status === 'active' ? 'success' : ($enrollment->certificate_status === 'revoked' ? 'danger' : 'warning') }} shadow-sm mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas {{ $enrollment->certificate_status_icon }} fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Certificate Status: {{ $enrollment->certificate_status_display }}</h5>
                            @if($enrollment->isCertificateRevoked() && $enrollment->certificate_revocation_reason)
                                <p class="mb-0 small"><strong>Revocation Reason:</strong> {{ $enrollment->certificate_revocation_reason }}</p>
                            @endif
                            <p class="mb-0 small">Last Updated: {{ $enrollment->certificate_status_updated_at?->format('F d, Y H:i') ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning shadow-sm mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-hourglass-half fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Certificate Not Yet Generated</h5>
                            <p class="mb-0 small">This student has completed the course but the certificate has not been generated.</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Certificate Information Card --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Certificate Information
                    </h6>
                    @if($enrollment->certificate_verified)
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle"></i> Verified
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th class="text-muted" width="160">Certificate Number</th>
                                    <td>
                                        @if($enrollment->certificate_number)
                                            <code class="fs-6">{{ $enrollment->certificate_number }}</code>
                                        @else
                                            <span class="text-muted">Not Generated</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Issue Date</th>
                                    <td>{{ $enrollment->certificate_generated_date?->format('F d, Y') ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Final Grade</th>
                                    <td>
                                        @if($enrollment->final_grade)
                                            <span class="{{ $enrollment->grade_color_class }} fw-bold fs-5">
                                                {{ $enrollment->final_grade }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Status</th>
                                    <td>
                                        <span class="{!! $enrollment->certificate_status_badge_class !!}">
                                            {{ $enrollment->certificate_status_display }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Verification</th>
                                    <td>
                                        @if($enrollment->certificate_verified)
                                            <span class="text-success"><i class="fas fa-check-circle"></i> Verified</span>
                                        @else
                                            <span class="text-muted">Not Verified</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th class="text-muted" width="160">Issuing Body</th>
                                    <td>IGRCFP</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Full Name</th>
                                    <td>Institute of Governance, Risk, Compliance & Financial Crime Prevention</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Verification URL</th>
                                    <td>
                                        @if($enrollment->certificate_number)
                                            <div class="d-flex align-items-center gap-2">
                                                <code class="small">{{ $enrollment->verification_url }}</code>
                                                <button class="btn btn-sm btn-outline-secondary" 
                                                        onclick="copyToClipboard('{{ $enrollment->verification_url }}')"
                                                        title="Copy URL" data-bs-toggle="tooltip">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <a href="{{ $enrollment->verification_url }}" target="_blank" 
                                                class="btn btn-sm btn-outline-primary"
                                                title="Open in new tab" data-bs-toggle="tooltip">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Download</th>
                                    <td>
                                        @if($enrollment->certificate_download_url)
                                            <a href="{{ $enrollment->certificate_download_url }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i> Download PDF
                                            </a>
                                        @else
                                            <span class="text-muted">Not Available</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Student Information Card --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-graduate"></i> Student Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center mb-3">
                            <div class="avatar-circle mx-auto" style="width: 80px; height: 80px; background: #4e73df; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; font-weight: bold;">
                                {{ strtoupper(substr($enrollment->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h5 class="mb-1">{{ $enrollment->user->name }}</h5>
                            <p class="text-muted mb-2">{{ $enrollment->user->email }}</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr><td class="text-muted small">Phone:</td><td class="small">{{ $enrollment->user->phone ?? 'N/A' }}</td></tr>
                                        <tr><td class="text-muted small">Country:</td><td class="small">{{ $enrollment->user->country ?? 'N/A' }}</td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr><td class="text-muted small">Enrolled:</td><td class="small">{{ $enrollment->enrollment_date?->format('M d, Y') ?? 'N/A' }}</td></tr>
                                        <tr><td class="text-muted small">Completed:</td><td class="small">{{ $enrollment->completed_at?->format('M d, Y') ?? 'N/A' }}</td></tr>
                                    </table>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.show', $enrollment->user_id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-user"></i> View Full Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Course Information Card --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book"></i> Course Information
                    </h6>
                </div>
                <div class="card-body">
                    <h5 class="mb-2">{{ $enrollment->course->title }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr><td class="text-muted small">Level:</td><td class="small">{{ $enrollment->course->level ?? 'N/A' }}</td></tr>
                                <tr><td class="text-muted small">Duration:</td><td class="small">{{ $enrollment->course->duration ?? 'N/A' }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr><td class="text-muted small">Modules:</td><td class="small">{{ $enrollment->course->total_modules ?? 'N/A' }}</td></tr>
                                <tr><td class="text-muted small">Progress:</td>
                                    <td>
                                        <div class="progress" style="height: 8px; width: 150px;">
                                            <div class="progress-bar bg-success" style="width: {{ $enrollment->progress }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $enrollment->progress }}%</small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <a href="{{ route('admin.courses.show', $enrollment->course_id) }}" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="fas fa-book"></i> View Course
                    </a>
                </div>
            </div>

            {{-- Assessment Grade Breakdown --}}
            @php
                $gradeBreakdown = $enrollment->getGradeBreakdownAttribute();
            @endphp
            
            @if(count($gradeBreakdown['submissions']) > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-tasks"></i> Assessment Grade Breakdown
                        </h6>
                        <span class="badge bg-info">
                            {{ $gradeBreakdown['graded_submissions'] }} / {{ $gradeBreakdown['total_submissions'] }} Graded
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Assessment</th>
                                        <th>Type / Level</th>
                                        <th>Score</th>
                                        <th>Percentage</th>
                                        <th>Status</th>
                                        <th>Graded Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gradeBreakdown['submissions'] as $submission)
                                        <tr>
                                            <td class="small fw-bold">{{ $submission['assessment_name'] }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $submission['assessment_type'] }}</span>
                                                <small class="text-muted d-block">{{ $submission['assessment_level'] }}</small>
                                            </td>
                                            <td>{{ $submission['score'] }}</td>
                                            <td>
                                                <span class="fw-bold">{{ $submission['percentage'] }}%</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $submission['passed'] ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $submission['passed'] ? 'Passed' : 'Failed' }}
                                                </span>
                                            </td>
                                            <td class="small text-muted">{{ $submission['graded_at'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-right">Overall Average:</td>
                                        <td>{{ $gradeBreakdown['overall_percentage'] }}%</td>
                                        <td colspan="2">
                                            <span class="badge bg-primary fs-6">
                                                {{ $gradeBreakdown['overall_grade'] }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        {{-- Grade Summary --}}
                        <div class="row mt-3">
                            <div class="col-md-3 text-center">
                                <div class="border rounded p-3">
                                    <h2 class="mb-0 {{ 
                                        $gradeBreakdown['overall_grade'] === 'Distinction' ? 'grade-distinction' : 
                                        ($gradeBreakdown['overall_grade'] === 'Merit' ? 'grade-merit' : 
                                        ($gradeBreakdown['overall_grade'] === 'Pass' ? 'grade-pass' : 'grade-fail')) 
                                    }}">
                                        {{ $gradeBreakdown['overall_percentage'] }}%
                                    </h2>
                                    <small class="text-muted">Average Score</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="border rounded p-3">
                                    <h2 class="mb-0 text-success">{{ $gradeBreakdown['passed_submissions'] }}</h2>
                                    <small class="text-muted">Passed</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="border rounded p-3">
                                    <h2 class="mb-0 text-danger">{{ $gradeBreakdown['graded_submissions'] - $gradeBreakdown['passed_submissions'] }}</h2>
                                    <small class="text-muted">Failed</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="border rounded p-3">
                                    <h2 class="mb-0 text-warning">{{ $gradeBreakdown['total_submissions'] - $gradeBreakdown['graded_submissions'] }}</h2>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-tasks"></i> Assessment Grade Breakdown
                        </h6>
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3 d-block"></i>
                        <p class="text-muted">No graded assessments found for this enrollment.</p>
                    </div>
                </div>
            @endif

            {{-- Activity Log / Verification History --}}
            @if(isset($verificationHistory) && $verificationHistory->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-history"></i> Certificate Activity History
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach($verificationHistory as $activity)
                                <div class="timeline-item mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span class="{!! $activity->event_badge_class !!} small">
                                                {{ $activity->event_display_name }}
                                            </span>
                                            <span class="small text-muted ms-2">{{ $activity->action }}</span>
                                        </div>
                                        <small class="text-muted">{{ $activity->formatted_time }}</small>
                                    </div>
                                    <p class="mb-1 small">{{ $activity->description }}</p>
                                    <small class="text-muted">
                                        By: {{ $activity->performer_name }} | IP: {{ $activity->ip_address }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar Column --}}
        <div class="col-lg-4">
            
            {{-- Status Update Card --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-toggle-on"></i> Update Certificate Status
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.certificates.update-status', $enrollment) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Certificate Status</label>
                            <select name="status" class="form-select form-select-sm" required id="statusSelect">
                                @foreach(\App\Models\Enrollment::getCertificateStatuses() as $value => $label)
                                    <option value="{{ $value }}" {{ ($enrollment->certificate_status ?? 'pending') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3" id="revocationReasonDiv" style="display: {{ ($enrollment->certificate_status ?? '') === 'revoked' ? 'block' : 'none' }};">
                            <label class="form-label small fw-bold">
                                Revocation Reason <span class="text-danger">*</span>
                            </label>
                            <textarea name="revocation_reason" class="form-control form-control-sm" rows="3" 
                                    placeholder="Please provide a detailed reason for revocation...">{{ $enrollment->certificate_revocation_reason }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Admin Notes</label>
                            <textarea name="admin_notes" class="form-control form-control-sm" rows="2" 
                                    placeholder="Optional notes..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- Quick Actions Card --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(!$enrollment->certificate_generated)
                            <a href="{{ route('admin.certificates.edit', $enrollment) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-certificate"></i> Generate Certificate
                            </a>
                        @else
                            <a href="{{ route('admin.certificates.edit', $enrollment) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit Certificate Details
                            </a>
                        @endif
                        
                        @if($enrollment->isCertificateRevoked())
                            <form action="{{ route('admin.certificates.update-status', $enrollment) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="active">
                                <input type="hidden" name="admin_notes" value="Certificate reactivated by admin">
                                <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-undo"></i> Reactivate Certificate
                                </button>
                            </form>
                        @endif
                        
                        @if($enrollment->certificate_generated && $enrollment->isCertificateActive())
                            <button class="btn btn-outline-info btn-sm" 
                                    onclick="copyToClipboard('{{ $enrollment->verification_url }}')">
                                <i class="fas fa-copy"></i> Copy Verification Link
                            </button>
                        @endif
                        
                        <a href="{{ route('admin.enrollments.show', $enrollment) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-user-graduate"></i> View Enrollment Details
                        </a>
                        
                        <a href="{{ route('admin.users.show', $enrollment->user_id) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-user"></i> View Student Profile
                        </a>
                        
                        <a href="{{ route('admin.courses.show', $enrollment->course_id) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-book"></i> View Course
                        </a>

                        <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-dark btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Certificates
                        </a>
                    </div>
                </div>
            </div>

            {{-- Certificate Notes --}}
            @if($enrollment->notes)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-sticky-note"></i> Notes
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small text-muted" style="white-space: pre-wrap;">{{ $enrollment->notes }}</div>
                    </div>
                </div>
            @endif

            {{-- Revocation Details (if revoked) --}}
            @if($enrollment->isCertificateRevoked())
                <div class="card border-left-danger shadow mb-4">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Revocation Details</div>
                        <p class="small mb-1"><strong>Reason:</strong> {{ $enrollment->certificate_revocation_reason }}</p>
                        <p class="small mb-1"><strong>Date:</strong> {{ $enrollment->certificate_status_updated_at?->format('F d, Y H:i') }}</p>
                        @if($enrollment->certificate_status_updated_by)
                            <p class="small mb-0">
                                <strong>By:</strong> 
                                {{ $enrollment->certificateStatusUpdatedBy->name ?? 'Admin #' . $enrollment->certificate_status_updated_by }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide revocation reason based on status selection
    document.getElementById('statusSelect').addEventListener('change', function() {
        const revocationDiv = document.getElementById('revocationReasonDiv');
        const reasonTextarea = revocationDiv.querySelector('textarea');
        
        if (this.value === 'revoked') {
            revocationDiv.style.display = 'block';
            reasonTextarea.required = true;
        } else {
            revocationDiv.style.display = 'none';
            reasonTextarea.required = false;
        }
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('statusSelect');
        if (statusSelect && statusSelect.value === 'revoked') {
            document.getElementById('revocationReasonDiv').style.display = 'block';
        }
    });

    // Copy to clipboard function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success toast or alert
            alert('Verification URL copied to clipboard!');
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
@endpush
@endsection