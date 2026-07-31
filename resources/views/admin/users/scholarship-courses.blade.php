<!-- resources/views/admin/users/scholarship-courses.blade.php -->
@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body pb-5">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Manage Scholarship Courses: {{ $user->name }}</h6>
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary btn-sm">
            ← Back to User
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header bg-emerald-50 border-bottom border-emerald-100 py-16 px-24">
            <h6 class="card-title mb-0 text-emerald-700">Assign Individual Courses</h6>
            <p class="text-sm text-secondary-light mb-0 mt-1">
                Select specific courses to grant free scholarship access. An email notification will be sent for each newly assigned course.
            </p>
        </div>
        
        <!-- Search & Filter Section -->
        <div class="card-body border-bottom bg-base p-24">
            <form method="GET" action="{{ route('admin.users.scholarship-courses', $user) }}" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label text-secondary-light small fw-medium">Search Courses</label>
                    <div class="input-group">
                        <span class="input-group-text bg-base border-end-0"><iconify-icon icon="solar:magnifer-linear"></iconify-icon></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by title or category..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-secondary-light small fw-medium">Filter by Category</label>
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">All Eligible Categories</option>
                        @foreach($eligibleCategories as $cat)
                            <option value="{{ $cat }}" {{ $categoryFilter == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    @if($search || $categoryFilter)
                        <a href="{{ route('admin.users.scholarship-courses', $user) }}" class="btn btn-outline-secondary w-100">Clear</a>
                    @else
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-body p-24">
            <form action="{{ route('admin.users.update-scholarship-courses', $user) }}" method="POST">
                @csrf
                
                <!-- Preserve Search Filters in Submission -->
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="category" value="{{ $categoryFilter }}">

                <div class="row g-3">
                    @forelse($availableCourses as $course)
                        <div class="col-md-6 col-lg-4">
                            <div class="form-check card p-16 h-100 cursor-pointer transition-all {{ in_array($course->id, $assignedCourseIds) ? 'border-primary-600 bg-primary-50 shadow-sm' : 'border-neutral-200 hover-border-primary-300' }}" 
                                 onclick="toggleCheckbox('course_{{ $course->id }}')">
                                
                                <input class="form-check-input position-static mt-1 me-2" type="checkbox" 
                                       name="course_ids[]" 
                                       value="{{ $course->id }}" 
                                       id="course_{{ $course->id }}"
                                       {{ in_array($course->id, $assignedCourseIds) ? 'checked' : '' }}>
                                
                                <label class="form-check-label w-100 pt-1" for="course_{{ $course->id }}">
                                    <span class="fw-medium d-block text-dark">{{ $course->title }}</span>
                                    <span class="badge bg-neutral-100 text-neutral-600 radius-4 px-6 py-2 text-xs mt-1 d-inline-block">
                                        {{ $course->igrcfp_category }}
                                    </span>
                                    <div class="small text-muted d-block mt-2">
                                        <iconify-icon icon="solar:layer-linear" class="icon-xs me-1"></iconify-icon> {{ $course->level }} 
                                        <span class="mx-1">•</span> 
                                        <iconify-icon icon="solar:clock-circle-linear" class="icon-xs me-1"></iconify-icon> {{ $course->duration }}
                                    </div>
                                </label>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="mb-3 text-muted">
                                <iconify-icon icon="solar:folder-error-linear" class="fs-1"></iconify-icon>
                            </div>
                            <p class="text-muted mb-0">No courses found matching your criteria.</p>
                            @if($search || $categoryFilter)
                                <a href="{{ route('admin.users.scholarship-courses', $user) }}" class="text-primary small mt-2 d-inline-block">Clear filters</a>
                            @endif
                        </div>
                    @endforelse
                </div>

                <div class="mt-24 d-flex gap-2 justify-content-end border-top pt-24">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary px-24">Cancel</a>
                    <button type="submit" class="btn btn-primary px-24">
                        <iconify-icon icon="solar:diskette-linear" class="me-1"></iconify-icon>
                        Save Assignments
                    </button>
                </div>
            </form>
        </div>
    </div> 
</div>
@endsection

@push('scripts')
<script>
    function toggleCheckbox(id) {
        const checkbox = document.getElementById(id);
        // Prevent double-toggling if clicking directly on the checkbox input
        if (event.target.type !== 'checkbox') {
            checkbox.checked = !checkbox.checked;
        }
        
        // Visual feedback logic
        const card = checkbox.closest('.card');
        if (checkbox.checked) {
            card.classList.add('border-primary-600', 'bg-primary-50', 'shadow-sm');
            card.classList.remove('border-neutral-200');
        } else {
            card.classList.remove('border-primary-600', 'bg-primary-50', 'shadow-sm');
            card.classList.add('border-neutral-200');
        }
    }
</script>
@endpush

@push('styles')
<style>
    .cursor-pointer { cursor: pointer; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .hover-border-primary-300:hover { border-color: #93c5fd !important; }
</style>
@endpush