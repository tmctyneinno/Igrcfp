{{-- resources/views/admin/certificates/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Generate Certificate')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-certificate"></i> Generate Certificate
        </h1>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Student & Course Info --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Student & Course Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Student Name</th>
                            <td>{{ $enrollment->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $enrollment->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Course</th>
                            <td>{{ $enrollment->course->title }}</td>
                        </tr>
                        <tr>
                            <th>Enrollment Date</th>
                            <td>{{ $enrollment->enrollment_date?->format('F d, Y') ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Progress</th>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: {{ $enrollment->progress }}%">
                                        {{ $enrollment->progress }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Assessment Results --}}
            @php
                $gradeBreakdown = $enrollment->getGradeBreakdownAttribute();
            @endphp
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tasks"></i> Assessment Results
                    </h6>
                    <span class="badge bg-info">
                        {{ $gradeBreakdown['graded_submissions'] }} of {{ $gradeBreakdown['total_submissions'] }} Graded
                    </span>
                </div>
                <div class="card-body">
                    @if(count($gradeBreakdown['submissions']) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Assessment</th>
                                        <th>Type</th>
                                        <th>Score</th>
                                        <th>Percentage</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gradeBreakdown['submissions'] as $submission)
                                        <tr>
                                            <td class="small">{{ $submission['assessment_name'] }}</td>
                                            <td><span class="badge bg-secondary">{{ $submission['assessment_type'] }}</span></td>
                                            <td>{{ $submission['score'] }}</td>
                                            <td>
                                                <span class="font-weight-bold">{{ $submission['percentage'] }}%</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $submission['passed'] ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $submission['passed'] ? 'Passed' : 'Failed' }}
                                                </span>
                                            </td>
                                            <td class="small">{{ $submission['graded_at'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr class="font-weight-bold">
                                        <td colspan="3" class="text-right">Overall Average:</td>
                                        <td>{{ $gradeBreakdown['overall_percentage'] }}%</td>
                                        <td colspan="2">
                                            <span class="badge bg-primary">
                                                Calculated Grade: {{ $gradeBreakdown['overall_grade'] }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 d-block"></i>
                            <p>No graded assessments found for this enrollment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Generate Certificate Form --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-certificate"></i> Generate Certificate
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.certificates.generate', $enrollment) }}" method="POST">
                        @csrf
                        
                        {{-- Use calculated grade toggle --}}
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="use_calculated_grade" 
                                       id="useCalculatedGrade" value="1" checked>
                                <label class="form-check-label" for="useCalculatedGrade">
                                    Use assessment-based grade
                                </label>
                            </div>
                            <div class="text-muted small mt-1">
                                Calculated Grade: <strong>{{ $gradeBreakdown['overall_grade'] }}</strong> 
                                ({{ $gradeBreakdown['overall_percentage'] }}%)
                            </div>
                        </div>

                        {{-- Manual grade selection --}}
                        <div class="mb-3" id="manualGradeDiv" style="display: none;">
                            <label class="form-label">Final Grade</label>
                            <select name="final_grade" class="form-select" id="finalGrade">
                                <option value="">-- Select Grade --</option>
                                <option value="Distinction" {{ $gradeBreakdown['overall_grade'] === 'Distinction' ? 'selected' : '' }}>
                                    Distinction (90%+)
                                </option>
                                <option value="Merit" {{ $gradeBreakdown['overall_grade'] === 'Merit' ? 'selected' : '' }}>
                                    Merit (75-89%)
                                </option>
                                <option value="Pass" {{ $gradeBreakdown['overall_grade'] === 'Pass' ? 'selected' : '' }}>
                                    Pass (60-74%)
                                </option>
                                <option value="Referral" {{ $gradeBreakdown['overall_grade'] === 'Referral' ? 'selected' : '' }}>
                                    Referral (40-59%)
                                </option>
                                <option value="Fail" {{ $gradeBreakdown['overall_grade'] === 'Fail' ? 'selected' : '' }}>
                                    Fail (Below 40%)
                                </option>
                            </select>
                        </div>

                        {{-- Admin notes --}}
                        <div class="mb-3">
                            <label class="form-label">Admin Notes (Optional)</label>
                            <textarea name="admin_notes" class="form-control" rows="3" 
                                      placeholder="Any notes about this certificate..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            <i class="fas fa-certificate"></i> Generate Certificate
                        </button>
                    </form>
                </div>
            </div>

            {{-- Certificate Preview Info --}}
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle"></i> Certificate Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <p><strong>Certificate Number:</strong><br>
                            <code>{{ $enrollment->certificate_number ?? 'Will be auto-generated' }}</code>
                        </p>
                        <p><strong>Issue Date:</strong><br>
                            {{ now()->format('F d, Y') }}
                        </p>
                        <p><strong>Issuing Body:</strong><br>
                            IGRCFP - Institute of Governance, Risk, Compliance & Financial Crime Prevention
                        </p>
                        <p><strong>Verification:</strong><br>
                            <small class="text-muted">A unique verification URL will be generated for this certificate</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle between calculated and manual grade
    document.getElementById('useCalculatedGrade').addEventListener('change', function() {
        document.getElementById('manualGradeDiv').style.display = this.checked ? 'none' : 'block';
        document.getElementById('finalGrade').required = !this.checked;
    });
</script>
@endpush
@endsection