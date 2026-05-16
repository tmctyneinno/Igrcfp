<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipApplication;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index(Request $request)
    {
        $query = ScholarshipApplication::with('post')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('nationality', 'LIKE', "%{$search}%");
            });
        }

        $applications = $query->paginate(20);

        return view('admin.scholarships.index', compact('applications'));
    }

    public function show(ScholarshipApplication $application)
    {
        $application->load('post');
        return view('admin.scholarships.show', compact('application'));
    }

    public function updateStatus(Request $request, ScholarshipApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,accepted,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Application status updated successfully!');
    }

    public function destroy(ScholarshipApplication $application)
    {
        $application->delete();
        return redirect()->route('admin.scholarships.index')
            ->with('success', 'Application deleted successfully!');
    }
}