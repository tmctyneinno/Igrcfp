@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Course Management</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Courses</li>
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

    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
                <form method="GET" class="d-inline">
                    <select name="per_page" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </form>
                
                <form class="navbar-search" method="GET">
                    <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search courses..." value="{{ request('search') }}">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
                
                <form method="GET" class="d-inline d-flex">
                    <select name="status" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </form>

                <form method="GET" class="d-inline d-flex">
                    <select name="level" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                        <option value="">All Levels</option>
                        <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                        <option value="expert" {{ request('level') == 'expert' ? 'selected' : '' }}>Expert</option>
                    </select>
                    @if(request('search') || request('status') || request('level') || request('per_page') != 10)
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-secondary ms-2">Clear</a>
                    @endif
                </form>
            </div>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"> 
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Add New Course
            </a>
        </div>

        <form id="bulk-action-form" action="{{ route('admin.courses.bulk-action') }}" method="POST">
            @csrf
            <div class="card-body p-24">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <select name="action" class="form-select form-select-sm w-auto" required>
                        <option value="">Bulk Actions</option>
                        <option value="publish">Publish</option>
                        <option value="draft">Move to Draft</option>
                        <option value="archive">Archive</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Apply</button>
                </div>

                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col" width="50">
                                    <div class="d-flex align-items-center gap-10">
                                        <div class="form-check style-check d-flex align-items-center">
                                            <input class="form-check-input radius-4 border input-form-dark" type="checkbox" id="selectAll">
                                        </div>
                                        S.L
                                    </div>
                                </th>
                                <th scope="col">Course Image</th>
                                <th scope="col">Course Title</th>
                                <th scope="col">Short Title</th>
                                <th scope="col">Level</th>
                                <th scope="col" class="text-center">Modules</th>
                                <th scope="col" class="text-center">Price</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Featured</th>
                                <th scope="col" class="text-center">Popular</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courses as $course)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-10">
                                        <div class="form-check style-check d-flex align-items-center">
                                            <input class="form-check-input radius-4 border border-neutral-400 course-checkbox" type="checkbox" name="course_ids[]" value="{{ $course->id }}">
                                        </div>
                                        {{ $loop->iteration + ($courses->currentPage() - 1) * $courses->perPage() }}
                                    </div>
                                </td> 
                                <td>
                                    <div class="featured-image-container">
                                        @if($course->image)
                                            <img src="{{ asset('storage/'.$course->image) }}" alt="{{ $course->title }}" 
                                                 class="featured-image rounded-8" style="max-height: 40px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('images/default-course.jpg') }}'">
                                        @else
                                            <div class="featured-image rounded-8 bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                                                <iconify-icon icon="mdi:book-education-outline" class="icon text-muted"></iconify-icon>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column"> 
                                        <span class="text-md fw-medium text-secondary-light mb-1">{{ Str::limit($course->title, 40) }}</span>
                                        <small class="text-muted">{!! Str::limit($course->short_description, 60) !!}</small>
                                        <div class="mt-1">
                                            <small class="badge bg-light text-dark">{{ $course->code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $course->short_title }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $course->level === 'beginner' ? 'primary' : ($course->level === 'intermediate' ? 'info' : ($course->level === 'advanced' ? 'warning' : 'danger')) }}">
                                        {{ ucfirst($course->level) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <small class="fw-medium">{{ $course->modules_count ?? 0 }}</small>
                                        <div class="progress mt-1" style="height: 4px; width: 60px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $course->modules_count && $course->total_modules ? min(100, ($course->modules_count / $course->total_modules) * 100) : 0 }}%; background-color: #0A1F44;" 
                                                 aria-valuenow="{{ $course->modules_count ?? 0 }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $course->total_modules ?? 0 }}"></div>
                                        </div>
                                        <small class="text-muted text-xs mt-1">{{ $course->total_modules ?? 0 }} total</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($course->discount_price > 0)
                                        <div>
                                            <small class="text-decoration-line-through text-muted d-block">${{ number_format($course->price, 2) }}</small>
                                            <span class="badge bg-success">${{ number_format($course->discount_price, 2) }}</span>
                                            <small class="d-block text-success text-xs">
                                                Save {{ number_format((($course->price - $course->discount_price) / $course->price) * 100, 0) }}%
                                            </small>
                                        </div>
                                    @else
                                        @if($course->price > 0)
                                            <span class="badge bg-info">${{ number_format($course->price, 2) }}</span>
                                        @else
                                            <span class="badge bg-success">Free</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.courses.status', $course->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto; min-width: 100px;">
                                            <option value="draft" {{ $course->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published" {{ $course->status == 'published' ? 'selected' : '' }}>Published</option>
                                            <option value="archived" {{ $course->status == 'archived' ? 'selected' : '' }}>Archived</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.courses.toggle-featured', $course->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-link p-0" title="{{ $course->is_featured ? 'Remove Featured' : 'Mark as Featured' }}">
                                            @if($course->is_featured)
                                                <iconify-icon icon="mdi:star" class="icon text-warning" style="font-size: 1.2rem;"></iconify-icon>
                                            @else
                                                <iconify-icon icon="mdi:star-outline" class="icon text-muted" style="font-size: 1.2rem;"></iconify-icon>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.courses.toggle-popular', $course->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-link p-0" title="{{ $course->is_popular ? 'Remove Popular' : 'Mark as Popular' }}">
                                            @if($course->is_popular)
                                                <iconify-icon icon="mdi:fire" class="icon text-danger" style="font-size: 1.2rem;"></iconify-icon>
                                            @else
                                                <iconify-icon icon="mdi:fire-outline" class="icon text-muted" style="font-size: 1.2rem;"></iconify-icon>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center"> 
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <a href="{{ route('admin.courses.show', $course->slug) }}" 
                                           class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                           title="View" data-bs-toggle="tooltip">
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>
                                        @foreach($course->modules as $module)
                                        <a href="{{ route('admin.courses.edit', $course->slug) }}" 
                                           class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                           title="Edit" data-bs-toggle="tooltip">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
                                        
                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.courses.destroy', $course->slug) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" 
                                                    onclick="return confirm('Are you sure you want to delete this course? This action cannot be undone.')" 
                                                    title="Delete" data-bs-toggle="tooltip">
                                                <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <div class="text-muted py-4">
                                        <iconify-icon icon="mdi:book-education-outline" class="icon-3x mb-3"></iconify-icon>
                                        <h6 class="mb-2">No courses found</h6>
                                        <p class="mb-3">
                                            @if(request('search') || request('status') || request('level'))
                                                Try adjusting your search or filter to find what you're looking for.
                                            @else
                                                Get started by creating your first course.
                                            @endif
                                        </p>
                                        <div class="d-flex gap-2 justify-content-center">
                                            @if(request('search') || request('status') || request('level'))
                                                <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-secondary">Clear Filters</a>
                                            @endif
                                            <a href="{{ route('admin.courses.create') }}" class="btn btn-sm btn-primary">
                                                Create New Course
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($courses->count() > 0)
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <div>
                        <span class="text-muted">Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} entries</span>
                    </div>
                    <div>
                        {{ $courses->withQueryString()->links('vendor.pagination.custom') }}
                    </div>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions Success Modal -->
<div class="modal fade" id="bulkActionSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="mb-3">
                    <iconify-icon icon="mdi:check-circle" class="icon-4x text-success"></iconify-icon>
                </div>
                <h5 class="mb-2">Success!</h5>
                <p class="text-muted mb-0">Bulk action completed successfully</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.featured-image-container {
    width: 60px;
    height: 40px;
    border-radius: 8px;
    overflow: hidden;
}
.featured-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.form-select-sm {
    padding: 0.25rem 2rem 0.25rem 0.5rem;
    font-size: 0.875rem;
}
.table th {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
    text-transform: uppercase;
    border-bottom: 2px solid #dee2e6;
}
.table td {
    vertical-align: middle;
    padding: 0.75rem;
}
.progress {
    background-color: #e9ecef;
}
.badge {
    font-size: 0.75em;
    padding: 0.35em 0.65em;
}
.icon {
    font-size: 1.25rem;
}
.icon-3x {
    font-size: 3rem;
}
.icon-4x {
    font-size: 4rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Select all checkboxes functionality
    const selectAll = document.getElementById('selectAll');
    const courseCheckboxes = document.querySelectorAll('.course-checkbox');
    
    if (selectAll && courseCheckboxes.length > 0) {
        selectAll.addEventListener('change', function() {
            courseCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        });

        // Update select all checkbox when individual checkboxes change
        courseCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(courseCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(courseCheckboxes).some(cb => cb.checked);
                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked && !allChecked;
            });
        });
    }

    // Bulk action form validation
    const bulkForm = document.getElementById('bulk-action-form');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const actionSelect = this.querySelector('select[name="action"]');
            const checkedBoxes = document.querySelectorAll('.course-checkbox:checked');
            
            if (!actionSelect.value) {
                e.preventDefault();
                alert('Please select a bulk action.');
                return false;
            }
            
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one course.');
                return false;
            }
            
            if (actionSelect.value === 'delete') {
                if (!confirm(`Are you sure you want to delete ${checkedBoxes.length} selected course(s)? This action cannot be undone.`)) {
                    e.preventDefault();
                    return false;
                }
            } else {
                if (!confirm(`Apply "${actionSelect.options[actionSelect.selectedIndex].text}" to ${checkedBoxes.length} selected course(s)?`)) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    }

    // Auto-submit status change with loading indicator
    const statusSelects = document.querySelectorAll('select[name="status"]');
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const originalText = this.parentNode.querySelector('button[type="submit"]')?.innerHTML;
            const form = this.closest('form');
            
            // Show loading on the select itself
            this.disabled = true;
            this.style.backgroundImage = 'url("data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 100 100%27 preserveAspectRatio=%27xMidYMid%27%3E%3Ccircle cx=%2750%27 cy=%2750%27 fill=%27none%27 stroke=%27%230A1F44%27 stroke-width=%2710%27 r=%2735%27 stroke-dasharray=%27164.93361431346415 56.97787143782138%27%3E%3CanimateTransform attributeName=%27transform%27 type=%27rotate%27 repeatCount=%27indefinite%27 dur=%271s%27 values=%270 50 50%3B360 50 50%27 keyTimes=%270%3B1%27/%3E%3C/circle%3E%3C/svg%3E")';
            this.style.backgroundRepeat = 'no-repeat';
            this.style.backgroundPosition = 'right 0.5rem center';
            this.style.backgroundSize = '1rem 1rem';
            
            // Submit the form
            form.submit();
        });
    });

    // Search form submission with debounce
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.closest('form').submit();
            }, 500);
        });
    }

    // Show success modal if there's a success message in session
    @if(session('success') && str_contains(session('success'), 'Bulk action'))
        var bulkSuccessModal = new bootstrap.Modal(document.getElementById('bulkActionSuccessModal'));
        bulkSuccessModal.show();
    @endif

    // Handle clear filters button
    const clearFiltersBtn = document.querySelector('a[href="{{ route('admin.courses.index') }}"]');
    if (clearFiltersBtn && clearFiltersBtn.textContent.includes('Clear')) {
        clearFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = "{{ route('admin.courses.index') }}";
        });
    }
});
</script>
@endpush