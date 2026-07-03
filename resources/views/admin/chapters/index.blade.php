@extends('admin.layouts.app')

@section('title', 'All Chapters')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Chapters Management</h5>
                    @if(auth()->guard('admin')->user()->isAdmin())
                        <a href="{{ route('admin.chapters.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i> Add New Chapter
                        </a>
                    @endif
                </div>

                <div class="card-body">
                    {{-- Success Alert --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Region</th>
                                    <th>Countries Covered</th>
                                    <th>Annual Fee</th>
                                    {{-- New Column --}}
                                    <th>Events</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($chapters as $chapter)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $chapter->region }}</strong></td>
                                        <td>{{ $chapter->country_focus ?? '—' }}</td>
                                        <td>£{{ number_format($chapter->annual_fee, 2) }}</td>
                                        {{-- New Events Count & Indicator --}}
                                        <td>
                                            @php
                                                $eventCount = $chapter->events->count();
                                            @endphp
                                            @if($eventCount > 0)
                                                <a href="{{ route('admin.chapters.events', $chapter) }}" 
                                                   class="badge bg-primary text-decoration-none">
                                                    {{ $eventCount }} {{ Str::plural('event', $eventCount) }}
                                                </a>
                                            @else
                                                <span class="text-muted fst-italic">No events</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($chapter->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.chapters.edit', $chapter) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit Chapter">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <a href="{{ route('admin.chapters.leadership', $chapter) }}" class="btn btn-sm btn-outline-info me-1" title="Manage Leadership">
                                                <i class="ri-user-settings-line"></i>
                                            </a>
                                            <a href="{{ route('admin.chapters.events', $chapter) }}" class="btn btn-sm btn-outline-warning me-1" title="Chapter Events">
                                                <i class="ri-calendar-event-line"></i>
                                            </a>
                                            <a href="{{ route('chapters.show', $chapter->slug) }}" target="_blank" class="btn btn-sm btn-outline-success me-1" title="View Public Page">
                                                <i class="ri-external-link-line"></i>
                                            </a>
                                            @if(auth()->guard('admin')->user()->isAdmin())
                                                <form action="{{ route('admin.chapters.destroy', $chapter) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this chapter? This action cannot be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Chapter">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="ri-map-pin-line fs-2 d-block mb-2"></i>
                                            No chapters found. Click "Add New Chapter" to create one.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $chapters->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection