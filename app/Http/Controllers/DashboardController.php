<?php

namespace App\Http\Controllers;

use App\Models\Course;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\Event;
use Inertia\Inertia;
 
class DashboardController extends Controller
{
    public function index()
    {
        // Pass enrollment redirect to frontend if it exists
        $enrollmentRedirect = session('enrollment_redirect');
        
        if ($enrollmentRedirect) {
            // Clear it from session after passing to frontend
            session()->forget('enrollment_redirect');
            
            return inertia('Dashboard/Index', [
                'enrollmentRedirect' => $enrollmentRedirect,
            ]);
        }
        
        // Get available courses
        $courses = Course::published()
            ->withCount('modules')
            ->orderBy('is_featured', 'desc')
            ->orderBy('is_popular', 'desc')
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'short_description' => $course->short_description,
                    'banner_image' => $course->banner_image, 
                    'image_url' => $course->image_url, 
                    'level' => $course->level,
                    'duration' => $course->duration,
                    'price' => $course->price,
                    'discount_price' => $course->discount_price,
                    'modules_count' => $course->modules_count,
                ];
            });
        
        // Get user's enrolled courses (you'll need to adjust based on your enrollment logic)
        $user = auth()->user();
        $enrolledCourses = $user->enrollments()
            ->with(['course' => function($query) {
                $query->withCount('modules');
            }]) 
            ->take(4)
            ->get()
            ->map(function ($enrollment) {
                $course = $enrollment->course;
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'short_description' => $course->short_description,
                    'banner_image' => $course->banner_image, 
                    'image_url' => $course->image_url, 
                    'level' => $course->level,
                    'duration' => $course->duration,
                    'progress' => $enrollment->progress, // Assuming you have progress tracking
                    'modules_count' => $course->modules_count,
                    'completed_modules' => $enrollment->completed_modules ?? 0,
                    'format' => $course->format,
                ];
            });
        
        $popularCourses = Course::published()
            ->where('is_popular', 1)
            // ->withCount('enrollments')
            ->select([
                'id',
                'title',
                'slug',
                'short_description',
                'banner_image',
                'image',
                'level',
                'format',
                'duration',
                'price',
                'discount_price',
                'is_featured',
                'rating'
            ])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->map(function ($course) {
            // Manually add the accessor values
            $course->image = $course->image? Storage::url($course->image) : null;
            $course->banner_image = $course->banner_image ? Storage::url($course->banner_image) : null;
            return $course;
        });
         // dd($popularCourses);
        return Inertia::render('Dashboard/Index', [
            'courses' => $courses,
            'enrolledCourses' => $enrolledCourses,
            'popularCourses' => $popularCourses,
        ]);
    }

    

}