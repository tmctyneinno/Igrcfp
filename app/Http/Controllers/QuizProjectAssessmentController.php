<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Assessment;
use App\Models\Enrollment;
use App\Models\AssessmentSubmission;
use App\Models\AssessmentAttempt;
use App\Models\Notification; // ✅ ADD THIS
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Inertia\Inertia;
 
class QuizProjectAssessmentController extends Controller
{

    /**
     * Redirect to the final project assessment
     */
    public function projectAssessment(Course $course)
    {
        $user = auth()->user();
        
        // Verify enrollment
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['enrolled', 'active', 'completed'])
            ->first();
        
        if (!$enrollment) {
            return redirect()->route('dashboard.courses.show', $course->slug)
                ->with('error', 'You are not enrolled in this course.');
        }
        
        // Find the diploma/project assessment for this course
        $assessment = Assessment::where('course_id', $course->id)
            ->whereIn('assessment_level', ['diploma', 'project'])
            ->where('status', 'active')
            ->first();
        
        if (!$assessment) {
            // Check if there's a final exam instead
            $assessment = Assessment::where('course_id', $course->id)
                ->where('assessment_level', 'final_exam')
                ->where('status', 'active')
                ->first();
        }
        
        if (!$assessment) {
            return redirect()->route('dashboard.courses.show', $course->slug)
                ->with('error', 'No final assessment is available for this course yet.');
        }
        
        // If it's a project/diploma assessment, show the project submission page
        if (in_array($assessment->assessment_level, ['diploma', 'project'])) {
            return $this->showProjectPage($course, $assessment, $enrollment);
        }
        
        // Otherwise, redirect to the regular quiz take page
        return redirect()->route('dashboard.quiz.take', [
            'course' => $course->slug,
            'assessment' => $assessment->id,
            'attempt' => [
                'id' => $attempt->id,
                'status' => $attempt->status, // IMPORTANT: Pass status (e.g., 'completed', 'in_progress')
                'score' => $attempt->score,   // IMPORTANT: Pass score so Part B can check if >= 50%
                'answers' => json_decode($attempt->answers, true) ?? [],
            ],
        ]);
    } 

    /**
     * Show the project submission page
     */
    private function showProjectPage(Course $course, Assessment $assessment, Enrollment $enrollment)
    {
        $user = auth()->user();
        
        // Check if user already has a submission
        $existingSubmission = AssessmentSubmission::where('assessment_id', $assessment->id)
            ->where('user_id', $user->id)
            ->where('enrollment_id', $enrollment->id)
            ->first();
        
        // Get project details
        $projectDetails = [
            'id' => $assessment->id,
            'title' => $assessment->title,
            'description' => $assessment->description,
            'project_brief' => $assessment->project_brief,
            'deliverables' => $assessment->deliverables,
            'instructions' => $assessment->instructions,
            'total_marks' => $assessment->total_marks,
            'passing_score' => $assessment->passing_score,
            'due_date' => $assessment->due_date ? $assessment->due_date->format('M d, Y H:i') : null,
            'is_overdue' => $assessment->is_overdue,
            'file_url' => $assessment->file_url,
            'file_name' => $assessment->file_name,
            'settings' => $assessment->settings,
        ];
        
        return Inertia::render('Dashboard/Project/Submit', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
            ],
            'assessment' => $projectDetails,
            'enrollment' => [
                'id' => $enrollment->id,
                'progress' => $enrollment->progress,
            ],
            'existingSubmission' => $existingSubmission ? [
                'id' => $existingSubmission->id,
                'submitted_at' => $existingSubmission->submitted_at ? $existingSubmission->submitted_at->format('M d, Y H:i') : null,
                'status' => $existingSubmission->status,
                'score' => $existingSubmission->score,
                'percentage' => $existingSubmission->percentage,
                'passed' => $existingSubmission->passed,
                'feedback' => $existingSubmission->feedback,
                'file_name' => $existingSubmission->submission_file_name,
                'file_url' => $existingSubmission->submission_file_url,
                'graded_at' => $existingSubmission->graded_at ? $existingSubmission->graded_at->format('M d, Y H:i') : null,
            ] : null,
        ]);
    }

    /**
     * Submit project assessment
     */
    public function submitProject(Request $request, Course $course, Assessment $assessment)
    {
        $user = auth()->user();
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        
        if (!$enrollment) {
            return back()->with('error', 'You are not enrolled in this course.');
        }
        
        // Check if past due date and late submissions not allowed
        if ($assessment->is_overdue && !$assessment->allow_late_submissions) {
            return back()->with('error', 'The submission deadline has passed.');
        }
        
        $request->validate([
            'submission_file' => 'required|file|max:' . ($assessment->settings['max_file_size'] ?? 50) * 1024,
            'submission_notes' => 'nullable|string|max:5000',
        ]);
        
        // Handle file upload
        $file = $request->file('submission_file');
        $filename = time() . '_' . Str::slug($user->name) . '_' . Str::slug($assessment->title) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('submissions/projects/' . $course->id, $filename, 'public');
        
        // Create or update submission
        $submission = AssessmentSubmission::where('assessment_id', $assessment->id)
            ->where('user_id', $user->id)
            ->where('enrollment_id', $enrollment->id)
            ->first();
        
        $isNew = !$submission;
        
        if ($isNew) {
            $attemptCount = AssessmentSubmission::where('assessment_id', $assessment->id)
                ->where('user_id', $user->id)
                ->count();
            
            $submission = new AssessmentSubmission();
            $submission->assessment_id = $assessment->id;
            $submission->user_id = $user->id;
            $submission->enrollment_id = $enrollment->id;
            $submission->attempt_number = $attemptCount + 1;
        }
        
        $submission->submitted_at = now();
        $submission->status = 'submitted';
        $submission->submission_file_path = $path;
        $submission->submission_file_name = $file->getClientOriginalName();
        $submission->submission_file_size = $file->getSize();
        $submission->answers = ['notes' => $request->submission_notes];
        $submission->ip_address = $request->ip();
        $submission->user_agent = $request->userAgent();
        
        if ($assessment->due_date && now() > $assessment->due_date) {
            $submission->status = 'late';
        }
        
        $submission->save();
        
        // Update assessment statistics
        $assessment->calculateStatistics();
        
        // ✅ CREATE NOTIFICATION FOR PROJECT SUBMISSION
        $this->createProjectSubmissionNotification($user, $course, $assessment, $submission, $isNew);
        
        return redirect()->route('dashboard.quiz.project-assessment', $course->slug)
            ->with('success', 'Your project has been submitted successfully!');
    }

    /**
     * ✅ Create notification for project submission
     */
    private function createProjectSubmissionNotification($user, $course, $assessment, $submission, $isNew)
    {
        if ($isNew) {
            // First time submission
            Notification::create([
                'user_id' => $user->id,
                'type' => 'project_submitted',
                'title' => '📤 Project Submitted!',
                'message' => "Your project '{$assessment->title}' for '{$course->title}' has been submitted successfully and is pending review.",
                'data' => [
                    'course_slug' => $course->slug,
                    'assessment_id' => $assessment->id,
                    'submission_id' => $submission->id,
                    'submitted_at' => $submission->submitted_at->format('M d, Y H:i'),
                ],
            ]);
        } else {
            // Resubmission
            Notification::create([
                'user_id' => $user->id,
                'type' => 'project_resubmitted',
                'title' => '🔄 Project Resubmitted',
                'message' => "Your updated project '{$assessment->title}' has been submitted and is pending review.",
                'data' => [
                    'course_slug' => $course->slug,
                    'assessment_id' => $assessment->id,
                    'submission_id' => $submission->id,
                    'attempt_number' => $submission->attempt_number,
                ],
            ]);
        }
        
        // ✅ Also notify admins (optional)
        $this->notifyAdminsOfSubmission($user, $course, $assessment, $submission);
    }
    
    /**
     * ✅ Notify admins of new submission
     */
    private function notifyAdminsOfSubmission($user, $course, $assessment, $submission)
    {
        // Get admin users
        $admins = \App\Models\User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'admin_project_submitted',
                'title' => '📋 New Project Submission',
                'message' => "{$user->name} has submitted '{$assessment->title}' for '{$course->title}'.",
                'data' => [
                    'course_slug' => $course->slug,
                    'assessment_id' => $assessment->id,
                    'submission_id' => $submission->id,
                    'student_name' => $user->name,
                    'student_id' => $user->id,
                ],
            ]);
        }
    }

    /**
     * ✅ Create notification when project is graded (called from admin)
     */
    public function notifyProjectGraded($submissionId)
    {
        $submission = AssessmentSubmission::with(['user', 'assessment.course'])->find($submissionId);
        
        if (!$submission) {
            return;
        }
        
        $user = $submission->user;
        $assessment = $submission->assessment;
        $course = $assessment->course;
        
        if ($submission->passed) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'project_passed',
                'title' => '🎉 Project Passed!',
                'message' => "Congratulations! You passed '{$assessment->title}' with a score of {$submission->percentage}%. Your certificate is now available!",
                'data' => [
                    'course_slug' => $course->slug,
                    'assessment_id' => $assessment->id,
                    'submission_id' => $submission->id,
                    'score' => $submission->percentage,
                    'passed' => true,
                ],
            ]);
            
            // ✅ Also create course completed notification if this was the final requirement
            $this->checkAndNotifyCourseCompleted($user, $course);
        } else {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'project_graded',
                'title' => '📊 Project Graded',
                'message' => "Your project '{$assessment->title}' has been graded. Score: {$submission->percentage}%. You can resubmit to improve your score.",
                'data' => [
                    'course_slug' => $course->slug,
                    'assessment_id' => $assessment->id,
                    'submission_id' => $submission->id,
                    'score' => $submission->percentage,
                    'passed' => false,
                    'passing_score' => $assessment->passing_score,
                    'feedback' => $submission->feedback,
                ],
            ]);
        }
    }
    
    /**
     * ✅ Check and notify when course is fully completed
     */
    private function checkAndNotifyCourseCompleted($user, $course)
    {
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        
        if ($enrollment && $enrollment->progress >= 100) {
            $existingNotification = Notification::where('user_id', $user->id)
                ->where('type', 'course_completed')
                ->where('data->course_slug', $course->slug)
                ->exists();
            
            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'course_completed',
                    'title' => '🏆 Course Completed!',
                    'message' => "Congratulations! You've successfully completed '{$course->title}'. Your certificate is ready!",
                    'data' => [
                        'course_slug' => $course->slug,
                        'course_title' => $course->title,
                        'enrollment_id' => $enrollment->id,
                    ],
                ]);
            }
        }
    }

    /**
     * ✅ Send reminder notification for pending project (can be called via scheduled task)
     */
    public function sendProjectReminders()
    {
        $pendingAssessments = Assessment::whereIn('assessment_level', ['diploma', 'project'])
            ->where('status', 'active')
            ->where('due_date', '>', now())
            ->where('due_date', '<', now()->addDays(3))
            ->with('course.enrollments.user')
            ->get();
        
        foreach ($pendingAssessments as $assessment) {
            foreach ($assessment->course->enrollments as $enrollment) {
                $user = $enrollment->user;
                
                // Check if user hasn't submitted yet
                $hasSubmitted = AssessmentSubmission::where('assessment_id', $assessment->id)
                    ->where('user_id', $user->id)
                    ->exists();
                
                if (!$hasSubmitted) {
                    $daysLeft = now()->diffInDays($assessment->due_date);
                    
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'assessment_due',
                        'title' => '⏰ Project Deadline Approaching',
                        'message' => "Your project '{$assessment->title}' is due in {$daysLeft} day(s). Submit soon to avoid penalties.",
                        'data' => [
                            'course_slug' => $assessment->course->slug,
                            'assessment_id' => $assessment->id,
                            'due_date' => $assessment->due_date->format('M d, Y H:i'),
                            'days_left' => $daysLeft,
                        ],
                    ]);
                }
            }
        }
    }

}