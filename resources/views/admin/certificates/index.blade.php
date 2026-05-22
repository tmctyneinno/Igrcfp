{{-- resources/views/admin/certificates/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Certificate Management')

@push('styles')
<style>
    .grade-distinction { color: #059669; font-weight: bold; }
    .grade-merit { color: #2563eb; font-weight: bold; }
    .grade-pass { color: #0891b2; font-weight: bold; }
    .grade-referral { color: #d97706; font-weight: bold; }
    .grade-fail { color: #dc2626; font-weight: bold; }
    .cert-number {
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
        background: #f8f9fc;
        padding: 2px 6px;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="fas fa-certificate text-primary"></i> Certificate Management
            </h1>
            <p class="mb-0 text-muted small">Generate, approve, and manage student certificates</p>
        </div>
        <div>
            <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#bulkGenerateModal">
                <i class="fas fa-layer-group fa-sm"></i> Bulk Generate
            </button>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Certificates</div>
                    <div class="h5 mb-0 font-weight-bold">{{ number_format($statistics['total_certificates']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Generated Today</div>
                    <div class="h5 mb-0 font-weight-bold">{{ number_format($statistics['today_generated']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Generation</div>
                    <div class="h5 mb-0 font-weight-bold">{{ number_format($statistics['pending_certificates']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active</div>
                    <div class="h5 mb-0 font-weight-bold">{{ number_format($statistics['active_certificates']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Revoked</div>
                    <div class="h5 mb-0 font-weight-bold">{{ number_format($statistics['revoked_certificates']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Expired</div>
                    <div class="h5 mb-0 font-weight-bold">{{ number_format($statistics['expired_certificates']) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filters
            </h6>
            <a href="{{ route('admin.certificates.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-redo-alt"></i> Reset
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.certificates.index') }}">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control form-control-sm" 
                               placeholder="Search name, email, cert #..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="course_id" class="form-select form-select-sm">
                            <option value="">All Courses</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ Str::limit($course->title, 30) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Enrollment Status</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="certificate_status" class="form-select form-select-sm">
                            <option value="">Certificate Status</option>
                            <option value="active" {{ request('certificate_status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="revoked" {{ request('certificate_status') == 'revoked' ? 'selected' : '' }}>Revoked</option>
                            <option value="expired" {{ request('certificate_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="suspended" {{ request('certificate_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="pending" {{ request('certificate_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <input type="date" name="date_from" class="form-control form-control-sm" 
                               placeholder="From" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-1">
                        <input type="date" name="date_to" class="form-control form-control-sm" 
                               placeholder="To" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Certificates Table --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Certificate Records
                <span class="badge bg-secondary ms-2">{{ $certificates->total() }}</span>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Certificate #</th>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Grade</th>
                            <th>Generated Date</th>
                            <th>Cert Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($certificates as $enrollment)
                            <tr>
                                <td>
                                    @if(!$enrollment->certificate_generated && $enrollment->status === 'completed')
                                        <input type="checkbox" name="enrollment_ids[]" 
                                               value="{{ $enrollment->id }}" class="enrollment-checkbox form-check-input">
                                    @endif
                                </td>
                                <td>
                                    @if($enrollment->certificate_number)
                                        <span class="cert-number">{{ $enrollment->certificate_number }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Not Generated</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2" style="width: 32px; height: 32px; background: #4e73df; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold; flex-shrink: 0;">
                                            {{ strtoupper(substr($enrollment->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="small fw-bold">{{ $enrollment->user->name }}</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">{{ $enrollment->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="small" title="{{ $enrollment->course->title }}">
                                        {{ Str::limit($enrollment->course->title, 30) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $displayGrade = $enrollment->display_grade;
                                        $gradeBadgeClass = $enrollment->grade_badge_class;
                                    @endphp
                                    
                                    @if($displayGrade && $displayGrade !== 'N/A')
                                        <span class="badge {{ $gradeBadgeClass }}">
                                            {{ $displayGrade }}
                                        </span>
                                        
                                        {{-- Show percentage if available --}}
                                        @php
                                            $avgPercentage = $enrollment->assessmentSubmissions()
                                                ->where('status', 'graded')
                                                ->avg('percentage');
                                        @endphp
                                        @if($avgPercentage)
                                            <small class="d-block text-muted" style="font-size: 0.65rem;">
                                                {{ number_format($avgPercentage, 1) }}%
                                            </small>
                                        @endif
                                    @else
                                        @if($enrollment->certificate_generated)
                                            <span class="badge bg-secondary">Pass</span>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($enrollment->certificate_generated_date)
                                        <span class="small">{{ $enrollment->certificate_generated_date->format('M d, Y') }}</span>
                                        <div class="text-muted" style="font-size: 0.65rem;">
                                            {{ $enrollment->certificate_generated_date->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($enrollment->certificate_generated)
                                        <span class="badge {{ $enrollment->certificate_status_badge_class }}">
                                            {{ $enrollment->certificate_status_display }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.certificates.show', $enrollment) }}" 
                                            class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                            title="View Certificate"
                                           data-bs-toggle="tooltip">
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>
                                        @if(!$enrollment->certificate_generated)
                                            <a href="{{ route('admin.certificates.edit', $enrollment) }}" 
                                               class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                           
                                               title="Generate Certificate" data-bs-toggle="tooltip">
                                                <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                            </a>
                                        @else
                                            <a href="{{ route('admin.certificates.edit', $enrollment) }}" 
                                                class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                           
                                                title="Edit Certificate" data-bs-toggle="tooltip">
                                                <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-certificate fa-3x mb-3 d-block"></i>
                                        <h6>No Certificate Records Found</h6>
                                        <p class="small">Try adjusting your filters or generate certificates for completed enrollments.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Showing <strong>{{ $certificates->firstItem() ?? 0 }}</strong> 
                    to <strong>{{ $certificates->lastItem() ?? 0 }}</strong> 
                    of <strong>{{ $certificates->total() }}</strong> entries
                </div>
                <div>
                    {{ $certificates->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bulk Generate Modal --}}
<div class="modal fade" id="bulkGenerateModal" tabindex="-1" aria-labelledby="bulkGenerateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.certificates.bulk-generate') }}" method="POST" id="bulkGenerateForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkGenerateModalLabel">
                        <i class="fas fa-layer-group text-success"></i> Bulk Generate Certificates
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small mb-3">
                        <i class="fas fa-info-circle"></i> 
                        Select enrollments from the table by checking the boxes, then click generate to create certificates for all selected students.
                    </div>
                    
                    <input type="hidden" name="enrollment_ids" id="bulkEnrollmentIds">
                    
                    <div id="selectedCount" class="alert alert-success d-none">
                        <i class="fas fa-check-circle"></i> 
                        <strong id="countDisplay">0</strong> enrollment(s) selected for certificate generation.
                    </div>
                    
                    <div id="noSelection" class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        No enrollments selected. Please check the boxes next to students in the table.
                    </div>

                    <div class="small text-muted mt-2">
                        <p class="mb-1"><strong>Note:</strong></p>
                        <ul class="mb-0">
                            <li>Certificates will be generated with calculated grades from assessments</li>
                            <li>Certificate numbers will be auto-generated</li>
                            <li>Students will be notified via email and in-app notification</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="bulkGenerateBtn" disabled>
                        <i class="fas fa-certificate"></i> Generate Certificates
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Select All checkbox
        const selectAllCheckbox = document.getElementById('selectAll');
        const enrollmentCheckboxes = document.querySelectorAll('.enrollment-checkbox');
        const bulkEnrollmentIdsInput = document.getElementById('bulkEnrollmentIds');
        const countDisplay = document.getElementById('countDisplay');
        const selectedCountDiv = document.getElementById('selectedCount');
        const noSelectionDiv = document.getElementById('noSelection');
        const bulkGenerateBtn = document.getElementById('bulkGenerateBtn');

        // Select All functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                enrollmentCheckboxes.forEach(cb => cb.checked = this.checked);
                updateBulkCount();
            });
        }

        // Individual checkbox change
        enrollmentCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkCount);
        });

        // Update bulk count display
        function updateBulkCount() {
            const checked = document.querySelectorAll('.enrollment-checkbox:checked');
            const ids = Array.from(checked).map(cb => cb.value);
            
            bulkEnrollmentIdsInput.value = JSON.stringify(ids);
            countDisplay.textContent = ids.length;
            
            if (ids.length > 0) {
                selectedCountDiv.classList.remove('d-none');
                noSelectionDiv.classList.add('d-none');
                bulkGenerateBtn.disabled = false;
            } else {
                selectedCountDiv.classList.add('d-none');
                noSelectionDiv.classList.remove('d-none');
                bulkGenerateBtn.disabled = true;
            }
        }

        // Initialize on page load
        updateBulkCount();
    });
</script>
@endpush