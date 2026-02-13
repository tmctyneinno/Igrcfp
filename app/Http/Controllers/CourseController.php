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
        
        return Inertia::render('Courses/Show', [
            'course' => $formattedCourse,
            'auth' => Auth::guard('web')->user(),
            'isEnrolled' => $isEnrolled,
        ]); 
    }

    public function enroll(Request $request, Course $course)
    {
        // Check if course is published
        if (!$course->status) {
            return redirect()->route('courses.index')->with('error', 'Course not available.');
        }

        // Check if user is logged in
        if (!$request->user()) {
            // Store the intended course in session for redirect after login
            session(['intended_enrollment' => $course->slug]);
            
            // Redirect to login with a message
            return redirect()->route('login')->with('success', 'Please login to enroll in this course.');
        }

        return Inertia::render('Courses/Enroll', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'price' => $course->price,
                'discount_price' => $course->discount_price,
                'duration' => $course->duration,
                'level' => $course->level,
                'image_url' => $course->image_url,
            ]
        ]);
    }

    /**
     * Process the enrollment
     */
    public function processEnrollment(Request $request, Course $course)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:card,bank,paypal',
            'terms_accepted' => 'required|accepted',
        ]);

        // Check if user is already enrolled
        if ($request->user()) {
            $existingEnrollment = $course->enrollments()
                ->where('user_id', $request->user()->id)
                ->first();
                
            if ($existingEnrollment) {
                return redirect()->back()->with('error', 'You are already enrolled in this course.');
            }
        }

        // Calculate final price
        $finalPrice = $course->discount_price ?? $course->price;

        // Create enrollment record
        $enrollment = $course->enrollments()->create([
            'user_id' => $request->user()?->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'payment_method' => $validated['payment_method'],
            'amount' => $finalPrice,
            'status' => $finalPrice > 0 ? 'pending_payment' : 'enrolled',
            'enrollment_date' => now(),
        ]);

        // If free course, enroll directly
        if ($finalPrice == 0) {
            return redirect()->route('courses.show', $course->slug)
                ->with('success', 'You have successfully enrolled in the course!');
        }

        // Redirect to payment page for paid courses
        return redirect()->route('payment.process', [
            'enrollment' => $enrollment->id,
            'course' => $course->slug
        ]);
    }

    public function enrollmentSuccess(Course $course, Request $request)
    {
        $enrollment = $course->enrollments()
            ->where('id', $request->enrollment)
            ->firstOrFail();

        return Inertia::render('Courses/EnrollmentSuccess', [
            'course' => $course,
            'enrollment' => $enrollment
        ]);
    }
}
