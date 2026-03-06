<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseCategoryController extends Controller
{
    public function index()
    {
        $categories = CourseCategory::withCount('courses')
                       ->orderBy('sort_order')
                       ->orderBy('name')
                       ->get();
        
        return view('admin.courses.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = CourseCategory::withCount('courses')
                       ->orderBy('sort_order')
                       ->orderBy('name')
                       ->get();
        return view('admin.courses.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:course_categories',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        CourseCategory::create($validated);

        return redirect()->route('admin.course-categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit(CourseCategory $courseCategory)
    {
        return view('admin.course-categories.form', ['category' => $courseCategory]);
    }

    public function update(Request $request, CourseCategory $courseCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:course_categories,name,' . $courseCategory->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        $courseCategory->update($validated);

        return redirect()->route('admin.course-categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(CourseCategory $courseCategory)
    {
        // Check if category has courses
        if ($courseCategory->courses()->count() > 0) {
            return back()->with('error', 'Cannot delete category with associated courses');
        }

        $courseCategory->delete();

        return redirect()->route('admin.course-categories.index')
            ->with('success', 'Category deleted successfully');
    }
}