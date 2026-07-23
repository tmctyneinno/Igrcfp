<?php
// app/Http/Controllers/Admin/EnrollmentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->paginate(15);
        // Get summary data for cards
        $summary = [
            'total' => Enrollment::count(),
            'pending' => Enrollment::where('status', 'pending_payment')->count(),
            'enrolled' => Enrollment::where('status', 'enrolled')->count(),
            'completed' => Enrollment::where('status', 'completed')->count(),
            'cancelled' => Enrollment::where('status', 'cancelled')->count(),
        ];
            
        return view('admin.enrollments.index', compact('enrollments','summary'));
    }

    public function export(Request $request)
    {
        $query = Enrollment::with(['user', 'course', 'transaction']);

        // Apply filters if any
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('course', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->latest()->get();

        // Generate CSV
        $filename = 'enrollments-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'ID', 'Student Name', 'Student Email', 'Course Title', 
            'Enrollment Date', 'Amount', 'Payment Status', 'Enrollment Status', 'Progress'
        ];

        $callback = function() use ($enrollments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($enrollments as $enrollment) {
                fputcsv($file, [
                    $enrollment->id,
                    $enrollment->user->name ?? 'N/A',
                    $enrollment->user->email ?? 'N/A',
                    $enrollment->course->title ?? 'N/A',
                    $enrollment->created_at->format('Y-m-d H:i:s'),
                    $enrollment->amount ?? 0,
                    $enrollment->transaction->status ?? 'No Payment',
                    $enrollment->status, 
                    ($enrollment->progress ?? 0) . '%',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function pending()
    {
        $enrollments = Enrollment::with(['user', 'course', 'transaction'])
            ->where('status', 'pending_payment')
            ->latest()
            ->paginate(15);
            
        return view('admin.enrollments.pending', compact('enrollments'));
    }

    public function completed()
    {
        $enrollments = Enrollment::with(['user', 'course', 'transaction'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);
            
        return view('admin.enrollments.completed', compact('enrollments'));
    }

    public function cancelled(Request $request)
{
    $query = Enrollment::with(['user', 'course', 'transaction'])
        ->where('status', 'cancelled');

    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('user', function($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('course', function($courseQuery) use ($search) {
                $courseQuery->where('title', 'like', "%{$search}%");
            });
        });
    }

    // Apply refund status filter
    if ($request->filled('refund_status')) {
        if ($request->refund_status === 'refunded') {
            $query->whereHas('transaction', function($q) {
                $q->where('status', 'refunded');
            });
        } elseif ($request->refund_status === 'pending_refund') {
            $query->whereHas('transaction', function($q) {
                $q->where('status', 'pending_refund');
            });
        } elseif ($request->refund_status === 'no_refund') {
            $query->whereDoesntHave('transaction', function($q) {
                $q->whereIn('status', ['refunded', 'pending_refund']);
            });
        }
    }

    $enrollments = $query->latest()->paginate($request->per_page ?? 15);

    // Calculate summary data
    $totalRefunded = Enrollment::where('status', 'cancelled')
        ->whereHas('transaction', function($q) {
            $q->where('status', 'refunded');
        })
        ->sum('amount');

    $totalEnrollments = Enrollment::count();
    $cancelledCount = Enrollment::where('status', 'cancelled')->count();
    $cancellationRate = $totalEnrollments > 0 
        ? round(($cancelledCount / $totalEnrollments) * 100, 2) 
        : 0;

    return view('admin.enrollments.cancelled', compact(
        'enrollments', 
        'totalRefunded', 
        'cancellationRate'
    ));
}

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['user', 'course', 'transaction']);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $enrollment->update(['status' => $request->status]);
        
        return redirect()->back()->with('success', 'Enrollment status updated successfully.');
    }

    public function destroy(Enrollment $enrollment)
    {
        
        if ($enrollment->transaction) {
            // Example: If you want to delete the transaction record as well
            $enrollment->transaction->delete(); 
            
            // Or if you use a refund system:
            $enrollment->transaction->update(['status' => 'refunded']);
        }

        // Delete the enrollment
        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment deleted successfully.');
    }
}