<?php
// app/Http/Controllers/Admin/EnrollmentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->paginate(15);
            
        return view('admin.enrollments.index', compact('enrollments'));
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