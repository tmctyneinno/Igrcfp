<?php

namespace App\Http\Controllers;

use App\Models\CourseCategory;
use App\Models\Course;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\CourseMaterial;
use App\Models\CourseModuleUser;
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
                    $moduleProgress = $this->calculateEnrollmentModuleProgress($enrollment);

                    return [
                        'enrollment_id' => $enrollment->id,
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug,
                        'short_description' => $course->short_description,
                        'banner_image' => $course->banner_image ? Storage::url($course->banner_image) : null,
                        'image_url' => $course->image_url,
                        'level' => $course->level,
                        'duration' => $course->duration,
                        'progress' => $moduleProgress['progress'],
                        'modules_count' => $moduleProgress['total_modules'],
                        'completed_modules' => $moduleProgress['completed_modules'],
                        'module_ids' => $moduleProgress['module_ids'],
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

        $enrolledCourseIds = [];
        if ($user) {
            $enrolledCourseIds = array_unique(array_merge(
                $user->courses()->pluck('courses.id')->toArray(),
                $user->enrollments()->pluck('course_id')->toArray()
            ));
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
            ->map(function ($course) use ($enrolledCourseIds) {
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
                    'is_enrolled' => in_array($course->id, $enrolledCourseIds),
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

        $enrolledCourseIds = [];
        if ($request->user()) {
            $user = $request->user();
            $enrolledCourseIds = array_unique(array_merge(
                $user->courses()->pluck('courses.id')->toArray(),
                $user->enrollments()->pluck('course_id')->toArray()
            ));
        }

        // Transform courses for the frontend
        $courses->getCollection()->transform(function ($course) use ($enrolledCourseIds) {
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
                'created_at' => $course->created_at->format('M d, Y'),
                'is_enrolled' => in_array($course->id, $enrolledCourseIds),
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
                $moduleProgress = $this->calculateEnrollmentModuleProgress($enrollment);

                return [
                    'enrollment_id' => $enrollment->id,
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'short_description' => $course->short_description,
                    'banner_image' => $course->banner_image, 
                    'image_url' => $course->image_url, 
                    'level' => $course->level,
                    'duration' => $course->duration,
                    'progress' => $moduleProgress['progress'],
                    'modules_count' => $moduleProgress['total_modules'],
                    'completed_modules' => $moduleProgress['completed_modules'],
                    'module_ids' => $moduleProgress['module_ids'],
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

    private function calculateEnrollmentModuleProgress(Enrollment $enrollment): array
    {
        $course = $enrollment->course;

        if (!$course) {
            return [
                'completed_modules' => 0,
                'total_modules' => 0,
                'module_ids' => [],
                'progress' => 0,
            ];
        }

        $modules = $course->modules()->get(['id']);
        $totalModules = $modules->count();

        if ($totalModules === 0) {
            return [
                'completed_modules' => 0,
                'total_modules' => 0,
                'module_ids' => [],
                'progress' => $enrollment->progress ?? 0,
            ];
        }

        $readModuleIds = CourseModuleUser::where('enrollment_id', $enrollment->id)
            ->where('user_id', $enrollment->user_id)
            ->where('read', true)
            ->pluck('course_module_id')
            ->all();

        $readModuleIds = array_flip($readModuleIds);

        $completedModules = $modules->filter(function ($module) use ($readModuleIds) {
            return isset($readModuleIds[$module->id]);
        })->count();

        return [
            'completed_modules' => $completedModules,
            'total_modules' => $totalModules,
            'module_ids' => $modules->pluck('id')->values()->all(),
            'progress' => (int) round(($completedModules / $totalModules) * 100),
        ];
    }
 
    public function showCourse($slug)
{
    $course = Course::where('slug', $slug)->firstOrFail();
     
    $enrollment = Enrollment::where('user_id', auth()->id())
        ->where('course_id', $course->id)
        ->whereIn('status', ['enrolled', 'active', 'completed'])
        ->first();

    // Get the COMBINED course quiz (one assessment that contains all module questions)
    $combinedQuiz = Assessment::where('course_id', $course->id)
        ->where('assessment_level', 'quiz')
        ->with(['submissions' => function($query) {
            $query->where('user_id', auth()->id());
        }])
        ->first();
    
    if ($combinedQuiz) {
        $submission = $combinedQuiz->submissions->first();
        $quizzes = [[
            'id' => $combinedQuiz->id,
            'title' => $combinedQuiz->title ?? 'Course Quiz',
            'description' => $combinedQuiz->description,
            'duration' => $combinedQuiz->duration,
            'questions_count' => $combinedQuiz->questions()->count(),
            'total_marks' => $combinedQuiz->total_marks,
            'passing_score' => $combinedQuiz->passing_score,
            'status' => $submission ? $submission->status : 'not_started',
            'score' => $submission ? $submission->score : null,
            'passed' => $submission ? $submission->passed : null,
            'unlocked' => $this->isCourseQuizUnlocked($course, $enrollment),
            'reason' => $this->isCourseQuizUnlocked($course, $enrollment) ? null : 'Complete all lessons first',
        ]];
    } else {
        $quizzes = [];
    }

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
        ->whereIn('assessment_level', ['diploma', 'project'])
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
            'graded_at' => $submission && $submission->graded_at ? $submission->graded_at->format('Y-m-d H:i:s') : null,
            'due_date' => $diplomaAssessment->due_date,
            'requires_identity_verification' => $diplomaAssessment->requires_identity_verification,
            'needs_manual_marking' => $diplomaAssessment->needs_manual_marking,
        ];
    } else {
        $diplomaAssessment = null;
    }

    $quizPassed = $combinedQuiz ? (bool) ($quizzes[0]['passed'] ?? false) : true;
    $moduleAssessmentsPassed = $moduleAssessments->isEmpty()
        ? true
        : $moduleAssessments->every(fn ($assessment) => (bool) ($assessment['passed'] ?? false));
    $finalExamPassed = $finalExam ? (bool) ($finalExam['passed'] ?? false) : true;
    $projectAssessmentPassed = $diplomaAssessment
        ? (($diplomaAssessment['status'] ?? null) === 'graded' && (bool) ($diplomaAssessment['passed'] ?? false))
        : false;
    $diplomaPassed = $projectAssessmentPassed;
    $assessmentsPassed = $moduleAssessmentsPassed && $finalExamPassed && $diplomaPassed;

    $examResults = [
        'quiz_passed' => $quizPassed,
        'module_assessments_passed' => $moduleAssessmentsPassed,
        'final_exam_passed' => $finalExamPassed,
        'diploma_passed' => $diplomaPassed,
        'assessments_passed' => $assessmentsPassed,
        'all_passed' => $quizPassed && $assessmentsPassed,
        'certificate_eligible' => $quizPassed && $assessmentsPassed,
        'project_assessment_required' => (bool) $diplomaAssessment,
        'project_assessment_completed' => $diplomaAssessment ? ($diplomaAssessment['status'] ?? null) === 'graded' : false,
        'project_assessment_passed' => $projectAssessmentPassed,
    ];

    $certification = [
        'can_display_card' => $projectAssessmentPassed && (($quizPassed && $assessmentsPassed) || (bool) $enrollment?->certificate_generated),
        'project_assessment_required' => (bool) $diplomaAssessment,
        'project_assessment_completed' => $diplomaAssessment ? ($diplomaAssessment['status'] ?? null) === 'graded' : false,
        'project_assessment_passed' => $projectAssessmentPassed,
        'certificate_eligible' => $quizPassed && $assessmentsPassed,
    ];
   
    $moduleReadingStatuses = $enrollment
        ? CourseModuleUser::where('enrollment_id', $enrollment->id)
            ->where('user_id', auth()->id())
            ->get()
            ->keyBy('course_module_id')
        : collect();

    $modules = $course->modules()
        ->with(['lessons' => function($query) use ($enrollment) {
            if ($enrollment) {
                $query->withCompletionStatus(auth()->id(), $enrollment->id);
            }
        }])
        ->orderBy('module_number')
        ->get()
        ->map(function ($module) use ($moduleReadingStatuses) {
            $readingStatus = $moduleReadingStatuses->get($module->id);
            $isRead = (bool) ($readingStatus?->read ?? false);

            return [
                'id' => $module->id,
                'title' => $module->title,
                'module_number' => $module->module_number,
                'course_outline' => $module->course_outline,
                'learning_objectives' => $module->learning_objectives,
                'full_content' => $module->full_content,
                'reading_content' => filled($module->full_content) ? $module->full_content : $module->course_outline,
                'estimated_hours' => $module->estimated_hours,
                'reading_progress' => $isRead ? 100 : (int) ($readingStatus?->reading_progress ?? 0),
                'read' => $isRead,
                'read_at' => optional($readingStatus?->read_at)->toIso8601String(),
                'lessons' => $module->lessons->map(function ($lesson) {
                    return [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'description' => $lesson->description,
                        'duration' => $lesson->duration,
                        'lesson_type' => $lesson->lesson_type ?? 'reading',
                        'content' => $lesson->content,
                        'completed' => (bool) ($lesson->completed ?? false),
                    ];
                })->toArray(),
            ];
        });

    $courseMaterials = $course->materials()
        ->orderBy('sort_order')
        ->get()
        ->map(fn ($material) => $this->formatCourseMaterial($material))
        ->toArray();

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
            'certification' => $certification,
            'auth' => ['user' => auth()->user()]
        ]); 
    }

    return Inertia::render('Dashboard/Courses/EnrollmentIndex', [
        'course' => $course,
        'enrollment' => $enrollment,
        'modules' => $modules, 
        'courseMaterials' => $courseMaterials,
        'quizzes' => $quizzes,
        'moduleAssessments' => $moduleAssessments,
        'finalExam' => $finalExam,
        'diplomaAssessment' => $diplomaAssessment,
        'candidate' => [
            'certificate_id' => $enrollment->certificate_number ?? auth()->user()->candidate_id,
        ],
        'examResults' => $examResults,
        'certification' => $certification,
    ]);
}

public function downloadCourseMaterial(CourseMaterial $material)
{
    $enrolled = Enrollment::where('user_id', auth()->id())
        ->where('course_id', $material->course_id)
        ->whereIn('status', ['enrolled', 'active', 'completed'])
        ->exists();

    abort_unless($enrolled, 403);
    abort_unless($material->is_downloadable, 403);
    abort_unless(Storage::disk('public')->exists($material->file_path), 404);

    $material->incrementDownloadCount();

    return Storage::disk('public')->download(
        $material->file_path,
        $material->file_name ?: basename($material->file_path)
    );
}

private function formatCourseMaterial(CourseMaterial $material): array
{
    return [
        'id' => $material->id,
        'title' => $material->title,
        'file_name' => $material->file_name,
        'file_type' => $material->file_type,
        'file_size' => $material->formatted_size,
        'file_url' => $material->file_url,
        'download_url' => route('dashboard.course-materials.download', $material),
        'is_downloadable' => $material->is_downloadable,
    ];
}


private function isCourseQuizUnlocked($course, $enrollment)
{
    if (!$enrollment) return false;

    $modules = $course->modules()->get(['id']);

    if ($modules->isEmpty()) {
        return true;
    }

    $readModuleIds = CourseModuleUser::where('enrollment_id', $enrollment->id)
        ->where('user_id', $enrollment->user_id)
        ->where('read', true)
        ->pluck('course_module_id')
        ->all();

    $readModuleIds = array_flip($readModuleIds);

    return $modules->every(function ($module) use ($readModuleIds) {
        return isset($readModuleIds[$module->id]);
    });
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

    public function memebership(Request $request)
    {
        
         // dd($popularCourses);
        return Inertia::render('Dashboard/Memebership/Index');
    } 
}
