<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date', Carbon::now()->subMonths(6));
        $endDate = $request->get('end_date', Carbon::now());

        if ($period != 'custom') {
            switch ($period) {
                case 'daily':
                    $startDate = Carbon::now()->subDays(30);
                    break;
                case 'weekly':
                    $startDate = Carbon::now()->subWeeks(12);
                    break;
                case 'monthly':
                    $startDate = Carbon::now()->subMonths(12);
                    break;
                case 'yearly':
                    $startDate = Carbon::now()->subYears(5);
                    break;
            }
        }

        $revenueData = $this->getRevenueData($period, $startDate, $endDate);
        
        // Get payment methods distribution
        $paymentMethods = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_method as method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Get top courses
        $topCourses = Course::withCount('enrollments')
            ->withSum('enrollments as total_revenue', 'amount')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();

        $summary = [
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
            'monthly_average' => Transaction::where('status', 'completed')
                ->whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()])
                ->avg('amount') ?? 0,
            'total_enrollments' => Enrollment::count(),
            'conversion_rate' => $this->calculateConversionRate(),
        ];

        return view('admin.reports.revenue', compact(
            'revenueData', 
            'summary', 
            'period',
            'paymentMethods',
            'topCourses'
        ));
    }

    private function getRevenueData($period, $startDate, $endDate)
    {
        $query = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate]);

        switch ($period) {
            case 'daily':
                return $query->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(*) as transactions_count'),
                        DB::raw('SUM(amount) as total')
                    )
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                    
            case 'weekly':
                return $query->select(
                        DB::raw('YEARWEEK(created_at) as week'),
                        DB::raw('COUNT(*) as transactions_count'),
                        DB::raw('SUM(amount) as total')
                    )
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get();
                    
            case 'monthly':
                return $query->select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                        DB::raw('COUNT(*) as transactions_count'),
                        DB::raw('SUM(amount) as total')
                    )
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
                    
            case 'yearly':
                return $query->select(
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('COUNT(*) as transactions_count'),
                        DB::raw('SUM(amount) as total')
                    )
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();
                    
            default:
                return collect();
        }
    }

    private function calculateConversionRate()
    {
        // This is a placeholder - you'd need to implement actual visitor tracking
        $totalVisitors = 10000; // You'd get this from analytics
        $totalEnrollments = Enrollment::count();
        
        return $totalVisitors > 0 ? round(($totalEnrollments / $totalVisitors) * 100, 2) : 0;
    }

    public function exportRevenue(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date', Carbon::now()->subMonths(6));
        $endDate = $request->get('end_date', Carbon::now());

        $revenueData = $this->getRevenueData($period, $startDate, $endDate);

        $filename = 'revenue-report-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['Period', 'Transactions', 'Revenue'];

        $callback = function() use ($revenueData, $period, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($revenueData as $data) {
                if ($period == 'daily') {
                    $periodLabel = Carbon::parse($data->date)->format('Y-m-d');
                } elseif ($period == 'weekly') {
                    $periodLabel = 'Week ' . $data->week;
                } elseif ($period == 'monthly') {
                    $periodLabel = $data->month;
                } elseif ($period == 'yearly') {
                    $periodLabel = $data->year;
                } else {
                    $periodLabel = 'N/A';
                }

                fputcsv($file, [
                    $periodLabel,
                    $data->transactions_count ?? 0,
                    $data->total ?? 0,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}