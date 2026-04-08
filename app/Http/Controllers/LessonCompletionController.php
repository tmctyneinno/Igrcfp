<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonCompletionController extends Controller
{
    public function markComplete(Lesson $lesson)
    {
        $user = auth()->user(); 
        
        // Get the enrollment for this course
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $lesson->module->course_id)
            ->where('status', 'enrolled')
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
                'updated_at' => now()
            ]
        );
        
        // Update enrollment progress
        $progress = $enrollment->updateProgress();
        
        // Return JSON for AJAX requests or flash message for regular requests
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'progress' => $progress,
                'message' => 'Lesson marked as complete'
            ]);
        }
        
        return back()->with('success', 'Lesson completed! Your progress is now ' . $progress . '%');
    }
    
    public function markIncomplete(Lesson $lesson)
    {
        $user = auth()->user();
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $lesson->module->course_id)
            ->first();
        
        if (!$enrollment) {
            return back()->with('error', 'Enrollment not found.');
        }
        
        // Mark lesson as incomplete
        DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->where('enrollment_id', $enrollment->id)
            ->delete();
        
        // Update enrollment progress
        $progress = $enrollment->updateProgress();
        
        return back()->with('success', 'Lesson marked as incomplete. Progress: ' . $progress . '%');
    }
}