<?php

namespace App\Http\Controllers;

use App\Models\CourseCategory;
use App\Models\Course;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\Enrollment;
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
        // Get course categories with course counts
        $categories = CourseCategory::where('is_active', true)
            ->withCount('courses')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(6)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon,
                    'description' => $category->description,
                    'courses_count' => $category->courses_count,
                ];
            });
        // Get available courses with category
        $courses = Course::published()
            ->with('category')
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
                    'banner_image' => $course->banner_image ? Storage::url($course->banner_image) : null,
                    'image_url' => $course->image_url,
                    'level' => $course->level,
                    'duration' => $course->duration,
                    'price' => $course->price,
                    'discount_price' => $course->discount_price,
                    'modules_count' => $course->modules_count,
                    'category' => $course->category ? [
                        'id' => $course->category->id,
                        'name' => $course->category->name,
                        'slug' => $course->category->slug,
                        'icon' => $course->category->icon,
                    ] : null,
                ];
            });
        
        // Get user's enrolled courses - Check if user is authenticated first
        $user = auth()->user();
        $enrolledCourses = collect(); // Empty collection by default
        
        if ($user) {
            $enrolledCourses = Enrollment::where('user_id', $user->id)
                ->with(['course' => function($query) {
                   $query->withCount('modules')->with('category');
                }])
                ->take(4)
                ->get()
                ->map(function ($enrollment) {
                    // Check if course exists
                    if (!$enrollment->course) {
                        return null;
                    }
                    
                    $course = $enrollment->course;
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug,
                        'short_description' => $course->short_description,
                        'banner_image' => $course->banner_image ? Storage::url($course->banner_image) : null,
                        'image_url' => $course->image_url,
                        'level' => $course->level,
                        'duration' => $course->duration,
                        'progress' => $enrollment->progress ?? 0,
                        'modules_count' => $course->modules_count ?? 0,
                        'completed_modules' => $enrollment->completed_modules ?? 0,
                        'format' => $course->format,
                        'status' => $enrollment->status ?? 'enrolled',
                        'category' => $course->category ? [
                            'id' => $course->category->id,
                            'name' => $course->category->name,
                            'slug' => $course->category->slug,
                            'icon' => $course->category->icon,
                        ] : null,
                    ];
                })
                ->filter() // Remove null values
                ->values(); // Reset array keys
        }
        
        // Get popular courses
        $popularCourses = Course::published()
            ->with('category')
            ->where('is_popular', 1)
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
                'rating',
                'category_id' 
            ])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'short_description' => $course->short_description,
                    'banner_image' => $course->banner_image ? Storage::url($course->banner_image) : null,
                    'image_url' => $course->image ? Storage::url($course->image) : null,
                    'level' => $course->level,
                    'format' => $course->format,
                    'duration' => $course->duration,
                    'price' => $course->price,
                    'discount_price' => $course->discount_price,
                    'is_featured' => $course->is_featured,
                    'rating' => $course->rating,
                    'category' => $course->category ? [
                        'id' => $course->category->id,
                        'name' => $course->category->name,
                        'slug' => $course->category->slug,
                        'icon' => $course->category->icon,
                    ] : null,
                ];
            });
        
        // Calculate stats
        $stats = [
            'total_courses' => $enrolledCourses->count(),
            'completed' => $enrolledCourses->where('status', 'completed')->count(),
            'in_progress' => $enrolledCourses->where('status', 'enrolled')->count(),
            'total_categories' => $categories->count(),
        ];
        
        return Inertia::render('Dashboard/Index', [
            'courses' => $courses,
            'enrolledCourses' => $enrolledCourses,
            'popularCourses' => $popularCourses,
            'categories' => $categories,
            'stats' => $stats,
        ]);
    }

    public function courses(Request $request)
    {
        $query = Course::published()
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

        return Inertia::render('Dashboard/Courses/Index', [
            'filters' => [
                'search' => $request->search ?? '',
                'level' => $request->level ?? '',
                'price_type' => $request->price_type ?? '',
                'featured' => $request->featured ?? false,
                'popular' => $request->popular ?? false,
                'format' => $request->format ?? '',
                'sort_field' => $sortInput, // Return the combined format for consistency
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

    public function myCourse(Request $request)
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
        return Inertia::render('Dashboard/MyLearning', [
            'courses' => $courses,
            'enrolledCourses' => $enrolledCourses,
        ]);
    } 
 
    public function showCourse($slug)
{
    $course = Course::where('slug', $slug)->firstOrFail();
    
    $enrollment = Enrollment::where('user_id', auth()->id())
        ->where('course_id', $course->id)
        ->first();

    // Get all assessments for this course with user's attempt status
    $quizzes = Assessment::where('course_id', $course->id)
    ->where('assessment_level', 'quiz')
    ->with(['submissions' => function($query) {
        $query->where('user_id', auth()->id());
    }])
    ->get()
    ->groupBy('module_id') // Group by module_id
    ->map(function($moduleQuizzes, $moduleId) {
        // Get the module info from the first quiz in the group
        $firstQuiz = $moduleQuizzes->first();
        $module = $firstQuiz->module;
        
        // Calculate total questions across all quizzes in this module
        $totalQuestions = $moduleQuizzes->sum(function($quiz) {
            return $quiz->questions()->count();
        });
        
        // Get the user's submission status (if any)
        $submission = $moduleQuizzes->first()->submissions->first();
        
        return [
            'id' => 'module-' . $moduleId, // Create a unique ID for the grouped quiz
            'module_id' => $moduleId,
            'module_name' => $module ? $module->title : 'General',
            'module_number' => $module ? $module->module_number : null,
            'title' => $module ? "Module {$module->module_number} Quiz" : "Module Quiz",
            'description' => $module ? "Complete all quizzes for Module {$module->module_number}" : "Module Quiz",
            'duration' => $moduleQuizzes->sum('duration'), // Total duration
            'questions_count' => $totalQuestions,
            'total_marks' => $moduleQuizzes->sum('total_marks'),
            'passing_score' => $moduleQuizzes->avg('passing_score'), // Average passing score
            'status' => $this->getModuleQuizStatus($moduleQuizzes), // Custom function to determine status
            'score' => $submission ? $submission->score : null,
            'passed' => $submission ? $submission->passed : null,
            'due_date' => $moduleQuizzes->max('due_date'), // Latest due date
            'quiz_ids' => $moduleQuizzes->pluck('id'), // Store the actual quiz IDs
        ];
    })->values(); // Reset keys
     
    $moduleAssessments = Assessment::where('course_id', $course->id)
        ->where('assessment_level', 'module_assessment')
        ->with(['submissions' => function($query) {
            $query->where('user_id', auth()->id());
        }])
        ->orderBy('created_at')
        ->get()
        ->map(function($assessment) {
            $submission = $assessment->submissions->first();
            return [
                'id' => $assessment->id,
                'title' => $assessment->title,
                'description' => $assessment->description,
                'duration' => $assessment->duration,
                'questions_count' => $assessment->questions()->count(),
                'total_marks' => $assessment->total_marks,
                'passing_score' => $assessment->passing_score,
                'status' => $submission ? $submission->status : 'not_started',
                'score' => $submission ? $submission->score : null,
                'passed' => $submission ? $submission->passed : null,
                'due_date' => $assessment->due_date,
                'requires_identity_verification' => $assessment->requires_identity_verification,
            ];
        });

    $finalExam = Assessment::where('course_id', $course->id)
        ->where('assessment_level', 'final_exam')
        ->with(['submissions' => function($query) {
            $query->where('user_id', auth()->id());
        }])
        ->first();

    if ($finalExam) {
        $submission = $finalExam->submissions->first();
        $finalExam = [
            'id' => $finalExam->id,
            'title' => $finalExam->title,
            'description' => $finalExam->description,
            'duration' => $finalExam->duration,
            'questions_count' => $finalExam->questions()->count(),
            'total_marks' => $finalExam->total_marks,
            'passing_score' => $finalExam->passing_score,
            'status' => $submission ? $submission->status : 'not_started',
            'score' => $submission ? $submission->score : null,
            'passed' => $submission ? $submission->passed : null,
            'due_date' => $finalExam->due_date,
            'requires_identity_verification' => $finalExam->requires_identity_verification,
        ];
    } else {
        $finalExam = null;
    }

    $diplomaAssessment = Assessment::where('course_id', $course->id)
        ->where('assessment_level', 'diploma')
        ->with(['submissions' => function($query) {
            $query->where('user_id', auth()->id());
        }])
        ->first();

    if ($diplomaAssessment) {
        $submission = $diplomaAssessment->submissions->first();
        $diplomaAssessment = [
            'id' => $diplomaAssessment->id,
            'title' => $diplomaAssessment->title,
            'description' => $diplomaAssessment->description,
            'project_brief' => $diplomaAssessment->project_brief,
            'total_marks' => $diplomaAssessment->total_marks,
            'passing_score' => $diplomaAssessment->passing_score,
            'status' => $submission ? $submission->status : 'not_started',
            'score' => $submission ? $submission->score : null,
            'passed' => $submission ? $submission->passed : null,
            'due_date' => $diplomaAssessment->due_date,
            'requires_identity_verification' => $diplomaAssessment->requires_identity_verification,
            'needs_manual_marking' => $diplomaAssessment->needs_manual_marking,
        ];
    } else {
        $diplomaAssessment = null;
    }

    // Calculate exam results summary
    $examResults = [
        'all_passed' => $this->checkAllAssessmentsPassed($course->id),
        'quizzes_completed' => $quizzes->where('status', 'completed')->count(),
        'total_quizzes' => $quizzes->count(),
        'assessments_completed' => $moduleAssessments->where('status', 'completed')->count(),
        'total_assessments' => $moduleAssessments->count(),
        'final_exam_passed' => $finalExam ? ($finalExam['passed'] ?? false) : null,
        'diploma_passed' => $diplomaAssessment ? ($diplomaAssessment['passed'] ?? false) : null,
    ];

    if (!$enrollment) {
        return Inertia::render('Dashboard/Courses/Show', [
            'course' => $course,
            'enrollment' => $enrollment,
            'modules' => $course->modules()->with('lessons')->get(),
            'quizzes' => $quizzes,
            'moduleAssessments' => $moduleAssessments,
            'finalExam' => $finalExam,
            'diplomaAssessment' => $diplomaAssessment,
            'examResults' => $examResults,
            'auth' => [
                'user' => auth()->user()
            ]
        ]);
    }

    return Inertia::render('Dashboard/Courses/Enrollment', [
        'course' => $course,
        'enrollment' => $enrollment,
        'modules' => $course->modules()->with('lessons')->get(),
        'quizzes' => $quizzes,
        'moduleAssessments' => $moduleAssessments,
        'finalExam' => $finalExam,
        'diplomaAssessment' => $diplomaAssessment,
        'candidate' => [
            'certificate_id' => $enrollment->certificate_number ?? auth()->user()->candidate_id,
        ],
        'examResults' => $examResults,
    ]);
}

private function getModuleQuizStatus($moduleQuizzes)
{
    $allCompleted = true;
    $anyInProgress = false;
    
    foreach ($moduleQuizzes as $quiz) {
        $submission = $quiz->submissions->first();
        
        if (!$submission) {
            $allCompleted = false;
        } elseif ($submission->status === 'in_progress') {
            $anyInProgress = true;
            $allCompleted = false;
        } elseif ($submission->status !== 'completed') {
            $allCompleted = false;
        }
    }
    
    if ($allCompleted) return 'completed';
    if ($anyInProgress) return 'in_progress';
    return 'not_started';
}


    private function checkAllAssessmentsPassed($courseId)
    {
        $assessments = Assessment::where('course_id', $courseId)
            ->whereIn('assessment_level', ['final_exam', 'diploma'])
            ->get();
        
        if ($assessments->isEmpty()) {
            return true; // No required assessments
        }
        
        foreach ($assessments as $assessment) {
            $submission = $assessment->submissions()
                ->where('user_id', auth()->id())
                ->first();
                
            if (!$submission || !$submission->passed) {
                return false;
            }
        }
        
        return true;
    }

    // Alternative method name if you're using courseSlug()
    public function courseSlug($slug)
    {
        return $this->showCourse($slug);
    }
}