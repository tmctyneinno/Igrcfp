<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{ 
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_admins' => Admin::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'admin_name' => $admin->name,
        ];

        return inertia('Admin/Dashboard', [
            'stats' => $stats,
            'auth' => [ 
                'admin' => $admin
            ]
        ]);
    }

    public function users()
    {
        $users = User::with(['courses'])->latest()->paginate(20);
        
        return inertia('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function courses()
    {
        $courses = Course::with(['instructor', 'enrollments'])->latest()->paginate(20);
        
        return inertia('Admin/Courses/Index', [
            'courses' => $courses,
        ]);
    }

    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'category' => 'required|string',
            'instructor_id' => 'required|exists:users,id',
            'is_published' => 'boolean',
        ]);

        $course = Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    // Add other methods as needed...
}