<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ScholarshipCourseAssignedMail;
use App\Models\User;
use App\Models\Course;
use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Services\BrevoMailService; // Import the Service
use App\Mail\CourseEnrollmentRejectedMail;
use App\Models\Enrollment;
use App\Models\CourseModuleUser;
use Illuminate\Support\Facades\Log;
  
class UserManagementController extends Controller
{ 
    protected $brevoService;

    // Inject the Brevo Service via Constructor
    public function __construct(BrevoMailService $brevoService)
    {
        $this->brevoService = $brevoService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $scholarship = $request->get('scholarship'); // New filter
        $perPage = $request->get('per_page', 20);

        $users = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($status, function ($query) use ($status) {
                if ($status === 'active') {
                    $query->where('status', 'active');
                } elseif ($status === 'inactive') {
                    $query->where('status', '!=', 'active');
                }
            })
            ->when($scholarship !== null, function ($query) use ($scholarship) {
                $query->where('is_scholarship_applicant', $scholarship);
            })
            ->latest()
            ->paginate($perPage);

        return view('admin.users.index', compact('users', 'search', 'status', 'perPage'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'nullable|string|in:admin,learner,tutor',
            'status' => 'nullable|string|in:active,pending,suspended',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'learner',
            'status' => $request->status ?? 'active',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        // Load relationships
        $user->load([
            'enrollments.course',
            'assessmentSubmissions.assessment.course',
            'transactions.enrollment.course',
        ]);

        // Calculate Assessment Stage for each enrollment
        foreach ($user->enrollments as $enrollment) {
            $enrollment->assessment_stage = $this->getAssessmentStage($enrollment);
        }
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Determine the current assessment stage for an enrollment
     */
    private function getAssessmentStage($enrollment)
    {
        $userId = $enrollment->user_id;
        $courseId = $enrollment->course_id;

        // 1. Check for Quiz (Part A)
        $quiz = Assessment::where('course_id', $courseId)
            ->where('assessment_level', 'quiz')
            ->first();

        if (!$quiz) {
            return ['label' => 'No Assessments', 'class' => 'secondary'];
        }

        $quizSubmission = AssessmentSubmission::where('assessment_id', $quiz->id)
            ->where('user_id', $userId)
            ->latest()
            ->first();

        // If no quiz attempt yet
        if (!$quizSubmission) {
            return ['label' => 'Not Started', 'class' => 'secondary'];
        }

        // If quiz failed or in progress
        if (!$quizSubmission->passed) {
            return ['label' => 'Quiz (Retry)', 'class' => 'warning'];
        }

        // 2. Check for Essay/Project (Part B)
        $essayAssessment = Assessment::where('course_id', $courseId)
            ->whereIn('assessment_level', ['diploma', 'project', 'module_assessment'])
            ->whereHas('questions', function($q) {
                $q->where('question_type', 'essay');
            })
            ->first();

        if (!$essayAssessment) {
            return ['label' => 'Quiz Passed', 'class' => 'success'];
        }

        $essaySubmission = AssessmentSubmission::where('assessment_id', $essayAssessment->id)
            ->where('user_id', $userId)
            ->latest()
            ->first();

        if (!$essaySubmission) {
            return ['label' => 'Ready for Essay', 'class' => 'primary'];
        }

        if ($essaySubmission->status === 'submitted' || $essaySubmission->status === 'in_progress') {
            return ['label' => 'Essay Under Review', 'class' => 'info'];
        }

        if ($essaySubmission->status === 'graded') {
            return ['label' => 'Completed', 'class' => 'success'];
        }

        return ['label' => 'Quiz Passed', 'class' => 'success'];
    }

    public function enrollments(User $user)
    {
        $enrollments = $user->enrollments()
            ->with(['course', 'course.modules'])
            ->latest()
            ->paginate(15);
        
        return view('admin.users.enrollments', compact('user', 'enrollments'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'nullable|string|in:admin,learner,tutor',
            'status' => 'nullable|string|in:active,pending,suspended',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role ?? $user->role,
            'status' => $request->status ?? $user->status,
            'phone' => $request->phone,
            'country' => $request->country,
            'city' => $request->city,
            'bio' => $request->bio,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['status' => $user->status === 'active' ? 'suspended' : 'active']);
        $status = $user->status === 'active' ? 'activated' : 'deactivated';
        
        return back()->with('success', "User {$status} successfully.");
    }

    // NEW: Toggle Scholarship Status
    public function toggleScholarship(Request $request, User $user)
    {
        $isScholar = $request->has('is_scholarship_applicant');
        
        $user->update([
            'is_scholarship_applicant' => $isScholar
        ]);

        $message = $isScholar 
            ? "Scholarship access granted to {$user->name}." 
            : "Scholarship access revoked from {$user->name}.";

        return back()->with('success', $message);
    }

    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $userIds = $request->user_ids;

        if (!$userIds) {
            return back()->with('error', 'Please select at least one user.');
        }

        switch ($action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['status' => 'active']);
                $message = 'Selected users activated successfully.';
                break;
            case 'deactivate':
                User::whereIn('id', $userIds)->update(['status' => 'suspended']);
                $message = 'Selected users deactivated successfully.';
                break;
            case 'delete':
                User::whereIn('id', $userIds)->delete();
                $message = 'Selected users deleted successfully.';
                break;
            default:
                return back()->with('error', 'Invalid action.');
        }

        return back()->with('success', $message);
    }

 
    public function scholarshipCourses(User $user, Request $request)
    {
        $assignedCourseIds = $user->scholarshipCourses()->pluck('courses.id')->toArray();
        
        $search = $request->get('search');
        $categoryFilter = $request->get('category');

        // Define eligible categories for reference
        $eligibleCategories = [
            'IGRCFP Certificates',
            // 'Certified GRC & Financial Crime Specialist'
        ]; 
 
        $query = Course::published()
            ->whereIn('igrcfp_category', $eligibleCategories);

        // Filter by Search Term (Title or Category)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('igrcfp_category', 'like', "%{$search}%");
            });
        }

        // Filter by Specific Category
        if ($categoryFilter) {
            $query->where('igrcfp_category', $categoryFilter);
        }

        $availableCourses = $query->orderBy('title')->get();

        return view('admin.users.scholarship-courses', compact(
            'user', 
            'availableCourses', 
            'assignedCourseIds', 
            'eligibleCategories',
            'search',
            'categoryFilter'
        ));
    }

    public function updateScholarshipCourses(Request $request, User $user)
    {
        $request->validate([
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $newCourseIds = $request->input('course_ids', []);
        
        // Ensure consistent types for comparison
        $currentCourseIds = $user->scholarshipCourses()
            ->pluck('courses.id')
            ->map(fn($id) => (string)$id)
            ->toArray();
        $newCourseIdsString = array_map('strval', $newCourseIds);
        
        // ✅ Find courses being REMOVED (before sync)
        $removedCourseIds = array_diff($currentCourseIds, $newCourseIdsString);

        // Find newly added courses
        $newlyAssigned = array_diff($newCourseIdsString, $currentCourseIds);

        // Sync scholarship pivot with extra data
        $syncData = [];
        foreach ($newCourseIds as $courseId) {
            $syncData[$courseId] = ['assigned_by' => auth()->id()];
        }
        $user->scholarshipCourses()->sync($syncData);

        // ✅ CRITICAL: Clean up enrollments & progress for removed courses
        if (!empty($removedCourseIds)) {
            $removedIntIds = array_map('intval', $removedCourseIds);

            // Delete enrollments
            Enrollment::where('user_id', $user->id)
                ->whereIn('course_id', $removedIntIds)
                ->delete();

            // Clean up course_user pivot
            $user->courses()->detach($removedIntIds);

            // Clean up module/lesson progress
            CourseModuleUser::where('user_id', $user->id)
                ->whereHas('module', fn($q) => $q->whereIn('course_id', $removedIntIds))
                ->delete();
        }

        // Send emails ONLY for newly assigned courses
        $emailsSent = 0;
        foreach ($newlyAssigned as $courseId) {
            $course = Course::find($courseId);
            if ($course) {
                try {
                    $mailable = new ScholarshipCourseAssignedMail($user, $course);
                    // $this->brevoService->sendMailable(
                    //     $user->email, 
                    //     $mailable, 
                    //     'Scholarship Course Assigned: ' . $course->title
                    // ); 
                    $emailsSent++;
                } catch (\Exception $e) {
                    Log::error('Failed to send scholarship email via Brevo: ' . $e->getMessage());
                }
            }
        }

        $message = 'Scholarship courses updated successfully.';
        if ($emailsSent > 0) {
            $message .= " {$emailsSent} notification(s) sent.";
        }

        return back()->with('success', $message);
    }

    
    public function rejectEnrollment(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $user = $enrollment->user;
        $course = $enrollment->course;
        $reason = $request->input('reason', 'You were not assigned to this course via the scholarship program.');

        try {
            // 1. Delete the enrollment
            $enrollment->delete();

            // 2. Send Email Notification using Brevo Service
            $mailable = new CourseEnrollmentRejectedMail($user, $course, $reason);
            
            // $this->brevoService->sendMailable(
            //     $user->email, 
            //     $mailable, 
            //     'Enrollment Update: ' . $course->title
            // );

            return back()->with('success', "Student removed from course and notified.");

        } catch (\Exception $e) {
            Log::error('Failed to send rejection email via Brevo: ' . $e->getMessage());
            return back()->with('error', "Student removed, but failed to send notification email: " . $e->getMessage());
        }
    }

} 