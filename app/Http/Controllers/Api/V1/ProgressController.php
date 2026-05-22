<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Http\Request;

class ProgressController extends ApiController
{
    public function completeLesson(Lesson $lesson, Request $request)
    {
        $courseId = $lesson->module?->course_id;
        $enrollment = $this->resolveEnrollment($request, $courseId);

        if (! $enrollment) {
            return $this->errorResponse('Enrollment not found for external user', status: 404);
        }

        $enrollment->update([
            'last_activity_at' => now(),
            'progress_percentage' => min(100, (float) $enrollment->progress_percentage + 1),
            'progress' => min(100, (int) $enrollment->progress + 1),
        ]);

        return $this->successResponse(['lesson_id' => $lesson->id, 'completed' => true], 'Lesson marked as complete');
    }

    public function trackLessonTime(Lesson $lesson, Request $request)
    {
        $validated = $request->validate([
            'seconds_spent' => ['required', 'integer', 'min:1', 'max:14400'],
            'watch_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'position_seconds' => ['nullable', 'integer', 'min:0'],
        ]);

        $courseId = $lesson->module?->course_id;
        $enrollment = $this->resolveEnrollment($request, $courseId);

        if (! $enrollment) {
            return $this->errorResponse('Enrollment not found for external user', status: 404);
        }

        $enrollment->update(['last_activity_at' => now()]);

        return $this->successResponse(['lesson_id' => $lesson->id] + $validated, 'Lesson time tracked successfully');
    }

    public function completeModule(CourseModule $module, Request $request)
    {
        $enrollment = $this->resolveEnrollment($request, $module->course_id);

        if (! $enrollment) {
            return $this->errorResponse('Enrollment not found for external user', status: 404);
        }

        $enrollment->update([
            'last_activity_at' => now(),
            'progress_percentage' => min(100, (float) $enrollment->progress_percentage + 5),
            'progress' => min(100, (int) $enrollment->progress + 5),
        ]);

        return $this->successResponse(['module_id' => $module->id, 'completed' => true], 'Module marked as complete');
    }

    public function courseProgress(Course $course, Request $request)
    {
        $enrollment = $this->resolveEnrollment($request, $course->id);

        if (! $enrollment) {
            return $this->errorResponse('Enrollment not found for external user', status: 404);
        }

        return $this->successResponse([
            'course_id' => $course->id,
            'completion_percentage' => (float) $enrollment->progress_percentage,
            'status' => $enrollment->status,
            'last_activity_at' => optional($enrollment->last_activity_at)->toISOString(),
        ]);
    }

    public function userProgress(string $externalUserId, Request $request)
    {
        $headerExternalUserId = (string) $request->attributes->get('external_user_id');
        $client = (string) $request->attributes->get('external_client');
        if ($externalUserId !== $headerExternalUserId) {
            return $this->errorResponse('External user mismatch', status: 403);
        }

        $items = Enrollment::query()
            ->where('external_user_id', $externalUserId)
            ->where('client', $client)
            ->get(['course_id', 'status', 'progress_percentage', 'last_activity_at'])
            ->map(fn (Enrollment $enrollment) => [
                'course_id' => $enrollment->course_id,
                'status' => $enrollment->status,
                'progress_percentage' => (float) $enrollment->progress_percentage,
                'last_activity_at' => optional($enrollment->last_activity_at)->toISOString(),
            ]);

        return $this->successResponse(['external_user_id' => $externalUserId, 'items' => $items]);
    }

    public function resume(Course $course, Request $request)
    {
        $enrollment = $this->resolveEnrollment($request, $course->id);
        if (! $enrollment) {
            return $this->errorResponse('Enrollment not found for external user', status: 404);
        }

        return $this->successResponse([
            'course_id' => $course->id,
            'active_module_id' => null,
            'active_lesson_id' => null,
            'completion_percentage' => (float) $enrollment->progress_percentage,
            'last_activity_at' => optional($enrollment->last_activity_at)->toISOString(),
        ]);
    }

    private function resolveEnrollment(Request $request, ?int $courseId): ?Enrollment
    {
        if (! $courseId) {
            return null;
        }

        return Enrollment::query()
            ->where('course_id', $courseId)
            ->where('external_user_id', (string) $request->attributes->get('external_user_id'))
            ->where('client', (string) $request->attributes->get('external_client'))
            ->first();
    }
}
