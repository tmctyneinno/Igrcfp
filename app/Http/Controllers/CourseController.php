<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
  
    public function index()
    {
        // Fetch courses with instructor and reviews count
        $courses = Course::with('instructor')
            ->where('status', 'published') 
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'short_title' => $course->short_title,
                    'slug' => $course->slug,
                    'description' => $course->description,
                    'excerpt' => $course->excerpt,
                    'level' => $course->level,
                    'level_badge' => $course->level_badge,
                    'price' => $course->current_price,
                    'has_discount' => $course->has_discount,
                    'discount_price' => $course->discount_price,
                    'discount_percentage' => $course->discount_percentage,
                    'image_url' => $course->image ? Storage::url($course->image) : null,
                    'instructor' => $course->instructor
                        ? [
                            'id' => $course->instructor->id,
                            'name' => $course->instructor->name,
                        ]
                        : null,
                    'rating' => round($course->averageRating(), 1),
                    'reviews_count' => $course->reviewsCount(),
                    'enrollments_count' => $course->activeEnrollmentsCount,
                    'duration' => $course->duration,
                    'modules_count' => $course->modules_count,
                    'certification' => $course->certification,
                ];
            });
        // Return Inertia page (React)
        return Inertia::render('Welcome', [
            'courses' => $courses
        ]);
    }

    /**
     * Show a single course detail
     */
    public function show($slug)
    {
        $course = Course::with(['modules' => function($query) {
            $query->orderBy('module_number');
        }, 'materials' => function($query) {
            $query->orderBy('sort_order');
        }])->where('slug', $slug)->firstOrFail();

        // Format course data for Inertia
        $formattedCourse = [
            'id' => $course->id,
            'title' => $course->title,
            'slug' => $course->slug,
            'short_title' => $course->short_title,
            'short_description' => $course->short_description,
            'full_description' => $course->full_description,
            'image_url' => $course->image_url,
            'banner_image_url' => $course->banner_image_url,
            'video_type' => $course->video_type,
            'video_url' => $course->video_url,
            'video_embed_url' => $course->video_embed_url,
            'level' => $course->level,
            'format' => $course->format,
            'duration' => $course->duration,
            'total_modules' => $course->total_modules,
            'total_hours' => $course->total_hours,
            'certification_name' => $course->certification_name,
            'certifying_body' => $course->certifying_body,
            'price' => $course->price,
            'discount_price' => $course->discount_price,
            'discount_percentage' => $course->discount_percentage,
            'target_audience' => $course->target_audience,
            'learning_outcomes' => $course->learning_outcomes ? explode("\n", $course->learning_outcomes) : [],
            'prerequisites' => $course->prerequisites,
            'career_pathways' => $course->career_pathways,
            'assessment_structure' => $course->assessment_structure,
            'code_of_conduct' => $course->code_of_conduct,
            'programme_overview' => $course->programme_overview,
            'programme_architecture' => $course->programme_architecture,
            'meta_description' => $course->meta_description,
            'meta_keywords' => $course->meta_keywords,
            'status' => $course->status,
            'is_featured' => $course->is_featured,
            'is_popular' => $course->is_popular,
            'modules' => $course->modules->map(function($module) {
                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'description' => $module->description,
                    'module_number' => $module->module_number,
                    'duration' => $module->duration,
                    'sort_order' => $module->sort_order,
                ];
            }),
            'materials' => $course->materials->map(function($material) {
                return [
                    'id' => $material->id,
                    'title' => $material->title,
                    'description' => $material->description,
                    'file_url' => $material->file_url,
                    'file_type' => $material->file_type,
                    'sort_order' => $material->sort_order,
                ];
            }),
            'created_at' => $course->created_at->format('M d, Y'),
            'updated_at' => $course->updated_at->format('M d, Y'),
        ];
        $isEnrolled = auth()->check() 
        ? auth()->user()->courses()->where('course_id', $course->id)->exists()
        : false;
        \Log::info('Rendering course page', [
            'component_name' => 'Courses/Show', // Make sure this is exact
            'slug' => $slug,
            'controller' => __METHOD__,
        ]);
        return Inertia::render('Courses/Show', [
            'course' => $formattedCourse,
            'auth' => Auth::guard('web')->user(),
            'isEnrolled' => $isEnrolled,
        ]);
    }


}
