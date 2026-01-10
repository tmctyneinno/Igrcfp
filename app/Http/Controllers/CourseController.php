<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::query()
            ->where('status', 'published')
            ->latest()
            ->take(6) // limit for homepage section
            ->get()
            ->map(fn ($course) => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'short_description' => $course->short_description,
                'level' => ucfirst($course->level),
                'price' => $course->current_price,
                'has_discount' => $course->has_discount,
                'discount_percentage' => $course->discount_percentage,
                'image' => $course->image_url,
                'instructor' => optional($course->instructor)->name ?? 'IGRCFP Faculty',
                'rating' => round($course->averageRating() ?? 4.8, 1),
            ]);

        return Inertia::render('Welcome', [
            'courses' => $courses,
        ]);
    }
}
