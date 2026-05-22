{{-- resources/views/admin/activity-logs/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Activity Audit Log')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-history"></i> Activity Audit Log
        </h1>
        <div>
            <a href="{{ route('admin.activity-logs.export', request()->query()) }}" 
               class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Export CSV
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
                                Today's Activities</div>
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
                                This Week</div>
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
                                Security Events Today</div>
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
                                Errors Today</div>
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
                <i class="fas fa-filter"></i> Filters
            </h6>
            <a href="{{ route('admin.activity-logs.index') }}" 
               class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-sync-alt"></i> Reset Filters
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" id="filterForm">
                <div class="row">
                    {{-- Period Quick Filter --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small fw-bold">Period</label>
                        <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Time</option>
                            <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ request('period') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="last_week" {{ request('period') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                            <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small fw-bold">Date From</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" 
                               value="{{ request('date_from') }}">
                    </div>

                    {{-- Date To --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small fw-bold">Date To</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" 
                               value="{{ request('date_to') }}">
                    </div>

                    {{-- Module Filter --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small fw-bold">Module</label>
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
                        <label class="form-label small fw-bold">Event</label>
                        <select name="event" class="form-select form-select-sm">
                            <option value="">All Events</option>
                            <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                            <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                            <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                            <option value="logged_in" {{ request('event') == 'logged_in' ? 'selected' : '' }}>Login</option>
                            <option value="login_failed" {{ request('event') == 'login_failed' ? 'selected' : '' }}>Failed Login</option>
                            <option value="logged_out" {{ request('event') == 'logged_out' ? 'selected' : '' }}>Logout</option>
                            <option value="status_changed" {{ request('event') == 'status_changed' ? 'selected' : '' }}>Status Changed</option>
                        </select>
                    </div>

                    {{-- User Type --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small fw-bold">User Type</label>
                        <select name="user_type" class="form-select form-select-sm">
                            <option value="">All Users</option>
                            <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('user_type') == 'user' ? 'selected' : '' }}>Students</option>
                            <option value="system" {{ request('user_type') == 'system' ? 'selected' : '' }}>System</option>
                        </select>
                    </div>

                    {{-- Severity --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label small fw-bold">Severity</label>
                        <select name="severity" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="info" {{ request('severity') == 'info' ? 'selected' : '' }}>Info</option>
                            <option value="warning" {{ request('severity') == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="error" {{ request('severity') == 'error' ? 'selected' : '' }}>Error</option>
                            <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>

                    {{-- Search --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label small fw-bold">Search</label>
                        <input type="text" name="search" class="form-control form-control-sm" 
                               placeholder="Search description, IP..." 
                               value="{{ request('search') }}">
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Activity Log Table --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Activity Records
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Date/Time</th>
                            <th>Performer</th>
                            <th>Module</th>
                            <th>Event</th>
                            <th>Description</th>
                            <th>Subject</th>
                            <th>IP Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>
                                    <span class="d-block small">{{ $log->formatted_time }}</span>
                                    <small class="text-muted">{{ $log->time_ago }}</small>
                                </td>
                                <td>
                                    <div>
                                        <span class="fw-bold">{{ $log->performer_name }}</span>
                                        @if($log->performer_role)
                                            <span class="badge bg-secondary ms-1">
                                                {{ $log->performer_role }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst($log->module ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="{!! $log->event_badge_class !!}">
                                        {{ $log->event_display_name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="small">{{ Str::limit($log->description, 80) }}</span>
                                </td>
                                <td>
                                    <span class="small">{{ $log->subject_name ?? '-' }}</span>
                                </td>
                                <td>
                                    <code class="small">{{ $log->ip_address }}</code>
                                </td>
                                <td>
                                    <a href="{{ route('admin.activity-logs.show', $log) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        <span>No activity logs found matching your criteria.</span>
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
                    Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} 
                    of {{ $logs->total() }} entries
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection