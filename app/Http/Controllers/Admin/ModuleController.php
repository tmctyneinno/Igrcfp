<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    /**
     * Show the form for creating a new module.
     */
    public function create(Course $course)
    {
        $nextModuleNumber = CourseModule::getNextModuleNumber($course->id);
        
        return view('admin.courses.modules.create', compact('course', 'nextModuleNumber'));
    }

    /**
     * Store a newly created module in storage.
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'nullable|string|max:20|unique:course_modules,code,NULL,id,course_id,' . $course->id,
            'module_number' => 'required|integer|min:1',
            'short_description' => 'required|string|max:500',
            'full_content' => 'required|string',
            'learning_objectives' => 'nullable|string',
            'key_concepts' => 'nullable|string',
            'topics_covered' => 'nullable|string',
            'case_study' => 'nullable|string',
            'exercise' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'estimated_hours' => 'required|integer|min:1|max:100',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        try {
            // Set course_id
            $validated['course_id'] = $course->id;
            
            // Set default values
            $validated['is_active'] = $request->has('is_active');
            $validated['sort_order'] = $request->sort_order ?? $validated['module_number'] * 10;

            // Create the module
            $module = CourseModule::create($validated);

            return redirect()->route('admin.courses.show', $course->slug)
                ->with('success', 'Module created successfully!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating module: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified module.
     */
    public function edit(Course $course, CourseModule $module)
    {
        // Ensure the module belongs to the course
        if ($module->course_id !== $course->id) {
            abort(404);
        }

        return view('admin.modules.edit', compact('course', 'module'));
    }

    /**
     * Update the specified module in storage.
     */
    public function update(Request $request, Course $course, CourseModule $module)
    {
        // Ensure the module belongs to the course
        if ($module->course_id !== $course->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:20|unique:course_modules,code,' . $module->id . ',id,course_id,' . $course->id,
            'module_number' => 'required|integer|min:1',
            'short_description' => 'required|string|max:500',
            'full_content' => 'required|string',
            'learning_objectives' => 'nullable|string',
            'key_concepts' => 'nullable|string',
            'topics_covered' => 'nullable|string',
            'case_study' => 'nullable|string',
            'exercise' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'estimated_hours' => 'required|integer|min:1|max:100',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        try {
            // Update the module
            $validated['is_active'] = $request->has('is_active');
            $module->update($validated);

            return redirect()->route('admin.courses.show', $course->id)
                ->with('success', 'Module updated successfully!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating module: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified module from storage.
     */
    public function destroy(Course $course, CourseModule $module)
    {
        // Ensure the module belongs to the course
        if ($module->course_id !== $course->id) {
            abort(404);
        }

        try {
            $module->delete();

            return redirect()->route('admin.courses.show', $course->id)
                ->with('success', 'Module deleted successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting module: ' . $e->getMessage());
        }
    }

    /**
     * Toggle module active status
     */
    public function toggleActive(Course $course, CourseModule $module)
    {
        // Ensure the module belongs to the course
        if ($module->course_id !== $course->id) {
            abort(404);
        }

        try {
            $module->update(['is_active' => !$module->is_active]);

            return back()->with('success', 'Module status updated!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating module status: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate a module
     */
    public function duplicate(Course $course, CourseModule $module)
    {
        // Ensure the module belongs to the course
        if ($module->course_id !== $course->id) {
            abort(404);
        }

        try {
            $newModule = $module->replicate();
            $newModule->code = $module->code . '-copy';
            $newModule->module_number = CourseModule::getNextModuleNumber($course->id);
            $newModule->title = $module->title . ' (Copy)';
            $newModule->save();

            return redirect()->route('admin.modules.edit', ['course' => $course->id, 'module' => $newModule->id])
                ->with('success', 'Module duplicated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error duplicating module: ' . $e->getMessage());
        }
    }

    /**
     * Reorder modules
     */
    public function reorder(Request $request, Course $course)
    {
        $request->validate([
            'modules' => 'required|array',
            'modules.*.id' => 'required|exists:course_modules,id,course_id,' . $course->id,
            'modules.*.position' => 'required|integer',
        ]);

        try {
            foreach ($request->modules as $moduleData) {
                CourseModule::where('id', $moduleData['id'])
                    ->where('course_id', $course->id)
                    ->update(['sort_order' => $moduleData['position']]);
            }

            return response()->json(['success' => true, 'message' => 'Modules reordered successfully!']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error reordering modules'], 500);
        }
    }
}