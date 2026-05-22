<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Notification; // ✅ ADD THIS
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LessonCompletionController extends Controller
{
    public function markComplete(Request $request, Lesson $lesson)
    {
        \Log::info('markComplete hit', [
            'lesson_id' => $lesson->id,
            'user_id' => auth()->id(),
        ]);
        $user = auth()->user();
        
        // Check what enrollments exist for this user
        $allEnrollments = Enrollment::where('user_id', $user->id)
            ->where('course_id', $lesson->module->course_id)
            ->get(['id', 'status', 'course_id', 'user_id']);
        
        \Log::info('Enrollments found:', [
            'course_id' => $lesson->module->course_id,
            'enrollments' => $allEnrollments->toArray(),
        ]);
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $lesson->module->course_id)
            ->whereIn('status', ['enrolled', 'active', 'completed'])
            ->first();
            
        if (!$enrollment) {
            return back()->with('error', 'You are not enrolled in this course.');
        }
        
        // Check if lesson was already completed
        $wasAlreadyCompleted = DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->where('enrollment_id', $enrollment->id)
            ->where('completed', true)
            ->exists();
        
        // Mark lesson as complete
        DB::table('lesson_user')->updateOrInsert(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'enrollment_id' => $enrollment->id
            ],
            [
                'completed' => true,
                'completed_at' => now(),
                'time_spent' => $request->input('time_spent', 0),
                'auto_completed' => $request->input('auto_completed', false),
                'scroll_progress' => $request->input('scroll_progress', 100),
                'attempts' => DB::raw('COALESCE(attempts, 0) + 1'),
                'last_viewed_at' => now(),
                'updated_at' => now()
            ]
        );
        
        // Verify it saved
        $saved = DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->where('enrollment_id', $enrollment->id)
            ->first();
        
        \Log::info('Lesson completion saved:', [
            'lesson_id' => $lesson->id,
            'user_id' => $user->id,
            'enrollment_id' => $enrollment->id,
            'completed' => $saved?->completed,
        ]);
        
        // Update enrollment progress
        $progress = $enrollment->updateProgress();
        
        // ✅ Create notification for module completion if this was the last lesson
        if (!$wasAlreadyCompleted) {
            $this->checkAndNotifyModuleCompleted($user, $lesson->module, $enrollment);
        }
        
        // ✅ Check if all course lessons are completed
        $this->checkAndNotifyAllLessonsCompleted($user, $enrollment);
        
        // Get fresh modules data
        $modules = $this->getModulesWithCompletionStatus($enrollment);
        $freshEnrollment = $enrollment->fresh();
        
        // Return with both flash AND direct props
        return redirect()->back()->with([
            'success' => 'Lesson completed! Progress: ' . $progress . '%',
            'modules' => $modules,
            'enrollment' => [
                'id' => $freshEnrollment->id,
                'progress' => $freshEnrollment->progress,
                'status' => $freshEnrollment->status,
            ],
        ]);
    }
    
    /**
     * ✅ Check and notify when a module is fully completed
     */
    private function checkAndNotifyModuleCompleted($user, $module, $enrollment)
    {
        // Get all lessons in this module
        $totalLessons = $module->lessons()->count();
        
        // Get completed lessons for this module
        $completedLessons = DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->where('enrollment_id', $enrollment->id)
            ->whereIn('lesson_id', $module->lessons()->pluck('id'))
            ->where('completed', true)
            ->count();
        
        // Check if module is now fully completed
        if ($totalLessons > 0 && $completedLessons === $totalLessons) {
            // Check if notification already sent
            $existingNotification = Notification::where('user_id', $user->id)
                ->where('type', 'module_completed')
                ->where('data->module_id', $module->id)
                ->exists();
            
            if (!$existingNotification) {
                // Get quiz status for this module
                $quiz = \App\Models\Assessment::where('module_id', $module->id)
                    ->where('assessment_level', 'quiz')
                    ->where('status', 'active')
                    ->first();
                
                $message = $quiz 
                    ? "You've completed all lessons in Module {$module->module_number}: {$module->title}. You can now take the module quiz!"
                    : "You've completed all lessons in Module {$module->module_number}: {$module->title}. Great job!";
                
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'module_completed',
                    'title' => "📚 Module {$module->module_number} Completed!",
                    'message' => $message,
                    'data' => [
                        'course_id' => $module->course_id,
                        'module_id' => $module->id,
                        'module_number' => $module->module_number,
                        'module_title' => $module->title,
                        'has_quiz' => !is_null($quiz),
                        'quiz_id' => $quiz?->id,
                    ],
                ]);
            }
        }
    }
    
    /**
     * ✅ Check and notify when all course lessons are completed
     */
    private function checkAndNotifyAllLessonsCompleted($user, $enrollment)
    {
        $course = $enrollment->course;
        
        // Get total lessons in course
        $totalLessons = $course->modules()
            ->withCount('lessons')
            ->get()
            ->sum('lessons_count');
        
        // Get completed lessons
        $completedLessons = DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->where('enrollment_id', $enrollment->id)
            ->where('completed', true)
            ->count();
        
        // Check if all lessons are completed
        if ($totalLessons > 0 && $completedLessons === $totalLessons) {
            $existingNotification = Notification::where('user_id', $user->id)
                ->where('type', 'all_lessons_completed')
                ->where('data->course_id', $course->id)
                ->exists();
            
            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'all_lessons_completed',
                    'title' => '🎯 All Lessons Completed!',
                    'message' => "You've completed all lessons for '{$course->title}'. You're now ready for the assessments!",
                    'data' => [
                        'course_id' => $course->id,
                        'course_slug' => $course->slug,
                        'course_title' => $course->title,
                        'total_lessons' => $totalLessons,
                        'completed_lessons' => $completedLessons,
                    ],
                ]);
            }
        }
    }
    
    /**
     * ✅ Create notification when significant progress is made
     */
    private function notifyProgressMilestone($user, $enrollment, $progress)
    {
        $milestones = [25, 50, 75, 100];
        
        // Check if we just crossed a milestone
        $previousProgress = $enrollment->getOriginal('progress') ?? 0;
        
        foreach ($milestones as $milestone) {
            if ($previousProgress < $milestone && $progress >= $milestone) {
                $existingNotification = Notification::where('user_id', $user->id)
                    ->where('type', 'progress_milestone')
                    ->where('data->course_id', $enrollment->course_id)
                    ->where('data->milestone', $milestone)
                    ->exists();
                
                if (!$existingNotification) {
                    $course = $enrollment->course;
                    
                    $messages = [
                        25 => "You're a quarter of the way through '{$course->title}'! Keep up the great work! 💪",
                        50 => "Halfway there! You've completed 50% of '{$course->title}'. You're making excellent progress! 🎯",
                        75 => "Almost there! You've completed 75% of '{$course->title}'. The finish line is in sight! 🏁",
                        100 => "Incredible! You've completed all lessons in '{$course->title}'! Time for the final assessments! 🎉",
                    ];
                    
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'progress_milestone',
                        'title' => "📈 {$milestone}% Complete!",
                        'message' => $messages[$milestone] ?? "You've reached {$milestone}% completion!",
                        'data' => [
                            'course_id' => $course->id,
                            'course_slug' => $course->slug,
                            'course_title' => $course->title,
                            'progress' => $progress,
                            'milestone' => $milestone,
                        ],
                    ]);
                }
                break;
            }
        }
    }
    
    private function getModulesWithCompletionStatus($enrollment)
    {
        return $enrollment->course->modules()
            ->with(['lessons' => function($query) use ($enrollment) {
                $query->withCompletionStatus($enrollment->user_id, $enrollment->id);
            }])
            ->orderBy('module_number')
            ->get()
            ->map(function ($module) {
                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'module_number' => $module->module_number,
                    'learning_objectives' => $module->learning_objectives,
                    'full_content' => $module->full_content,
                    'estimated_hours' => $module->estimated_hours,
                    'lessons' => $module->lessons->map(function ($lesson) {
                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'description' => $lesson->description,
                            'duration' => $lesson->duration,
                            'lesson_type' => $lesson->lesson_type ?? 'reading',
                            'content' => $lesson->content,
                            'completed' => (bool) ($lesson->completed ?? false),
                        ];
                    })->toArray(),
                ];
            })->toArray();
    }
}