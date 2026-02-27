{{-- resources/views/admin/courses/modules/lessons/index.blade.php --}}

@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Lessons for: {{ $module->title }}</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.courses.index') }}" class="hover-text-primary">Courses</a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.courses.show', $course->id) }}" class="hover-text-primary">{{ Str::limit($course->title, 20) }}</a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.courses.modules.edit', [$course->id, $module->id]) }}" class="hover-text-primary">
                    {{ Str::limit($module->title, 15) }}
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Lessons</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h6 class="card-title mb-0">All Lessons</h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.courses.modules.lessons.create', [$course->slug, $module->id]) }}" 
                       class="btn btn-sm btn-primary">
                        Add New Lesson
                    </a>
                    <a href="{{ route('admin.courses.modules.edit', [$course->slug, $module->id]) }}" 
                       class="btn btn-sm btn-outline-secondary">
                        Back to Module
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($lessons->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Lesson Title</th>
                                <th>Duration</th>
                                <th>Preview</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="lessonSortable">
                            @foreach($lessons as $lesson)
                                <tr data-id="{{ $lesson->id }}">
                                    <td>
                                        <span class="sort-handle" style="cursor: move;">
                                            <iconify-icon icon="mdi:drag"></iconify-icon>
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $lesson->title }}</strong>
                                            @if($lesson->short_description)
                                                <p class="text-muted small mb-0">{{ Str::limit($lesson->short_description, 50) }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($lesson->duration)
                                            {{ floor($lesson->duration / 60) }}h {{ $lesson->duration % 60 }}m
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($lesson->is_free)
                                            <span class="badge bg-success">Free Preview</span>
                                        @else
                                            <span class="badge bg-secondary">Enrolled Only</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($lesson->is_published)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $lesson->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.courses.modules.lessons.edit', [$course->slug, $module->id, $lesson->id]) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <iconify-icon icon="mdi:pencil"></iconify-icon>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-info" title="Preview" target="_blank">
                                                <iconify-icon icon="mdi:eye"></iconify-icon>
                                            </a>
                                            <form action="{{ route('admin.courses.modules.lessons.destroy', [$course->slug, $module->id, $lesson->id]) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Delete this lesson? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <iconify-icon icon="mdi:trash"></iconify-icon>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <iconify-icon icon="mdi:play-box-outline" class="icon-3x text-muted mb-3"></iconify-icon>
                    <h5 class="text-muted mb-2">No Lessons Yet</h5>
                    <p class="text-muted mb-4">Start adding lessons to this module</p>
                    <a href="{{ route('admin.courses.modules.lessons.create', [$course->id, $module->id]) }}" 
                       class="btn btn-primary">
                        <iconify-icon icon="mdi:plus"></iconify-icon>
                        Add First Lesson
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.sort-handle {
    cursor: move;
    user-select: none;
}
.sort-handle:hover {
    color: #0d6efd;
}
.table tr {
    cursor: default;
}
.table tr.dragging {
    opacity: 0.5;
    background: #f8f9fa;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lessonList = document.getElementById('lessonSortable');
    if (lessonList) {
        new Sortable(lessonList, {
            handle: '.sort-handle',
            animation: 150,
            onEnd: function() {
                const lessons = [];
                document.querySelectorAll('#lessonSortable tr').forEach((row, index) => {
                    lessons.push({
                        id: row.dataset.id,
                        sort_order: index + 1
                    });
                });

                fetch('{{ route("admin.courses.modules.lessons.reorder", [$course->id, $module->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ lessons })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message (optional)
                        console.log('Lesson order updated');
                    }
                })
                .catch(error => console.error('Error updating lesson order:', error));
            }
        });
    }
});
</script>
@endpush