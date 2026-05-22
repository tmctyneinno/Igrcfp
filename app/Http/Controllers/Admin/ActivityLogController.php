<?php
// app/Http/Controllers/Admin/ActivityLogController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Services\ActivityLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Restrict to Super Admin and Origin Admin only
            $admin = Auth::guard('admin')->user();
            if (!$admin || !in_array($admin->role, [Admin::ROLE_SUPER_ADMIN, Admin::ROLE_ORIGIN_ADMIN])) {
                abort(403, 'Access denied. Only Super Admin and Origin Admin can access activity logs.');
            }
            return $next($request);
        });
    }

    /**
     * Display the main activity log page
     */
    public function index(Request $request)
    {
        $logs = $this->filterLogs($request)->paginate(25)->withQueryString();
        
        $statistics = $this->getStatistics();
        $modules = $this->getAvailableModules();

        return view('admin.activity-logs.index', compact('logs', 'statistics', 'modules'));
    }

    /**
     * Display a single log entry
     */
    public function show(ActivityLog $activityLog)
    {
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Filter logs based on request parameters
     */
    private function filterLogs(Request $request)
    {
        $query = ActivityLog::query()->with(['loggable', 'subject']);

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Quick date filters
        if ($request->filled('period')) {
            match ($request->period) {
                'today'       => $query->today(),
                'yesterday'   => $query->whereDate('created_at', today()->subDay()),
                'this_week'   => $query->thisWeek(),
                'last_week'   => $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]),
                'this_month'  => $query->thisMonth(),
                'last_month'  => $query->whereMonth('created_at', now()->subMonth()->month),
                default       => null,
            };
        }

        // Module filter
        if ($request->filled('module')) {
            $query->byModule($request->module);
        }

        // Event type filter
        if ($request->filled('event')) {
            $query->byEvent($request->event);
        }

        // User type filter
        if ($request->filled('user_type')) {
            match ($request->user_type) {
                'admin' => $query->where('loggable_type', Admin::class),
                'user'  => $query->where('loggable_type', User::class),
                'system' => $query->where('loggable_id', 0),
                default => null,
            };
        }

        // Search by performer or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Severity filter
        if ($request->filled('severity')) {
            $query->bySeverity($request->severity);
        }

        // Order
        $query->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc');

        return $query;
    }

    /**
     * Get activity log statistics for dashboard
     */
    private function getStatistics(): array
    {
        return [
            'total_today'      => ActivityLog::today()->count(),
            'total_this_week'  => ActivityLog::thisWeek()->count(),
            'total_this_month' => ActivityLog::thisMonth()->count(),
            'security_events'  => ActivityLog::security()->today()->count(),
            'errors_today'     => ActivityLog::today()->whereIn('severity', [
                ActivityLog::SEVERITY_ERROR, 
                ActivityLog::SEVERITY_CRITICAL
            ])->count(),
            'unique_users_today' => ActivityLog::today()
                ->selectRaw('COUNT(DISTINCT CONCAT(loggable_type, loggable_id)) as count')
                ->value('count'),
        ];
    }

    /**
     * Get available modules for filter dropdown
     */
    private function getAvailableModules(): array
    {
        return ActivityLog::select('module')
            ->distinct()
            ->whereNotNull('module')
            ->pluck('module')
            ->toArray();
    }

    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        $logs = $this->filterLogs($request)->get();

        // Log the export action
        ActivityLoggerService::log(
            ActivityLog::EVENT_EXPORT,
            'activity_logs',
            'Exported activity logs',
            count($logs) . ' log entries exported'
        );

        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->streamDownload(function() use ($logs) {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fputs($handle, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($handle, [
                'ID', 'Date/Time', 'Performer', 'Type', 'Module',
                'Event', 'Action', 'Description', 'Subject', 
                'IP Address', 'Severity'
            ]);

            // Data
            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->formatted_time,
                    $log->performer_name,
                    $log->loggable_type === Admin::class ? 'Admin' : 'User',
                    $log->module,
                    $log->event_display_name,
                    $log->action,
                    $log->description,
                    $log->subject_name,
                    $log->ip_address,
                    $log->severity,
                ]);
            }

            fclose($handle);
        }, $filename, $headers);
    }
}