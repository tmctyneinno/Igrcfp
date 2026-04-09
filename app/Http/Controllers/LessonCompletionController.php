<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Enrollment;
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
            ->whereIn('status', ['enrolled', 'active', 'completed'])  // 👈 accept all valid statuses
            ->first();
        if (!$enrollment) {
            return back()->with('error', 'You are not enrolled in this course.');
        }
        
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
                    // 'materials' => $module->materials->map(function ($material) {
                    //     return [
                    //         'id' => $material->id,
                    //         'title' => $material->title,
                    //         'file_url' => $material->file_url,
                    //     ];
                    // })->toArray(),
                ];
            })->toArray();
    }
}