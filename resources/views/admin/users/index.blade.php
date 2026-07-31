@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Users Management</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Users</li>
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
                    <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search" value="{{ request('search') }}">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
                
                <form method="GET" class="d-inline d-flex">
                    <select name="status" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    <select name="role" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px ms-2" onchange="this.form.submit()">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="tutor" {{ request('role') == 'tutor' ? 'selected' : '' }}>Tutor</option>
                        <option value="learner" {{ request('role') == 'learner' ? 'selected' : '' }}>Learner</option>
                    </select>
                    {{-- New Filter for Scholarship --}}
                    <select name="scholarship" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px ms-2" onchange="this.form.submit()">
                        <option value="">All Users</option>
                        <option value="1" {{ request('scholarship') == '1' ? 'selected' : '' }}>Scholarship Applicants</option>
                        <option value="0" {{ request('scholarship') == '0' ? 'selected' : '' }}>Non-Scholarship</option>
                    </select>
                    <select name="enrolled" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px ms-2" onchange="this.form.submit()">
                        <option value="">All Users</option>
                        <option value="1" {{ request('enrolled') == '1' ? 'selected' : '' }}>Enrolled</option>
                        <option value="0" {{ request('enrolled') == '0' ? 'selected' : '' }}>Not Enrolled</option>
                    </select>
                    @if(request('search') || request('status') || request('role') || request('scholarship') || request('enrolled') || request('per_page') != 10)
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary ms-2">Clear</a>
                    @endif
                </form>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"> 
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Add New User
            </a>
        </div>

        <form id="bulk-action-form" action="{{ route('admin.users.bulk-action') }}" method="POST">
            @csrf
            <div class="card-body p-24">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <select name="action" class="form-select form-select-sm w-auto" required>
                        <option value="">Bulk Actions</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
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
                                <th scope="col">Join Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col" class="text-center">Scholarship</th> {{-- New Column --}}
                                <th scope="col" class="text-center">Enrollments</th>
                                <th scope="col" class="text-center">Transactions</th>
                                <th scope="col" class="text-center">Role</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Verified</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-10">
                                        <div class="form-check style-check d-flex align-items-center">
                                            <input class="form-check-input radius-4 border border-neutral-400 user-checkbox" type="checkbox" name="user_ids[]" value="{{ $user->id }}">
                                        </div> 
                                        {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                    </div>
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" 
                                             class="w-20-px h-20-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                                        <div class="flex-grow-1">
                                            <span class="text-md mb-0 fw-normal text-secondary-light">{{ $user->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                
                                {{-- Scholarship Column --}}
                                <td class="text-center">
                                    @if($user->is_scholarship_applicant)
                                        <span class="badge bg-primary text-white px-12 py-6 radius-8" title="Scholarship Applicant">
                                            <i class="fas fa-graduation-cap me-1"></i> Yes
                                        </span>
                                    @else
                                        <span class="badge bg-secondary text-secondary-light px-12 py-6 radius-8">
                                            No
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @php
                                        $enrollmentCount = $user->enrollments->count();
                                        $activeEnrollments = $user->activeEnrollments->count();
                                        $completedEnrollments = $user->completedEnrollments->count();
                                    @endphp
                                    @if($enrollmentCount > 0)
                                        <a href="{{ route('admin.users.enrollments', $user) }}" class="text-decoration-none">
                                            <span class="badge bg-success text-white px-12 py-6 radius-8 hover-bg-success-700">
                                                {{ $enrollmentCount }} Course{{ $enrollmentCount != 1 ? 's' : '' }}
                                            </span>
                                            @if($activeEnrollments > 0)
                                                <br><small class="text-success">{{ $activeEnrollments }} active</small>
                                            @endif
                                            @if($completedEnrollments > 0)
                                                <br><small class="text-info">{{ $completedEnrollments }} completed</small>
                                            @endif
                                        </a>
                                    @else
                                        <span class="badge bg-secondary text-white px-12 py-6 radius-8">
                                            Not Enrolled
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $transactionCount = $user->transactions->count();
                                        $totalSpent = $user->transactions->where('status', 'completed')->sum('amount');
                                        $completedTransactions = $user->transactions->where('status', 'completed')->count();
                                    @endphp
                                    @if($transactionCount > 0)
                                        <span class="badge bg-info text-white px-12 py-6 radius-8">
                                            {{ $transactionCount }} Transaction{{ $transactionCount != 1 ? 's' : '' }}
                                        </span>
                                        @if($totalSpent > 0)
                                            <br><small class="text-success">${{ number_format($totalSpent, 2) }} spent</small>
                                        @endif
                                        @if($completedTransactions > 0)
                                            <br><small class="text-muted">{{ $completedTransactions }} completed</small>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary text-white px-12 py-6 radius-8">
                                            No Transactions
                                        </span>
                                    @endif
                                </td> 
                                <td class="text-center">
                                    @php
                                        $roleColors = [
                                            'admin' => 'danger',
                                            'tutor' => 'info',
                                            'learner' => 'primary'
                                        ];
                                        $roleColor = $roleColors[$user->role] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $roleColor }}-600 text-white px-12 py-6 radius-8">
                                        {{ ucfirst($user->role ?? 'Learner') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusColors = [
                                            'active' => 'success',
                                            'pending' => 'warning',
                                            'suspended' => 'danger'
                                        ];
                                        $statusColor = $statusColors[$user->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}-600 text-white px-12 py-6 radius-8">
                                        {{ ucfirst($user->status ?? 'Active') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success-600 text-white px-8 py-4 radius-4" title="Email Verified">
                                            <iconify-icon icon="solar:check-circle-bold" class="icon"></iconify-icon>
                                        </span>
                                    @else
                                        <span class="badge bg-warning-600 text-white px-8 py-4 radius-4" title="Email Not Verified">
                                            <iconify-icon icon="solar:clock-circle-bold" class="icon"></iconify-icon>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center"> 
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none"
                                           title="View Details"> 
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" 
                                                    onclick="return confirm('Are you sure you want to delete this user?')"
                                                    title="Delete"> 
                                                <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-4">
                                    <div class="text-muted">No users found.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <span>Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries</span>
                    {{ $users->links('vendor.pagination.custom') }}
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .w-30-px { width: 30px; }
    .h-30-px { height: 30px; }
    .w-40-px { width: 40px; }
    .h-40-px { height: 40px; }
    .rounded-8 { border-radius: 8px; }
    .object-fit-cover { object-fit: cover; }
    .bg-danger-600 { background-color: #dc2626 !important; }
    .bg-info-600 { background-color: #2563eb !important; }
    .bg-success-600 { background-color: #16a34a !important; }
    .bg-warning-600 { background-color: #d97706 !important; }
    .bg-secondary-600 { background-color: #6b7280 !important; }
    .bg-primary-600 { background-color: #2563eb !important; }
    .bg-emerald-600 { background-color: #059669 !important; }
    .bg-secondary-100 { background-color: #f3f4f6 !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        });
    }

    const bulkForm = document.getElementById('bulk-action-form');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one user.');
                return false;
            }
        });
    }
});
</script>
@endpush