<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date', Carbon::now()->subMonths(6));
        $endDate = $request->get('end_date', Carbon::now());

        $revenueData = $this->getRevenueData($period, $startDate, $endDate);
        
        $summary = [
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
            'monthly_average' => Transaction::where('status', 'completed')
                ->whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()])
                ->avg('amount') ?? 0,
            'total_enrollments' => Enrollment::count(),
            'conversion_rate' => $this->calculateConversionRate(),
        ];

        return view('admin.reports.revenue', compact('revenueData', 'summary', 'period'));
    }

    private function getRevenueData($period, $startDate, $endDate)
    {
        $query = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate]);

        switch ($period) {
            case 'daily':
                return $query->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
            case 'weekly':
                return $query->selectRaw('YEARWEEK(created_at) as week, SUM(amount) as total')
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get();
            case 'monthly':
                return $query->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
            case 'yearly':
                return $query->selectRaw('YEAR(created_at) as year, SUM(amount) as total')
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();
            default:
                return collect();
        }
    }

    private function calculateConversionRate()
    {
        $totalVisitors = 1000; // You'd get this from analytics
        $totalEnrollments = Enrollment::count();
        
        return $totalVisitors > 0 ? round(($totalEnrollments / $totalVisitors) * 100, 2) : 0;
    }
}