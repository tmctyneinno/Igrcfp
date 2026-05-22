@extends('admin.layouts.app')

@section('content')
@php
    $money = fn ($amount) => '£' . number_format((float) $amount, 2);
    $statusBadge = function ($status) {
        return match ($status) {
            'completed', 'active', 'accepted' => 'bg-success-focus text-success-main',
            'pending', 'pending_payment', 'pending_approval', 'under_review' => 'bg-warning-focus text-warning-main',
            'failed', 'cancelled', 'rejected' => 'bg-danger-focus text-danger-main',
            default => 'bg-neutral-200 text-secondary-light',
        };
    };
@endphp

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Admin Dashboard</h6>
            <p class="text-sm text-secondary-light mb-0">Welcome back, {{ $stats['admin_name'] }}. Here is the summary of your admin content.</p>
        </div>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
        </ul>
    </div>

    <div class="row row-cols-xxxl-5 row-cols-lg-4 row-cols-sm-2 row-cols-1 gy-4">
        <div class="col">
            <div class="card shadow-none border bg-gradient-start-1 h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <p class="fw-medium text-primary-light mb-1">Total Users</p>
                            <h6 class="mb-0">{{ number_format($stats['total_users']) }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="flowbite:users-group-outline" class="text-white text-2xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-none border bg-gradient-start-2 h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <p class="fw-medium text-primary-light mb-1">Courses</p>
                            <h6 class="mb-0">{{ number_format($stats['total_courses']) }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:document-text-outline" class="text-white text-2xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-none border bg-gradient-start-3 h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <p class="fw-medium text-primary-light mb-1">Enrollments</p>
                            <h6 class="mb-0">{{ number_format($stats['total_enrollments']) }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mingcute:user-star-line" class="text-white text-2xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-none border bg-gradient-start-4 h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <p class="fw-medium text-primary-light mb-1">Revenue</p>
                            <h6 class="mb-0">{{ $money($stats['total_revenue']) }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-none border bg-gradient-start-5 h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <p class="fw-medium text-primary-light mb-1">Certificates Pending</p>
                            <h6 class="mb-0">{{ number_format($stats['pending_certificates']) }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-warning rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:diploma-bold-duotone" class="text-white text-2xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mt-1">
        <div class="col-xxl-8 col-xl-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-20">
                        <h6 class="mb-0 fw-bold text-lg">Admin Content Summary</h6>
                        <span class="text-sm text-secondary-light">Main areas available in the admin panel</span>
                    </div>

                    <div class="row gy-3">
                        @foreach([
                            ['label' => 'Admin Users', 'value' => $stats['total_admins'], 'icon' => 'ic:baseline-admin-panel-settings', 'route' => 'admin.admins.index'],
                            ['label' => 'Users', 'value' => $stats['total_users'], 'icon' => 'flowbite:users-group-outline', 'route' => 'admin.users.index'],
                            ['label' => 'Courses', 'value' => $stats['total_courses'], 'icon' => 'solar:document-text-outline', 'route' => 'admin.courses.index'],
                            ['label' => 'Assessments', 'value' => $stats['total_assessments'], 'icon' => 'solar:clipboard-check-outline', 'route' => 'admin.assessments.all'],
                            ['label' => 'Certificates Generated', 'value' => $stats['generated_certificates'], 'icon' => 'solar:diploma-bold-duotone', 'route' => 'admin.certificates.index'],
                            ['label' => 'Transactions', 'value' => $stats['total_transactions'], 'icon' => 'hugeicons:transaction', 'route' => 'admin.transactions.index'],
                            ['label' => 'Membership Plans', 'value' => $stats['membership_plans'], 'icon' => 'mdi:account-badge-outline', 'route' => 'admin.membership-plans.index'],
                            ['label' => 'Mentors', 'value' => $stats['total_mentors'], 'icon' => 'mdi:account-group-outline', 'route' => 'admin.mentors.index'],
                            ['label' => 'Scholarships', 'value' => $stats['total_scholarships'], 'icon' => 'ph:student-fill', 'route' => 'admin.scholarships.index'],
                            ['label' => 'Blogs', 'value' => $stats['total_blogs'], 'icon' => 'hugeicons:invoice-03', 'route' => 'admin.blogs.index'],
                            ['label' => 'Events', 'value' => $stats['total_events'], 'icon' => 'solar:calendar-outline', 'route' => 'admin.events.index'],
                            ['label' => 'News', 'value' => $stats['total_articles'], 'icon' => 'solar:document-add-outline', 'route' => 'admin.articles.index'],
                        ] as $item)
                            <div class="col-md-6 col-xl-4">
                                <a href="{{ route($item['route']) }}" class="border radius-8 p-16 d-flex align-items-center justify-content-between gap-3 hover-bg-neutral-100 h-100">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="w-40-px h-40-px bg-primary-50 text-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                                            <iconify-icon icon="{{ $item['icon'] }}" class="text-xl"></iconify-icon>
                                        </span>
                                        <span class="fw-medium text-primary-light">{{ $item['label'] }}</span>
                                    </div>
                                    <span class="fw-bold text-primary-light">{{ number_format($item['value']) }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-xl-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <h6 class="mb-20 fw-bold text-lg">Needs Attention</h6>

                    @foreach([
                        ['label' => 'Pending enrollments', 'value' => $stats['pending_enrollments'], 'route' => 'admin.enrollments.pending', 'class' => 'text-warning-main'],
                        ['label' => 'Pending certificates', 'value' => $stats['pending_certificates'], 'route' => 'admin.certificates.index', 'class' => 'text-warning-main'],
                        ['label' => 'Pending transactions', 'value' => $stats['pending_transactions'], 'route' => 'admin.transactions.pending', 'class' => 'text-warning-main'],
                        ['label' => 'Failed transactions', 'value' => $stats['failed_transactions'], 'route' => 'admin.transactions.failed', 'class' => 'text-danger-main'],
                        ['label' => 'Membership approvals', 'value' => $stats['pending_memberships'], 'route' => 'admin.membership-approvals.index', 'class' => 'text-warning-main'],
                        ['label' => 'Mentor applications', 'value' => $stats['pending_mentor_applications'], 'route' => 'admin.mentor-applications.index', 'class' => 'text-warning-main'],
                        ['label' => 'Scholarship pending review', 'value' => $stats['pending_scholarships'] + $stats['under_review_scholarships'], 'route' => 'admin.scholarships.index', 'class' => 'text-warning-main'],
                    ] as $item)
                        <a href="{{ route($item['route']) }}" class="d-flex align-items-center justify-content-between gap-3 py-12 border-bottom">
                            <span class="text-secondary-light">{{ $item['label'] }}</span>
                            <span class="fw-bold {{ $item['class'] }}">{{ number_format($item['value']) }}</span>
                        </a>
                    @endforeach

                    <div class="mt-24 p-16 border radius-8">
                        <p class="text-sm text-secondary-light mb-8">Enrollment status</p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="bg-success-focus text-success-main px-12 py-6 rounded-pill text-sm">Completed: {{ number_format($stats['completed_enrollments']) }}</span>
                            <span class="bg-warning-focus text-warning-main px-12 py-6 rounded-pill text-sm">Pending: {{ number_format($stats['pending_enrollments']) }}</span>
                            <span class="bg-danger-focus text-danger-main px-12 py-6 rounded-pill text-sm">Cancelled: {{ number_format($stats['cancelled_enrollments']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-xl-6">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-20">
                        <h6 class="mb-0 fw-bold text-lg">Latest Users</h6>
                        <a href="{{ route('admin.users.index') }}" class="text-primary-600 hover-text-primary text-sm">View All</a>
                    </div>

                    @forelse($recentUsers as $user)
                        <div class="d-flex align-items-center justify-content-between gap-3 mb-16">
                            <div>
                                <h6 class="text-md mb-0 fw-medium">{{ $user->name }}</h6>
                                <span class="text-sm text-secondary-light">{{ $user->email }}</span>
                            </div>
                            <span class="text-sm text-secondary-light">{{ $user->created_at?->format('d M Y') }}</span>
                        </div>
                    @empty
                        <p class="text-secondary-light mb-0">No users yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-xl-6">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-20">
                        <h6 class="mb-0 fw-bold text-lg">Latest Enrollments</h6>
                        <a href="{{ route('admin.enrollments.index') }}" class="text-primary-600 hover-text-primary text-sm">View All</a>
                    </div>

                    @forelse($recentEnrollments as $enrollment)
                        <div class="d-flex align-items-center justify-content-between gap-3 mb-16">
                            <div>
                                <h6 class="text-md mb-0 fw-medium">{{ $enrollment->user?->name ?? $enrollment->name ?? 'Guest User' }}</h6>
                                <span class="text-sm text-secondary-light">{{ $enrollment->course?->title ?? 'No course attached' }}</span>
                            </div>
                            <span class="{{ $statusBadge($enrollment->status) }} px-12 py-4 rounded-pill fw-medium text-sm">
                                {{ ucwords(str_replace('_', ' ', $enrollment->status)) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-secondary-light mb-0">No enrollments yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-xl-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-20">
                        <h6 class="mb-0 fw-bold text-lg">Latest Transactions</h6>
                        <a href="{{ route('admin.transactions.index') }}" class="text-primary-600 hover-text-primary text-sm">View All</a>
                    </div>

                    @forelse($recentTransactions as $transaction)
                        <div class="d-flex align-items-center justify-content-between gap-3 mb-16">
                            <div>
                                <h6 class="text-md mb-0 fw-medium">{{ $transaction->user?->name ?? 'Unknown User' }}</h6>
                                <span class="text-sm text-secondary-light">{{ $transaction->reference ?? $transaction->transaction_id ?? 'No reference' }}</span>
                            </div>
                            <div class="text-end">
                                <h6 class="text-md mb-0 fw-medium">{{ $money($transaction->amount) }}</h6>
                                <span class="{{ $statusBadge($transaction->status) }} px-12 py-4 rounded-pill fw-medium text-sm">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-secondary-light mb-0">No transactions yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
