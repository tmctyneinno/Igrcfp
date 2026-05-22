{{-- resources/views/admin/activity-logs/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Activity Audit Log')

@push('styles')
<style>
    .log-row {
        transition: all 0.2s ease;
    }
    .log-row:hover {
        background-color: #f8f9fc !important;
    }
    .subject-link {
        color: #4e73df;
        text-decoration: none;
        transition: color 0.2s;
    }
    .subject-link:hover {
        color: #2e59d9;
        text-decoration: underline;
    }
    .performer-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        margin-right: 8px;
    }
    .avatar-admin {
        background: #4e73df;
        color: white;
    }
    .avatar-user {
        background: #1cc88a;
        color: white;
    }
    .avatar-system {
        background: #858796;
        color: white;
    }
    .description-cell {
        max-width: 300px;
    }
    .action-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="fas fa-history text-primary"></i> Activity Audit Log
            </h1>
            <p class="mb-0 text-muted small">Comprehensive log of all system activities and security events</p>
        </div>
        <div>
            <a href="{{ route('admin.activity-logs.export', request()->query()) }}" 
               class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-file-export fa-sm text-white-50"></i> Export Logs
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Today's Activities
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($statistics['total_today']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                This Week
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($statistics['total_this_week']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Security Events Today
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($statistics['security_events']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Errors & Warnings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($statistics['errors_today']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters Card --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filter Activities
            </h6>
            <a href="{{ route('admin.activity-logs.index') }}" 
               class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-redo-alt"></i> Reset
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" id="filterForm">
                <div class="row">
                    {{-- Period Quick Filter --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small font-weight-bold text-uppercase text-muted">Period</label>
                        <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Time</option>
                            <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>📅 Today</option>
                            <option value="yesterday" {{ request('period') == 'yesterday' ? 'selected' : '' }}>📅 Yesterday</option>
                            <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>📅 This Week</option>
                            <option value="last_week" {{ request('period') == 'last_week' ? 'selected' : '' }}>📅 Last Week</option>
                            <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>📅 This Month</option>
                            <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>📅 Last Month</option>
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small font-weight-bold text-uppercase text-muted">From</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" 
                               value="{{ request('date_from') }}">
                    </div>

                    {{-- Date To --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small font-weight-bold text-uppercase text-muted">To</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" 
                               value="{{ request('date_to') }}">
                    </div>

                    {{-- Module Filter --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small font-weight-bold text-uppercase text-muted">Module</label>
                        <select name="module" class="form-select form-select-sm">
                            <option value="">All Modules</option>
                            @foreach($modules as $module)
                                <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                    {{ ucfirst($module) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Event Filter --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small font-weight-bold text-uppercase text-muted">Event</label>
                        <select name="event" class="form-select form-select-sm">
                            <option value="">All Events</option>
                            <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>➕ Created</option>
                            <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>✏️ Updated</option>
                            <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>🗑️ Deleted</option>
                            <option value="logged_in" {{ request('event') == 'logged_in' ? 'selected' : '' }}>🔑 Login</option>
                            <option value="login_failed" {{ request('event') == 'login_failed' ? 'selected' : '' }}>⚠️ Failed Login</option>
                            <option value="logged_out" {{ request('event') == 'logged_out' ? 'selected' : '' }}>🚪 Logout</option>
                            <option value="status_changed" {{ request('event') == 'status_changed' ? 'selected' : '' }}>🔄 Status Changed</option>
                        </select>
                    </div>

                    {{-- User Type --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small font-weight-bold text-uppercase text-muted">User Type</label>
                        <select name="user_type" class="form-select form-select-sm">
                            <option value="">All Users</option>
                            <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>👤 Admins</option>
                            <option value="user" {{ request('user_type') == 'user' ? 'selected' : '' }}>👨‍🎓 Students</option>
                            <option value="system" {{ request('user_type') == 'system' ? 'selected' : '' }}>🤖 System</option>
                        </select>
                    </div>

                    {{-- Severity --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small font-weight-bold text-uppercase text-muted">Severity</label>
                        <select name="severity" class="form-select form-select-sm">
                            <option value="">All Levels</option>
                            <option value="info" {{ request('severity') == 'info' ? 'selected' : '' }}>ℹ️ Info</option>
                            <option value="warning" {{ request('severity') == 'warning' ? 'selected' : '' }}>⚠️ Warning</option>
                            <option value="error" {{ request('severity') == 'error' ? 'selected' : '' }}>❌ Error</option>
                            <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>🚨 Critical</option>
                        </select>
                    </div>

                    {{-- Search --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label small font-weight-bold text-uppercase text-muted">Search</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search description, IP, email..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Activity Log Table --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list-alt"></i> Activity Records
                <span class="badge bg-secondary ms-2">{{ $logs->total() }} total</span>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-nowrap" style="width: 60px;"># ID</th>
                            <th class="text-nowrap" style="width: 130px;">Date & Time</th>
                            <th class="text-nowrap" style="width: 180px;">Performer</th>
                            <th class="text-nowrap" style="width: 90px;">Module</th>
                            <th class="text-nowrap" style="width: 100px;">Event</th>
                            <th style="min-width: 200px;">Action</th>
                            <th style="min-width: 150px;">Subject</th>
                            <th class="text-nowrap" style="width: 110px;">IP Address</th>
                            <th class="text-nowrap" style="width: 70px;">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="log-row">
                                {{-- ID --}}
                                <td class="align-middle">
                                    <span class="badge bg-light text-dark font-monospace">
                                        #
                                        {{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}
                                    </span>
                                </td>
                                
                                {{-- Date/Time --}}
                                <td class="align-middle small">
                                    <div class="text-nowrap">
                                        <i class="far fa-clock text-muted me-1"></i>
                                        {{ $log->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-muted text-nowrap">
                                        {{ $log->created_at->format('H:i:s') }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.7rem;">
                                        {{ $log->time_ago }}
                                    </div>
                                </td>
                                
                                {{-- Performer --}}
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        
                                        <div>
                                            <div class="font-weight-bold small">{{ $log->performer_name }}</div>
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="{{ $log->performer_type_badge }}" style="font-size: 0.65rem;">
                                                    {{ $log->performer_type_label }}
                                                </span>
                                                @if($log->performer_role)
                                                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">
                                                        {{ $log->performer_role }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($log->performer_email)
                                                <div class="text-muted" style="font-size: 0.7rem;">
                                                    {{ $log->performer_email }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- Module --}}
                                <td class="align-middle">
                                    <span class="badge bg-info" style="font-size: 0.75rem;">
                                        {{ ucfirst($log->module ?? 'System') }}
                                    </span>
                                </td>
                                
                                {{-- Event --}}
                                <td class="align-middle">
                                    <span class="{!! $log->event_badge_class !!}" style="font-size: 0.75rem;">
                                        <i class="fas {{ $log->event_icon }} me-1"></i>
                                        {{ $log->event_display_name }}
                                    </span>
                                </td>
                                
                                {{-- Action / Description --}}
                                <td class="align-middle description-cell">
                                    <div class="small font-weight-bold text-dark mb-1">
                                        {{ $log->action_display }}
                                    </div>
                                    @if($log->description)
                                        <div class="text-muted small">
                                            {{ $log->description }}
                                        </div>
                                    @endif
                                    @if($log->severity !== 'info')
                                        <span class="{!! $log->severity_badge_class !!} mt-1" style="font-size: 0.65rem;">
                                            {{ ucfirst($log->severity) }}
                                        </span>
                                    @endif
                                </td>
                                
                                {{-- Subject --}}
                                <td class="align-middle">
                                    @if($log->subject_name)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-cube text-muted me-2 small"></i>
                                            <div>
                                                @if($log->subject_link)
                                                    <a href="{{ $log->subject_link }}" 
                                                       class="subject-link small font-weight-bold" 
                                                       target="_blank">
                                                        {{ $log->subject_name }}
                                                    </a>
                                                @else
                                                    <span class="small font-weight-bold">{{ $log->subject_name }}</span>
                                                @endif
                                                <div class="text-muted" style="font-size: 0.65rem;">
                                                    {{ $log->subject_type_display }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                
                                {{-- IP Address --}}
                                <td class="align-middle">
                                    <code class="small" title="{{ $log->user_agent }}">
                                        {{ $log->ip_address ?? 'N/A' }}
                                    </code>
                                </td>
                                
                                {{-- Actions --}}
                                <td class="align-middle text-center">
                                    <a href="{{ route('admin.activity-logs.show', $log) }}" 
                                       class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                        
                                       data-bs-toggle="tooltip"
                                       title="View full details">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-clipboard-list fa-4x mb-3 d-block"></i>
                                        <h5>No Activity Logs Found</h5>
                                        <p class="small">Try adjusting your filters or date range</p>
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
                    Showing <strong>{{ $logs->firstItem() ?? 0 }}</strong> 
                    to <strong>{{ $logs->lastItem() ?? 0 }}</strong> 
                    of <strong>{{ $logs->total() }}</strong> entries
                </div>
                <div>
                    {{ $logs->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush