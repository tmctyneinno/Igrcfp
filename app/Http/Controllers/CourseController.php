<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    /**
     * Display a list of courses for the frontend.
     */
    public function index()
    {
        // Fetch courses with instructor and reviews count
        $courses = Course::query()
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
                    'image_url' => $course->image_url,
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
        $course = Course::with(['instructor', 'modules', 'lessons'])->where('slug', $slug)->firstOrFail();

        return Inertia::render('Courses/Show', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'excerpt' => $course->excerpt,
                'level' => $course->level,
                'level_badge' => $course->level_badge,
                'price' => $course->current_price,
                'has_discount' => $course->has_discount,
                'discount_price' => $course->discount_price,
                'discount_percentage' => $course->discount_percentage,
                'image_url' => $course->image_url,
                'video_url' => $course->video_url,
                'video_embed_code' => $course->video_embed_code,
                'instructor' => $course->instructor
                    ? [
                        'id' => $course->instructor->id,
                        'name' => $course->instructor->name,
                    ]
                    : null,
                'rating' => round($course->averageRating(), 1),
                'reviews_count' => $course->reviewsCount(),
                'enrollments_count' => $course->activeEnrollmentsCount,
                'modules' => $course->modules()->get()->map(function($module) {
                    return [
                        'id' => $module->id,
                        'title' => $module->title,
                        'order' => $module->order,
                        'lessons_count' => $module->lessons()->count(),
                    ];
                }),
                'lessons' => $course->lessons()->get()->map(function($lesson) {
                    return [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'duration' => $lesson->duration,
                    ];
                }),
            ]
        ]);
    }
}
