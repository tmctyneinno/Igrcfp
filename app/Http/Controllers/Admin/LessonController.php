<?php
// app/Http/Controllers/Admin/LessonController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Course $course, CourseModule $module)
    {
        $lessons = $module->lessons()->ordered()->get();
        
        return view('admin.courses.modules.lessons.index', compact('course', 'module', 'lessons'));
    }

    public function create( $course,  $module)
    {
        return view('admin.courses.modules.lessons.create', compact('course', 'module'));
    }

    public function store(Request $request, Course $course, CourseModule $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'video_embed_code' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer',
            'is_free' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $validated['module_id'] = $module->id;
        $validated['sort_order'] = $validated['sort_order'] ?? $module->lessons()->count() + 1;

        Lesson::create($validated);

        return redirect()->route('admin.courses.modules.lessons.index', [$course->id, $module->id])
            ->with('success', 'Lesson created successfully.');
    }

    public function edit(Course $course, CourseModule $module, Lesson $lesson)
    {
        return view('admin.courses.modules.lessons.edit', compact('course', 'module', 'lesson'));
    }

    public function update(Request $request, Course $course, CourseModule $module, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'video_embed_code' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer',
            'is_free' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $lesson->update($validated);

        return redirect()->route('admin.courses.modules.lessons.index', [$course->id, $module->id])
            ->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Course $course, CourseModule $module, Lesson $lesson)
    {
        $lesson->delete();

        return redirect()->route('admin.courses.modules.lessons.index', [$course->id, $module->id])
            ->with('success', 'Lesson deleted successfully.');
    }

    public function reorder(Request $request, Course $course, CourseModule $module)
    {
        $request->validate([
            'lessons' => 'required|array',
            'lessons.*.id' => 'required|exists:lessons,id',
            'lessons.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->lessons as $lessonData) {
            Lesson::where('id', $lessonData['id'])->update(['sort_order' => $lessonData['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}