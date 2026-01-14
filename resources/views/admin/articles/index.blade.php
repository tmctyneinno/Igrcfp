@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Article Management</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Articles</li>
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
                    <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search articles..." value="{{ request('search') }}">
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
                    <select name="category" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $categoryItem)
                            <option value="{{ $categoryItem->id }}" {{ request('category') == $categoryItem->id ? 'selected' : '' }}>
                                {{ $categoryItem->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
                
                @if(request('search') || request('status') || request('category') || request('per_page') != 10)
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-sm btn-outline-secondary">Clear Filters</a>
                @endif
            </div>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"> 
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Add New Article
            </a>
        </div>

        <form id="bulk-action-form" action="{{ route('admin.articles.bulk-action') }}" method="POST">
            @csrf
            <div class="card-body p-24">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <select name="action" class="form-select form-select-sm w-auto" required>
                        <option value="">Bulk Actions</option>
                        <option value="publish">Publish</option>
                        <option value="draft">Move to Draft</option>
                        <option value="archive">Archive</option>
                        <option value="feature">Feature</option>
                        <option value="unfeature">Remove Featured</option>
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
                                <th scope="col">Image</th>
                                <th scope="col">Title</th>
                                <th scope="col">Category</th>
                                <th scope="col">Author</th>
                                <th scope="col" class="text-center">Publish Date</th>
                                <th scope="col" class="text-center">Views</th>
                                <th scope="col" class="text-center">Read Time</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Featured</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $article)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-10">
                                        <div class="form-check style-check d-flex align-items-center">
                                            <input class="form-check-input radius-4 border border-neutral-400 article-checkbox" type="checkbox" name="article_ids[]" value="{{ $article->id }}">
                                        </div>
                                        {{ $loop->iteration + ($articles->currentPage() - 1) * $articles->perPage() }}
                                    </div>
                                </td>
                                <td>
                                    <div class="featured-image-container"> 
                                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" style="max-height: 20px;"
                                             class="featured-image rounded-8">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('admin.articles.edit', $article) }}" class="text-md fw-medium text-secondary-light mb-1 hover-text-primary">
                                            {{ Str::limit($article->title, 40) }}
                                        </a>
                                        <small class="text-muted">{{ Str::limit($article->excerpt, 60) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $article->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-sm fw-normal text-secondary-light">
                                        {{ $article->author->name ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($article->published_at)
                                        <div class="d-flex flex-column">
                                            <small class="fw-medium">{{ $article->published_at->format('M d, Y') }}</small>
                                            <small class="text-muted">{{ $article->published_at->format('h:i A') }}</small>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">
                                        {{ number_format($article->views) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">
                                        {{ $article->read_time }} min
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusColors = [
                                            'published' => 'success',
                                            'draft' => 'warning',
                                            'archived' => 'secondary'
                                        ];
                                        $statusColor = $statusColors[$article->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($article->is_featured)
                                        <iconify-icon icon="mdi:star" class="icon text-warning"></iconify-icon>
                                    @else
                                        <iconify-icon icon="mdi:star-outline" class="icon text-muted"></iconify-icon>
                                    @endif
                                </td>
                                <td class="text-center"> 
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <!-- View Article -->
                                        <a href="{{ route('news.show', $article->slug) }}" target="_blank"
                                           class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                           title="View">
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>
                                        
                                        <!-- Edit Article -->
                                        <a href="{{ route('admin.articles.edit', $article) }}" 
                                           class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none" 
                                           title="Edit">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
                                        
                                        <!-- Toggle Featured -->
                                        <form action="{{ route('admin.articles.toggle-featured', $article) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="bg-{{ $article->is_featured ? 'warning' : 'secondary' }}-focus bg-hover-{{ $article->is_featured ? 'warning' : 'secondary' }}-200 text-{{ $article->is_featured ? 'warning' : 'secondary' }}-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" 
                                                    title="{{ $article->is_featured ? 'Remove Featured' : 'Mark as Featured' }}">
                                                <iconify-icon icon="mdi:star" class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                        
                                        <!-- Quick Status Toggle -->
                                        <div class="dropdown d-inline">
                                            <button class="bg-primary-focus bg-hover-primary-200 text-primary-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" 
                                                    type="button" 
                                                    data-bs-toggle="dropdown" 
                                                    title="Change Status">
                                                <iconify-icon icon="mdi:swap-vertical" class="menu-icon"></iconify-icon>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <form action="{{ route('admin.articles.update-status', $article) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="published">
                                                        <button type="submit" class="dropdown-item {{ $article->status == 'published' ? 'active' : '' }}">
                                                            <iconify-icon icon="mdi:check-circle-outline"></iconify-icon> Publish
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.articles.update-status', $article) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="draft">
                                                        <button type="submit" class="dropdown-item {{ $article->status == 'draft' ? 'active' : '' }}">
                                                            <iconify-icon icon="mdi:pencil-outline"></iconify-icon> Draft
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.articles.update-status', $article) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="archived">
                                                        <button type="submit" class="dropdown-item {{ $article->status == 'archived' ? 'active' : '' }}">
                                                            <iconify-icon icon="mdi:archive-outline"></iconify-icon> Archive
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <!-- Delete Article -->
                                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" 
                                                    onclick="return confirm('Are you sure you want to delete this article?')" 
                                                    title="Delete">
                                                <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <div class="text-muted">
                                        <iconify-icon icon="mdi:newspaper-variant-outline" class="icon-3x mb-2"></iconify-icon>
                                        <p>No articles found.</p>
                                        @if(request('search') || request('status') || request('category'))
                                            <a href="{{ route('admin.articles.index') }}" class="btn btn-sm btn-primary">Clear Filters</a>
                                        @else
                                            <a href="{{ route('admin.articles.create') }}" class="btn btn-sm btn-primary">Create Your First Article</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <span>Showing {{ $articles->firstItem() }} to {{ $articles->lastItem() }} of {{ $articles->total() }} entries</span>
                    {{ $articles->links('vendor.pagination.custom') }}
                </div>
            </div>
        </form>
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
.dropdown-menu {
    min-width: 180px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkboxes
    const selectAll = document.getElementById('selectAll');
    const articleCheckboxes = document.querySelectorAll('.article-checkbox');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            articleCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        });
    }

    // Bulk action form validation
    const bulkForm = document.getElementById('bulk-action-form');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.article-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one article.');
                return false;
            }
            
            const action = this.querySelector('select[name="action"]').value;
            if (!action) {
                e.preventDefault();
                alert('Please select a bulk action.');
                return false;
            }
        });
    }
    
    // Update select all checkbox state
    articleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(articleCheckboxes).every(cb => cb.checked);
            selectAll.checked = allChecked;
            selectAll.indeterminate = !allChecked && Array.from(articleCheckboxes).some(cb => cb.checked);
        });
    });
});
</script>
@endpush