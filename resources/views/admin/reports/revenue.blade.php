@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Revenue Reports</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Reports</li>
            <li>-</li>
            <li class="fw-medium">Revenue</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Date Range Filter -->
    <div class="card mb-24">
        <div class="card-body p-24">
            <form method="GET" action="{{ route('admin.reports.revenue') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Period</label>
                    <select name="period" class="form-select" onchange="this.form.submit()">
                        <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', now()->subMonths(6)->format('Y-m-d')) }}">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                </div>
                
                @if(request('period') || request('start_date') || request('end_date'))
                <div class="col-12">
                    <a href="{{ route('admin.reports.revenue') }}" class="btn btn-sm btn-outline-secondary">Reset Filters</a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row gy-4 mb-24">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 p-0 radius-12 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="mb-0 fw-medium text-secondary-light text-md">Total Revenue</span>
                            <h4 class="fw-semibold mb-0 mt-2 text-primary-light">${{ number_format($summary['total_revenue'] ?? 0, 2) }}</h4>
                        </div>
                        <div class="w-50-px h-50-px bg-primary-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:cash-multiple" class="icon text-2xl text-primary-600"></iconify-icon>
                        </div>
                    </div>
                    <p class="text-sm text-secondary-light mt-12 mb-0">
                        <span class="text-success">↑ 12%</span> vs last period
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card h-100 p-0 radius-12 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="mb-0 fw-medium text-secondary-light text-md">Monthly Average</span>
                            <h4 class="fw-semibold mb-0 mt-2 text-success-main">${{ number_format($summary['monthly_average'] ?? 0, 2) }}</h4>
                        </div>
                        <div class="w-50-px h-50-px bg-success-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:chart-line" class="icon text-2xl text-success-600"></iconify-icon>
                        </div>
                    </div>
                    <p class="text-sm text-secondary-light mt-12 mb-0">
                        Based on last 30 days
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card h-100 p-0 radius-12 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="mb-0 fw-medium text-secondary-light text-md">Total Enrollments</span>
                            <h4 class="fw-semibold mb-0 mt-2 text-info-main">{{ $summary['total_enrollments'] ?? 0 }}</h4>
                        </div>
                        <div class="w-50-px h-50-px bg-info-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:account-school" class="icon text-2xl text-info-600"></iconify-icon>
                        </div>
                    </div>
                    <p class="text-sm text-secondary-light mt-12 mb-0">
                        Lifetime enrollments
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card h-100 p-0 radius-12 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <div>
                            <span class="mb-0 fw-medium text-secondary-light text-md">Conversion Rate</span>
                            <h4 class="fw-semibold mb-0 mt-2 text-warning-main">{{ $summary['conversion_rate'] ?? 0 }}%</h4>
                        </div>
                        <div class="w-50-px h-50-px bg-warning-50 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="mdi:percent" class="icon text-2xl text-warning-600"></iconify-icon>
                        </div>
                    </div>
                    <p class="text-sm text-secondary-light mt-12 mb-0">
                        Visitors to enrollments
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="row gy-4 mb-24">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Revenue Overview ({{ ucfirst($period) }})</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="exportChart()">
                            <iconify-icon icon="mdi:download" class="me-1"></iconify-icon>
                            Download Chart
                        </button>
                    </div>
                </div>
                <div class="card-body p-24">
                    <canvas id="revenueChart" style="width: 100%; height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Table -->
    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Revenue Details</h5>
                    <div class="d-flex gap-2">
                        {{-- <a href="{{ route('admin.reports.revenue.export', request()->query()) }}" class="btn btn-sm btn-success">
                            <iconify-icon icon="mdi:export" class="me-1"></iconify-icon>
                            Export CSV
                        </a> --}}
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Period</th>
                                    <th scope="col">Transactions</th>
                                    <th scope="col">Revenue</th>
                                    <th scope="col">Growth</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revenueData as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($period == 'daily')
                                            {{ \Carbon\Carbon::parse($data->date)->format('M d, Y') }}
                                        @elseif($period == 'weekly')
                                            Week {{ $data->week }}
                                        @elseif($period == 'monthly')
                                            {{ $data->month }}
                                        @elseif($period == 'yearly')
                                            {{ $data->year }}
                                        @endif
                                    </td>
                                    <td>{{ $data->transactions_count ?? 0 }}</td>
                                    <td class="fw-bold text-success">${{ number_format($data->total, 2) }}</td>
                                    <td>
                                        @php
                                            $prevRevenue = $revenueData[$index - 1]->total ?? 0;
                                            $growth = $prevRevenue > 0 ? (($data->total - $prevRevenue) / $prevRevenue) * 100 : 0;
                                        @endphp
                                        @if($growth > 0)
                                            <span class="text-success">↑ {{ number_format($growth, 1) }}%</span>
                                        @elseif($growth < 0)
                                            <span class="text-danger">↓ {{ number_format(abs($growth), 1) }}%</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <iconify-icon icon="mdi:chart-line" class="icon-3x mb-2"></iconify-icon>
                                            <p>No revenue data available for the selected period.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <th colspan="2" class="text-end">Total:</th>
                                    <th>{{ $revenueData->sum('transactions_count') ?? 0 }}</th>
                                    <th class="text-success">${{ number_format($revenueData->sum('total') ?? 0, 2) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Courses Section -->
    <div class="row gy-4 mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h5 class="mb-0">Top Performing Courses</h5>
                </div>
                <div class="card-body p-24">
                    <div class="table-responsive">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Enrollments</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCourses ?? [] as $course)
                                <tr>
                                    <td>{{ Str::limit($course->title, 30) }}</td>
                                    <td>{{ $course->enrollments_count }}</td>
                                    <td class="text-success">${{ number_format($course->total_revenue, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods Distribution -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h5 class="mb-0">Payment Methods</h5>
                </div>
                <div class="card-body p-24">
                    <canvas id="paymentMethodsChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress {
        height: 8px;
        border-radius: 4px;
    }
    .progress-bar {
        border-radius: 4px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    @php
        $labels = [];
        $values = [];
        
        foreach($revenueData as $data) {
            if($period == 'daily') {
                $labels[] = \Carbon\Carbon::parse($data->date)->format('M d');
            } elseif($period == 'weekly') {
                $labels[] = 'Week ' . $data->week;
            } elseif($period == 'monthly') {
                $labels[] = $data->month;
            } elseif($period == 'yearly') {
                $labels[] = $data->year;
            }
            $values[] = $data->total;
        }
    @endphp

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode($values) !!},
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });

    // Payment Methods Chart
    @if(isset($paymentMethods))
    const pieCtx = document.getElementById('paymentMethodsChart').getContext('2d');
    
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($paymentMethods->pluck('method')) !!},
            datasets: [{
                data: {!! json_encode($paymentMethods->pluck('total')) !!},
                backgroundColor: [
                    '#4CAF50',
                    '#FF9800',
                    '#2196F3',
                    '#9C27B0',
                    '#F44336'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: $${value.toFixed(2)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    @endif

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

function exportChart() {
    const canvas = document.getElementById('revenueChart');
    const link = document.createElement('a');
    link.download = 'revenue-chart.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}
</script>
@endpush