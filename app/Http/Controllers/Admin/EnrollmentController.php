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
            
        return view('admin.enrollments.index', compact('enrollments'));
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
        $enrollments = Enrollment::with(['user', 'course'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);
            
        return view('admin.enrollments.pending', compact('enrollments'));
    }

    public function completed()
    {
        $enrollments = Enrollment::with(['user', 'course'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);
            
        return view('admin.enrollments.completed', compact('enrollments'));
    }

    public function cancelled()
    {
        $enrollments = Enrollment::with(['user', 'course'])
            ->where('status', 'cancelled')
            ->latest()
            ->paginate(15);
            
        return view('admin.enrollments.cancelled', compact('enrollments'));
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
}