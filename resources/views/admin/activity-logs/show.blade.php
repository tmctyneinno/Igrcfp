{{-- resources/views/admin/activity-logs/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Activity Log Detail #' . $activityLog->id)

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-search-plus"></i> Activity Log Detail
        </h1>
        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Back to Logs
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Main Details Card --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Activity Details
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Log ID</th>
                            <td>#{{ $activityLog->id }}</td>
                        </tr>
                        <tr>
                            <th>Date & Time</th>
                            <td>
                                {{ $activityLog->formatted_time }}
                                <small class="text-muted d-block">{{ $activityLog->time_ago }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Event</th>
                            <td>
                                <span class="{!! $activityLog->event_badge_class !!}">
                                    {{ $activityLog->event_display_name }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Module</th>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($activityLog->module ?? 'N/A') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Severity</th>
                            <td>
                                <span class="{!! $activityLog->severity_badge_class !!}">
                                    {{ ucfirst($activityLog->severity) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Action</th>
                            <td>{{ $activityLog->action ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $activityLog->description }}</td>
                        </tr>
                        <tr>
                            <th>IP Address</th>
                            <td><code>{{ $activityLog->ip_address }}</code></td>
                        </tr>
                        <tr>
                            <th>User Agent</th>
                            <td>
                                <span class="small text-muted">{{ $activityLog->user_agent }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Properties Card (if exists) --}}
            @if($activityLog->properties)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-code"></i> Additional Properties
                        </h6>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            {{-- Performer Card --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user"></i> Performer
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-circle mx-auto mb-2" 
                             style="width: 80px; height: 80px; background: #4e73df; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; font-weight: bold;">
                            {{ strtoupper(substr($activityLog->performer_name, 0, 1)) }}
                        </div>
                        <h5 class="mb-0">{{ $activityLog->performer_name }}</h5>
                        @if($activityLog->performer_role)
                            <span class="badge bg-primary mt-1">{{ $activityLog->performer_role }}</span>
                        @endif
                    </div>
                    <hr>
                    <p class="small mb-1"><strong>Type:</strong> 
                        {{ $activityLog->loggable_type === 'App\\Models\\Admin' ? 'Administrator' : 'Student' }}
                    </p>
                </div>
            </div>

            {{-- Subject Card --}}
            @if($activityLog->subject)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-cube"></i> Subject
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="small mb-1"><strong>Type:</strong> 
                            {{ class_basename($activityLog->subject_type) }}
                        </p>
                        <p class="small mb-0"><strong>Name:</strong> 
                            {{ $activityLog->subject_name }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection