@extends('admin.layouts.app')

@section('title', "Chapter Events - {$chapter->region}")

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Events for: {{ $chapter->region }}</h5>
                    <div>
                        <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm me-2">
                            <i class="ri-add-line me-1"></i> Add New Event
                        </a>
                        <a href="{{ route('admin.chapters.index') }}" class="btn btn-light btn-sm">
                            <i class="ri-arrow-left-line me-1"></i> Back to Chapters
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>
                                         <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $event->title }}</strong></td>
                                        <td>{{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('d M Y') : '—' }}</td>
                                        <td>{{ $event->start_time ?? '—' }}</td>
                                        <td>
                                            @if(isset($event->status) && $event->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-outline-primary me-1" title="View Event">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-outline-info me-1" title="Edit Event">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="ri-calendar-event-line fs-2 d-block mb-2"></i>
                                            No events linked to this chapter yet.
                                            <br>
                                            <small>You can add an event and assign it to this chapter from the main Events page.</small>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection