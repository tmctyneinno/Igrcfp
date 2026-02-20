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

    public function courses(Request $request)
    {
        $query = Course::published()
            ->withCount('modules');
            // ->with(['instructor']); // Only load instructor relationship if it exists

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('short_description', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('tags', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by level
        if ($request->has('level') && !empty($request->level)) {
            $query->where('level', $request->level);
        }

        // Filter by price type
        if ($request->has('price_type') && !empty($request->price_type)) {
            if ($request->price_type === 'free') {
                $query->where('price', 0);
            } elseif ($request->price_type === 'paid') {
                $query->where('price', '>', 0);
            } elseif ($request->price_type === 'discounted') {
                $query->whereNotNull('discount_price')
                      ->whereColumn('discount_price', '<', 'price');
            }
        }

        // Filter by featured/popular
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        if ($request->has('popular') && $request->popular) {
            $query->where('is_popular', true);
        }

        // Sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSortFields = ['title', 'price', 'created_at', 'modules_count'];
        if (in_array($sortField, $allowedSortFields)) {
            if ($sortField === 'modules_count') {
                $query->orderBy('modules_count', $sortDirection);
            } else {
                $query->orderBy($sortField, $sortDirection);
            }
        } else {
            $query->orderBy('is_featured', 'desc')
                  ->orderBy('is_popular', 'desc')
                  ->orderBy('created_at', 'desc');
        }

        // Get filter options for dropdowns
        $levels = Course::published()->select('level')->distinct()->pluck('level');
        
        // Get unique formats if they exist
        $formats = Course::published()->whereNotNull('format')->select('format')->distinct()->pluck('format');

        // Paginate results
        $courses = $query->paginate(12)->withQueryString();

        // Transform courses for the frontend
        $courses->getCollection()->transform(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'short_description' => $course->short_description,
                'description' => $course->description,
                'banner_image' => $course->banner_image,
                'image_url' => $course->image_url,
                'level' => $course->level,
                'duration' => $course->duration,
                'price' => $course->price,
                'discount_price' => $course->discount_price,
                'modules_count' => $course->modules_count,
                'is_featured' => $course->is_featured,
                'is_popular' => $course->is_popular,
                'format' => $course->format,
                'tags' => $course->tags,
                'instructor' => $course->instructor ? [
                    'name' => $course->instructor->name,
                    'avatar' => $course->instructor->avatar
                ] : null,
                'created_at' => $course->created_at->format('M d, Y')
            ];
        });

        return Inertia::render('Courses/Index', [
            'filters' => [
                'search' => $request->search ?? '',
                'level' => $request->level ?? '',
                'price_type' => $request->price_type ?? '',
                'featured' => $request->featured ?? false,
                'popular' => $request->popular ?? false,
                'format' => $request->format ?? '',
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection,
            ],
            'courses' => $courses,
            'filterOptions' => [
                'levels' => $levels,
                'formats' => $formats,
                'priceTypes' => [
                    ['value' => '', 'label' => 'All Prices'],
                    ['value' => 'free', 'label' => 'Free'],
                    ['value' => 'paid', 'label' => 'Paid'],
                    ['value' => 'discounted', 'label' => 'Discounted'],
                ],
                'sortOptions' => [
                    ['value' => 'created_at_desc', 'label' => 'Newest First'],
                    ['value' => 'created_at_asc', 'label' => 'Oldest First'],
                    ['value' => 'price_asc', 'label' => 'Price: Low to High'],
                    ['value' => 'price_desc', 'label' => 'Price: High to Low'],
                    ['value' => 'title_asc', 'label' => 'Title: A to Z'],
                    ['value' => 'title_desc', 'label' => 'Title: Z to A'],
                    ['value' => 'modules_count_desc', 'label' => 'Most Modules'],
                ]
            ]
        ]);
    }

}