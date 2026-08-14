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
                    'banner_image' => $course->banner_image_url, // FIXED: Use the model accessor
                    'image_url' => $course->image_url,           // FIXED: Accessor already handles the URL
                    'level' => $course->level,
                    'duration' => $course->duration,
                    'price' => $course->price,
                    'discount_price' => $course->discount_price,
                    'modules_count' => $course->modules_count,
                    'is_scholarship_eligible' => (bool) ($course->is_scholarship_eligible ?? false), // Prevents null errors
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
        // FIXED: Load modules and their nested relationships. 
        // (Ensure CourseModule model has quizzes() and assessments() methods defined!)
        $course->load(['modules.quizzes', 'modules.assessments']);

        $courseData = [
            'id' => $course->id,
            'title' => $course->title,
            'slug' => $course->slug,
            'description' => $course->full_description,      // FIXED: Mapped to correct column
            'short_description' => $course->short_description,
            'banner_image' => $course->banner_image_url,     // FIXED: Use the model accessor
            'image_url' => $course->image_url,               // FIXED: Accessor already handles the URL
            'level' => $course->level,
            'duration' => $course->duration,
            'price' => $course->price,
            'discount_price' => $course->discount_price,
            'is_scholarship_eligible' => (bool) ($course->is_scholarship_eligible ?? false),
            'modules' => $course->modules->map(function ($module) {
                return [
                    'id' => $module->id,
                    'name' => $module->title,
                    'order' => $module->module_number,
                    'quizzes' => $module->quizzes ?? [],
                    'assessments' => $module->assessments ?? [],
                ];
            }),
        ];

        return response()->json([
            'success' => true,
            'data' => $courseData
        ]);
    }
}