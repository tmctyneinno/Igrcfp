<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\Event;
use Inertia\Inertia;
 
class HomeController extends Controller
{
    public function index()
    {
        $featuredCourses = Course::published()
            ->withCount('modules')
            ->where('is_featured', true)
            ->orWhere('is_popular', true)
            ->inRandomOrder()
            ->take(8)
            ->get();
 
        // Get 4 random courses from the remaining
        $randomCourses = Course::published()
            ->withCount('modules')
            ->whereNotIn('id', $featuredCourses->pluck('id'))
            ->inRandomOrder()
            ->take(8)
            ->get();

        // Merge and shuffle the collections
        $courses = $featuredCourses->merge($randomCourses)
            ->shuffle()
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

        return Inertia::render('Welcome', [
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
            'courses' => $courses,
        ]);
    }

    public function welcomeToIGRCFP()
    {
        return Inertia::render('About/WelcomeIGRCFP/Index', [
            'title' => 'Welcome to IGRCFP',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }

    public function OurStructure(){
        return Inertia::render('About/OurStructure/Index', [
            'title' => 'Our Structure',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }

    public function whyIgrcfp(){
        return Inertia::render('About/WhyIgrcfp/Index');
    }

    public function membership(){
        return Inertia::render('Membership/Index', [
            'title' => 'Membership',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }

    public function certifications()
    {
        // Get featured/popular courses
        $featuredCourses = Course::published()
            ->withCount('modules')
            ->where(function($query) {
                $query->where('is_featured', true)
                    ->orWhere('is_popular', true);
            })
            ->inRandomOrder()
            ->take(8)
            ->get();
    
        // Get random courses excluding the featured ones
        $randomCourses = Course::published()
            ->withCount('modules')
            ->whereNotIn('id', $featuredCourses->pluck('id'))
            ->inRandomOrder()
            ->take(8)
            ->get();

        // Merge and shuffle the collections
        $allCourses = $featuredCourses->merge($randomCourses)
            ->shuffle()
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

        // Create a paginator instance to maintain the same structure
        $perPage = 12;
        $currentPage = request()->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $courses = new \Illuminate\Pagination\LengthAwarePaginator(
            $allCourses->slice($offset, $perPage)->values(),
            $allCourses->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return Inertia::render('Certifications/Index', [
            'courses' => $courses,
            'title' => 'Certifications & Trainings',
            'description' => 'Learn about the Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP) Professionals body.',
        ]);
    }
 
    public function certificationsOverview(){
    
        return Inertia::render('Certifications/CertificationsOverview', [
            'title' => 'certifications Overview',
            'description' => 'Certified GRC & Financial Crime Specialist',
        ]);
    }

    public function certificationsPathway(){
        return Inertia::render('Certifications/Pathway', [
            'title' => 'Certification Pathway',
            'description' => '',
        ]);
    }

    public function cgfcsSpecialist(){
        \Log::info('CGFCS Specialist route accessed');
    
        return Inertia::render('Certifications/CGFCSSpecialist', [
            'title' => 'CGFCS Specialist',
            'description' => 'Certified GRC & Financial Crime Specialist',
        ]);
    }


    public function eventsIndex(){
        $currentDate = now()->toDateString();
        
        $events = Event::query()
            ->where('status', 'published')
            ->select('*')
            ->selectRaw("CASE WHEN start_date >= '{$currentDate}' THEN 0 ELSE 1 END as is_upcoming")
            ->selectRaw("CASE WHEN start_date >= '{$currentDate}' THEN start_date ELSE DATE('9999-12-31') - start_date END as sort_date")
            ->orderBy('is_upcoming')
            ->orderBy('sort_date')
            ->paginate(10);

        return Inertia::render('Events/Index', [
            'title' => 'Professional Events & Workshops',
            'description' => 'Join IGRCFP for expert-led workshops, seminars, and networking events designed for governance, risk, compliance, and financial crime prevention professionals.',
            'events' => $events,
        ]);
    }

    public function eventShow($slug)
    {
        $event = Event::where('slug', $slug)
            ->where('status', 'published')
            ->with('user')
            ->firstOrFail();

        $upcomingEvents = Event::where('status', 'published')
            ->where('id', '!=', $event->id)
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->limit(3)
            ->get();

        // If we don't have enough upcoming events, get events from same venue
        if ($upcomingEvents->count() < 3 && $event->venue) {
            $venueEvents = Event::where('status', 'published')
                ->where('id', '!=', $event->id)
                ->where('venue', $event->venue)
                ->whereNotIn('id', $upcomingEvents->pluck('id'))
                ->inRandomOrder()
                ->limit(3 - $upcomingEvents->count())
                ->get();
            
            $upcomingEvents = $upcomingEvents->merge($venueEvents);
        }

        // If still not enough, get events from same location
        if ($upcomingEvents->count() < 3 && $event->location) {
            $locationEvents = Event::where('status', 'published')
                ->where('id', '!=', $event->id)
                ->where('location', $event->location)
                ->whereNotIn('id', $upcomingEvents->pluck('id'))
                ->inRandomOrder()
                ->limit(3 - $upcomingEvents->count())
                ->get();
            
            $upcomingEvents = $upcomingEvents->merge($locationEvents);
        }

        // Final fallback: random events
        if ($upcomingEvents->count() < 3) {
            $randomEvents = Event::where('status', 'published')
                ->where('id', '!=', $event->id)
                ->whereNotIn('id', $upcomingEvents->pluck('id'))
                ->inRandomOrder()
                ->limit(3 - $upcomingEvents->count())
                ->get();
            
            $upcomingEvents = $upcomingEvents->merge($randomEvents);
        }

        return Inertia::render('Events/Show', [
            'event' => $event,
            'relatedEvents' => $upcomingEvents,
        ]);
    }

    public function eventRegister($slug)
    {
        $event = Event::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Check if registration is open
        if ($event->registration_status === 'sold_out') {
            abort(403, 'This event is sold out.');
        }

        return Inertia::render('Events/Register', [
            'event' => $event,
        ]);
    }

    public function blog(){
        return Inertia::render('Blog/Index', [
            'title' => 'Blog',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }

    public function contact(){
        return Inertia::render('Contact/Index', [
            'title' => 'Contact Us',
            'description' => 'Our friendly team would love to hear from you.',
        ]);
    }

    public function trainingCalendar(){
        return Inertia::render('Certifications/TrainingCalendar', [
            'title' => 'Training Calendar',
            'description' => 'Our friendly team would love to hear from you.',
        ]);
    }

    public function news()
    {
        return Inertia::render('News/Index', [
            'title' => 'News',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }

    public function privacyPolicy(){
        return Inertia::render('PrivacyPolicy/Index');
    }

    public function termsCondition(){
        return Inertia::render('TermsCondition/Index');
    }

    public function privacyPreferenceCenter(){
        return Inertia::render('PrivacyPreferenceCenter/Index');
    }


  
    public function showIgrcfpProgramme(Request $request, string $programme)
    {
        $programmeConfig = [
            'certificates' => [
                'category' => 'IGRCFP Certificates',
                'title' => 'IGRCFP Certificates',
                'description' => 'Browse IGRCFP certificate courses and specialist programmes.',
            ],
            'diploma' => [
                'category' => 'IGRCFP Diploma',
                'title' => 'IGRCFP Diploma',
                'description' => 'Browse courses in the IGRCFP Diploma pathway.',
            ],
            'advanced-diploma' => [
                'category' => 'IGRCFP Advanced Diploma',
                'title' => 'IGRCFP Advanced Diploma',
                'description' => 'Browse courses in the IGRCFP Advanced Diploma pathway.',
            ],
            'certified-grc-financial-crime-specialist' => [
                'category' => 'Certified GRC & Financial Crime Specialist',
                'title' => 'Certified GRC & Financial Crime Specialist',
                'description' => 'Browse courses for the Certified GRC & Financial Crime Specialist pathway.',
            ],
            'fellowship' => [
                'category' => 'IGRCFP Fellowship',
                'title' => 'IGRCFP Fellowship',
                'description' => 'Browse courses in the IGRCFP Fellowship pathway.',
            ],
        ];

        abort_unless(isset($programmeConfig[$programme]), 404);

        $currentProgramme = $programmeConfig[$programme];

        $query = Course::published()
            ->where('igrcfp_category', $currentProgramme['category'])
            ->withCount('modules');

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('short_description', 'like', "%{$searchTerm}%")
                ->orWhere('full_description', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by level
        if ($request->has('level') && !empty($request->level)) {
            $query->where('level', $request->level);
        }

        // Filter by course category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
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

        // Filter by format
        if ($request->has('format') && !empty($request->format)) {
            $query->where('format', $request->format);
        }

        // Handle sorting - supports both combined format (field_direction) and separate parameters
        $sortInput = $request->get('sort_field', 'created_at_desc');
        
        // Parse sort field and direction
        if (str_contains($sortInput, '_')) {
            $parts = explode('_', $sortInput);
            $direction = array_pop($parts); // Get the last part (asc/desc)
            $field = implode('_', $parts); // The rest is the field name
            
            // Validate direction
            $sortDirection = in_array(strtolower($direction), ['asc', 'desc']) ? strtolower($direction) : 'desc';
            
            // Map the field to actual column names
            $sortField = $field;
        } else {
            // Fallback to separate parameters if combined format not used
            $sortField = $request->get('sort_field', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            
            // Validate direction
            $sortDirection = in_array(strtolower($sortDirection), ['asc', 'desc']) ? strtolower($sortDirection) : 'desc';
        }

        // Allowed sort fields
        $allowedSortFields = ['title', 'price', 'created_at', 'modules_count'];
        
        // Apply sorting
        if (in_array($sortField, $allowedSortFields)) {
            if ($sortField === 'modules_count') {
                $query->orderBy('modules_count', $sortDirection);
            } else {
                $query->orderBy($sortField, $sortDirection);
            }
        } else {
            // Default sorting
            $query->orderBy('is_featured', 'desc')
                ->orderBy('is_popular', 'desc')
                ->orderBy('created_at', 'desc');
        }

        // Get filter options for dropdowns
        $levels = Course::published()->select('level')->distinct()->pluck('level');
        $categories = CourseCategory::where('is_active', true)
            ->whereIn('id', Course::published()
                ->where('igrcfp_category', $currentProgramme['category'])
                ->whereNotNull('category_id')
                ->select('category_id')
                ->distinct()
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);
        
        // Get unique formats if they exist
        $formats = Course::published()->whereNotNull('format')->select('format')->distinct()->pluck('format');

        // Paginate results
        $courses = $query->paginate(20)->withQueryString();

        // Transform courses for the frontend
        $courses->getCollection()->transform(function ($course) {
            // Handle instructor relationship if it exists
            $instructorData = null;
            if (isset($course->instructor) && $course->instructor) {
                $instructorData = [
                    'name' => $course->instructor->name,
                    'avatar' => $course->instructor->avatar ?? null
                ];
            }

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
                'modules_count' => $course->modules_count,
                'is_featured' => $course->is_featured,
                'is_popular' => $course->is_popular,
                'format' => $course->format,
                'instructor' => $instructorData,
                'created_at' => $course->created_at->format('M d, Y')
            ];
        });
 
        return Inertia::render('Courses/Index', [
            'title' => $currentProgramme['title'],
            'description' => $currentProgramme['description'],
            'igrcfpCategory' => $currentProgramme['category'],
            'filters' => [
                'search' => $request->search ?? '',
                'level' => $request->level ?? '',
                'category' => $request->category ?? '',
                'price_type' => $request->price_type ?? '',
                'featured' => $request->featured ?? false,
                'popular' => $request->popular ?? false,
                'format' => $request->format ?? '',
                'sort_field' => $sortInput, // Return the combined format for consistency
            ],
            'courses' => $courses,
            'filterOptions' => [
                'levels' => $levels,
                'categories' => $categories,
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
