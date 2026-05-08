<?php

namespace App\Http\Controllers;

use App\Models\CourseModule;
use App\Models\CourseModuleUser;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class ModuleReadingProgressController extends Controller
{
    public function store(Request $request, CourseModule $module)
    {
        $validated = $request->validate([
            'reading_progress' => ['required', 'integer', 'min:0', 'max:100'],
            'read' => ['sometimes', 'boolean'],
        ]);

        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $module->course_id)
            ->whereIn('status', ['enrolled', 'active', 'completed'])
            ->firstOrFail();

        $modules = $enrollment->course->modules()
            ->orderBy('module_number')
            ->orderBy('sort_order')
            ->get(['id']);

        $readModuleIds = CourseModuleUser::where('enrollment_id', $enrollment->id)
            ->where('user_id', $enrollment->user_id)
            ->where('read', true)
            ->pluck('course_module_id')
            ->all();

        $readModuleIds = array_flip($readModuleIds);
        $currentIndex = $modules->search(fn ($courseModule) => $courseModule->id === $module->id);

        if ($currentIndex === false) {
            abort(404);
        }

        $previousModulesRead = $modules
            ->take($currentIndex)
            ->every(fn ($courseModule) => isset($readModuleIds[$courseModule->id]));

        if (!$previousModulesRead) {
            return response()->json([
                'message' => 'Please read the previous module before completing this one.',
            ], 422);
        }

        $progress = CourseModuleUser::firstOrNew(
            [
                'user_id' => auth()->id(),
                'enrollment_id' => $enrollment->id,
                'course_module_id' => $module->id,
            ]
        );

        $requestedReadState = (bool) ($validated['read'] ?? false);
        $isRead = (bool) $progress->read || ($requestedReadState && (int) $validated['reading_progress'] >= 100);

        $progress->fill([
            'reading_progress' => $isRead
                ? 100
                : max((int) ($progress->reading_progress ?? 0), (int) $validated['reading_progress']),
            'read' => $isRead,
            'read_at' => $isRead ? ($progress->read_at ?? now()) : null,
            'last_viewed_at' => now(),
        ]);

        $progress->save();

        $moduleProgress = $this->calculateModuleReadingProgress($enrollment, $modules);
        $enrollment->update(['progress' => $moduleProgress]);

        return response()->json([
            'success' => true,
            'module' => [
                'id' => $module->id,
                'reading_progress' => $progress->reading_progress,
                'read' => $progress->read,
                'read_at' => optional($progress->read_at)->toIso8601String(),
            ],
            'enrollment' => [
                'id' => $enrollment->id,
                'progress' => $moduleProgress,
            ],
        ]);
    }

    private function calculateModuleReadingProgress(Enrollment $enrollment, $modules = null): int
    {
        $modules ??= $enrollment->course->modules()->get(['id']);
        $totalModules = $modules->count();

        if ($totalModules === 0) {
            return 0;
        }

        $readModuleIds = CourseModuleUser::where('enrollment_id', $enrollment->id)
            ->where('user_id', $enrollment->user_id)
            ->where('read', true)
            ->pluck('course_module_id')
            ->all();

        $readModuleIds = array_flip($readModuleIds);

        $completedModules = $modules->filter(function ($module) use ($readModuleIds) {
            return isset($readModuleIds[$module->id]);
        })->count();

        return (int) round(($completedModules / $totalModules) * 100);
    }
}
