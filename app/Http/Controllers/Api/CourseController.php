<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of published courses.
     */
    public function index()
    {
        $courses = Course::published()
            ->withCount('modules')
            ->orderByDesc('is_featured')
            ->orderByDesc('is_popular')
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'short_description' => $course->short_description,
                    'banner_image' => $course->banner_image ? asset('storage/' . $course->banner_image) : null,
                    'image_url' => $course->image_url ? asset('storage/' . $course->image_url) : null,
                    'level' => $course->level,
                    'duration' => $course->duration,
                    'price' => $course->price,
                    'discount_price' => $course->discount_price,
                    'modules_count' => $course->modules_count,
                    // Use the individual course flag instead of category matching
                    'is_scholarship_eligible' => (bool) $course->is_scholarship_eligible,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    /**
     * Display detailed information for a specific course.
     */
    public function show(Course $course)
    {
        $course->load(['modules.quizzes', 'modules.assessments']);

        $courseData = [
            'id' => $course->id,
            'title' => $course->title,
            'slug' => $course->slug,
            'description' => $course->description,
            'short_description' => $course->short_description,
            'banner_image' => $course->banner_image ? asset('storage/' . $course->banner_image) : null,
            'image_url' => $course->image_url ? asset('storage/' . $course->image_url) : null,
            'level' => $course->level,
            'duration' => $course->duration,
            'price' => $course->price,
            'discount_price' => $course->discount_price,
            'is_scholarship_eligible' => (bool) $course->is_scholarship_eligible,
            'modules' => $course->modules->map(function ($module) {
                return [
                    'id' => $module->id,
                    'name' => $module->name,
                    'order' => $module->order,
                    'quizzes' => $module->quizzes,
                    'assessments' => $module->assessments,
                ];
            }),
        ];

        return response()->json([
            'success' => true,
            'data' => $courseData
        ]);
    }
}