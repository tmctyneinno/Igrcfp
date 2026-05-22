<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Article;
use App\Models\Assessment;
use App\Models\Blog;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\MembershipTier;
use App\Models\MentorApplication;
use App\Models\MentorProfile;
use App\Models\ScholarshipApplication;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{ 
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $stats = [
            'admin_name' => $admin->name,
            'total_admins' => Admin::count(),
            'total_users' => User::count(),
            'total_blogs' => Blog::count() ?? 0,
            'total_courses' => Course::count(),
            'total_events' => Event::count() ?? 0,
            'total_articles' => Article::count(),
            'total_assessments' => Assessment::count(),
            'total_enrollments' => Enrollment::count(),
            'completed_enrollments' => Enrollment::where('status', 'completed')->count(),
            'pending_enrollments' => Enrollment::where('status', 'pending_payment')->count(),
            'cancelled_enrollments' => Enrollment::where('status', 'cancelled')->count(),
            'generated_certificates' => Enrollment::where('certificate_generated', true)->count(),
            'pending_certificates' => Enrollment::where('status', 'completed')
                ->where('certificate_generated', false)
                ->count(),
            'total_transactions' => Transaction::count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'failed_transactions' => Transaction::where('status', 'failed')->count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
            'membership_tiers' => MembershipTier::count(),
            'membership_plans' => MembershipPlan::count(),
            'active_memberships' => Membership::active()->count(),
            'pending_memberships' => Membership::where('status', 'pending_approval')->count(),
            'total_mentors' => MentorProfile::count(),
            'active_mentors' => MentorProfile::where('is_active', true)->count(),
            'pending_mentor_applications' => MentorApplication::where('status', 'pending')->count(),
            'total_scholarships' => ScholarshipApplication::count(),
            'pending_scholarships' => ScholarshipApplication::where('status', 'pending')->count(),
            'under_review_scholarships' => ScholarshipApplication::where('status', 'under_review')->count(),
            'accepted_scholarships' => ScholarshipApplication::where('status', 'accepted')->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentEnrollments = Enrollment::with(['user', 'course'])->latest()->take(5)->get();
        $recentTransactions = Transaction::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentEnrollments',
            'recentTransactions'
        ));
    }

    public function users()
    {
        $users = User::with(['courses'])->latest()->paginate(20);
        
        return view('admin.users.index', compact('stats'));
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
