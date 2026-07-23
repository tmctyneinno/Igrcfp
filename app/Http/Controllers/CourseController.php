<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
   
    public function index(Request $request)
    {
        $query = Course::with('instructor', 'category')
            ->where('status', 'published');

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $courses = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $enrolledCourseIds = [];
        if (auth()->check()) {
            $user = auth()->user();
            $enrolledCourseIds = array_unique(array_merge(
                $user->courses()->pluck('courses.id')->toArray(),
                $user->enrollments()->pluck('course_id')->toArray()
            ));
        }

        $courses->getCollection()->transform(function ($course) use ($enrolledCourseIds) {
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
                'is_enrolled' => in_array($course->id, $enrolledCourseIds),
            ];
        }); 

        return Inertia::render('Welcome', [
            'courses' => $courses,
            'categories' => CourseCategory::active()->ordered()->get(),
            'filters' => $request->all(),
            'filterOptions' => [
                'levels' => ['Beginner', 'Intermediate', 'Advanced', 'Expert'],
                'categories' => CourseCategory::all(),
                'priceTypes' => [
                    ['value' => 'free', 'label' => 'Free'],
                    ['value' => 'paid', 'label' => 'Paid'],
                    ['value' => 'discounted', 'label' => 'Discounted'],
                ],
                'sortOptions' => [
                    ['value' => 'title_asc', 'label' => 'Title: A to Z'],
                    ['value' => 'title_desc', 'label' => 'Title: Z to A'],
                    ['value' => 'price_asc', 'label' => 'Price: Low to High'],
                    ['value' => 'price_desc', 'label' => 'Price: High to Low'],
                    ['value' => 'created_at_desc', 'label' => 'Newest First'],
                    ['value' => 'created_at_asc', 'label' => 'Oldest First'],
                ],
            ],
        ]);
    }

    
    public function show($slug)
    {
        $course = Course::with(['modules', 'materials', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        $isEnrolled = auth()->check()
            ? auth()->user()->courses()->where('course_id', $course->id)->exists()
                || auth()->user()->enrollments()->where('course_id', $course->id)->exists()
            : false;

        // Check if course is eligible for scholarship
        $scholarshipCategories = [
            'IGRCFP Certificates',
            'Certified GRC & Financial Crime Specialist'
        ];
        $isScholarshipEligible = in_array($course->igrcfp_category, $scholarshipCategories);

        // Prepare User Data
        $userData = null;
        if (auth()->check()) {
            $user = auth()->user();
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_scholarship_applicant' => $user->is_scholarship_applicant, // <--- ADD THIS
            ];
        }

        // Format course data for Inertia
        $formattedCourse = [
            'id' => $course->id,
            'title' => $course->title,
            'category' => $course->category ? [
                'id' => $course->category->id,
                'name' => $course->category->name,
                'slug' => $course->category->slug,
                'icon' => $course->category->icon,
            ] : null,
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
            'is_enrolled' => $isEnrolled,
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
            'is_scholarship_eligible' => $isScholarshipEligible,
        ];
        
        return Inertia::render('Courses/Show', [
            'course' => $formattedCourse,
            'auth' => [
                'user' => $userData,
            ],
            'isEnrolled' => $isEnrolled,
        ]); 
    }
 

    
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

    public function byCategory(Request $request, $slug)
    {
        // Find the category by slug
        $category = CourseCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        // Start building the query
        $query = Course::with('category')
            ->published()
            ->where('category_id', $category->id);
        
        // Apply filters from request
        if ($request->has('level') && $request->level != '') {
            $query->where('level', $request->level);
        }
        
        if ($request->has('price_type') && $request->price_type != '') {
            if ($request->price_type === 'free') {
                $query->where('price', 0);
            } elseif ($request->price_type === 'paid') {
                $query->where('price', '>', 0);
            } elseif ($request->price_type === 'discounted') {
                $query->where('discount_price', '>', 0);
            }
        }
        
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }
        
        if ($request->boolean('popular')) {
            $query->where('is_popular', true);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                ->orWhere('short_description', 'LIKE', "%{$search}%")
                ->orWhere('full_description', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        // Get paginated results
        $courses = $query->paginate(20)->withQueryString();

        $enrolledCourseIds = [];
        if (auth()->check()) {
            $user = auth()->user();
            $enrolledCourseIds = array_unique(array_merge(
                $user->courses()->pluck('courses.id')->toArray(),
                $user->enrollments()->pluck('course_id')->toArray()
            ));
        }

        $courses->getCollection()->transform(function ($course) use ($enrolledCourseIds) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'short_description' => $course->short_description,
                'full_description' => $course->full_description,
                'banner_image' => $course->banner_image,
                'image_url' => $course->image_url,
                'level' => $course->level,
                'duration' => $course->duration,
                'price' => $course->price,
                'discount_price' => $course->discount_price,
                'discount_percentage' => $course->discount_percentage,
                'modules_count' => $course->modules_count,
                'is_featured' => $course->is_featured,
                'is_popular' => $course->is_popular,
                'category' => $course->category ? [
                    'id' => $course->category->id,
                    'name' => $course->category->name,
                    'slug' => $course->category->slug,
                ] : null,
                'is_enrolled' => in_array($course->id, $enrolledCourseIds),
            ];
        });

        // Get all categories for filter options
        $categories = CourseCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Courses/ByCategory', [ // Use the same view
            'courses' => $courses,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'icon' => $category->icon,
            ],
            'filters' => $request->all(),
            'filterOptions' => [
                'levels' => ['Beginner', 'Intermediate', 'Advanced', 'Expert'],
                'categories' => $categories,
                'priceTypes' => [
                    ['value' => 'free', 'label' => 'Free'],
                    ['value' => 'paid', 'label' => 'Paid'],
                    ['value' => 'discounted', 'label' => 'Discounted'],
                ],
                'sortOptions' => [
                    ['value' => 'title_asc', 'label' => 'Title: A to Z'],
                    ['value' => 'title_desc', 'label' => 'Title: Z to A'],
                    ['value' => 'price_asc', 'label' => 'Price: Low to High'],
                    ['value' => 'price_desc', 'label' => 'Price: High to Low'],
                    ['value' => 'created_at_desc', 'label' => 'Newest First'],
                    ['value' => 'created_at_asc', 'label' => 'Oldest First'],
                ],
            ],
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
            // If it's a POST request from AJAX, we can't easily redirect to login with Inertia 
            // without a full page visit, but standard redirect works for GET.
            session(['intended_enrollment' => $course->slug]);
            return redirect()->route('login', [
                'redirect' => route('courses.enroll', ['course' => $course->slug])
            ])->with('success', 'Please login to enroll in this course.');
        }

        // Check if user is already enrolled
        $existingEnrollment = Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->first();
            
        if ($existingEnrollment) {
            // For AJAX/Inertia requests, returning a redirect response usually triggers a page visit
            return redirect()->route('dashboard.courses.show', ['slug' => $course->slug])
                ->with('info', 'You are already enrolled in this course.');
        }

        $user = $request->user();

        // --- SCHOLARSHIP LOGIC START ---
        // Define eligible categories for scholarship
        $scholarshipCategories = [
            'IGRCFP Certificates',
            'Certified GRC & Financial Crime Specialist' // Added based on previous context
        ];
        
        $isCertificationCourse = in_array($course->igrcfp_category, $scholarshipCategories);

        // Check if user is a scholarship applicant AND course is eligible
        if ($user->is_scholarship_applicant && $isCertificationCourse) {
            // Create enrollment directly without payment/cart
            $enrollment = $course->enrollments()->create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'payment_method' => 'scholarship',
                'amount' => 0,
                'status' => 'enrolled',
                'enrollment_date' => now(),
            ]);

            return redirect()->route('dashboard.courses.show', ['slug' => $course->slug])
                ->with('success', 'Scholarship applied! You have been successfully enrolled in the course.');
        }
        // --- SCHOLARSHIP LOGIC END ---

        // Existing logic for non-scholarship users (Add to cart)
        $cart = $user->carts()->where('status', 'active')->first();
        
        if (!$cart) {
            $cart = $user->carts()->create([
                'status' => 'active',
                'session_id' => session()->getId(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $existingItem = $cart->items()->where('course_id', $course->id)->first();
        
        if ($existingItem) {
            return redirect()->route('dashboard.cart.index')->with('info', 'Course is already in your cart.');
        }
        
        $cart->items()->create([
            'item_type' => 'course',
            'course_id' => $course->id,
            'price' => $course->discount_price ?? $course->price,
            'quantity' => 1,
        ]);
        
        $cart->update([
            'total_amount' => $cart->items->sum('price'),
            'item_count' => $cart->items->count(),
            'updated_at' => now(),
        ]);

        return redirect()->route('dashboard.cart.index')->with('success', 'Course added to cart successfully!');
    }


}
