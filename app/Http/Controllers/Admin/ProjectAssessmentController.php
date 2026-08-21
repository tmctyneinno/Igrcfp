<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
 
class ProjectAssessmentController extends Controller
{
    /**
     * Display a listing of project assessments.
     */
    public function index(Request $request)
    {
        $query = Assessment::projects()
            ->with(['course', 'module'])
            ->withCount('submissions')
            ->orderBy('created_at', 'desc');

        if ($request->has('course_id') && $request->course_id != '') {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $assessments = $query->paginate(15);
        $courses = Course::orderBy('title')->get();
        
        $statistics = [
            'total' => Assessment::projects()->count(),
            'active' => Assessment::projects()->where('status', 'active')->count(),
            'draft' => Assessment::projects()->where('status', 'draft')->count(),
            'submissions' => \App\Models\AssessmentSubmission::whereHas('assessment', fn($q) => $q->projects())->count(),
        ];

        return view('admin.courses.assessments.projects.index', compact('assessments', 'courses', 'statistics'));
    } 

    /**
     * Show the form for creating a new project assessment.
     */
    public function create()
    {
        $courses = Course::where('status', 'published')->orderBy('title')->get();
        $modules = CourseModule::orderBy('module_number')->get();
        
        return view('admin.courses.assessments.projects.create', compact('courses', 'modules'));
    }

    /**
     * Store a newly created project assessment.
     */
   public function store(Request $request)
    {
        \Log::info('Project assessment store called', $request->all());
        
        try {
            $projectTopics = $request->input('settings.project_topics', '');
            $derivedTitle = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($projectTopics))), 120, '');

            $request->merge([
                'title' => $request->input('title') ?: ($derivedTitle ?: 'Project Assessment'),
            ]);

            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'course_id' => 'required|exists:courses,id',
                'module_id' => 'nullable|exists:course_modules,id',
                'project_brief' => 'required|string',
                'deliverables' => 'nullable|string',
                'instructions' => 'nullable|string',
                'status' => 'required|in:draft,active',
                'total_marks' => 'nullable|integer|min:1',
                'passing_score' => 'nullable|integer|min:1|max:100',
                'weight' => 'nullable|integer|min:1|max:100',
                'release_date' => 'nullable|date',
                'due_date' => 'required|date',
                'allow_late_submissions' => 'nullable',
                'max_file_size' => 'nullable|integer|min:1',
                'requires_identity_verification' => 'nullable',
                'assessment_file' => 'nullable|file|mimes:pdf,doc,docx,pptx,xlsx,zip|max:51200',
                'settings' => 'nullable|array',
                'settings.project_topics' => 'required|string',
                'action' => 'nullable|string',
            ]);

            $validated['assessment_level'] = 'diploma';
            $validated['type'] = 'project';
            $validated['needs_manual_marking'] = true;
            $validated['allow_late_submissions'] = $request->has('allow_late_submissions');
            $validated['requires_identity_verification'] = $request->has('requires_identity_verification');
            
            // Set max_file_size in settings or directly
            if ($request->filled('settings.max_file_size')) {
                $validated['max_file_size'] = $request->input('settings.max_file_size');
            }

            // Handle file upload
            if ($request->hasFile('assessment_file')) {
                $file = $request->file('assessment_file');
                $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('assessments/projects', $filename, 'public');
                
                $validated['file_path'] = $path;
                $validated['file_name'] = $file->getClientOriginalName();
                $validated['file_size'] = $file->getSize();
                $validated['file_extension'] = $file->getClientOriginalExtension();
            }

            // Process settings
            $settings = [];
            if ($request->has('settings')) {
                $settings = $request->input('settings', []);
            }
            if ($request->has('settings.resources')) {
                $settings['resources'] = $request->input('settings.resources');
            }
            if ($request->has('settings.enable_plagiarism_check')) {
                $settings['enable_plagiarism_check'] = $request->has('settings.enable_plagiarism_check');
            }
            $validated['settings'] = $settings;

            $assessment = Assessment::create($validated);

            \Log::info('Project assessment created', ['id' => $assessment->id]);

            $action = $request->input('action', 'publish');
            $message = $action === 'draft' ? 'Project saved as draft.' : 'Project published successfully.';

            return redirect()->route('admin.projects.index')
                ->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Project creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Error creating project: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified project assessment.
     */
    public function show(Assessment $assessment)
    {
        $assessment->load(['course', 'module', 'submissions.user']);
        
        // Load submission counts
        $assessment->loadCount('submissions');
        $assessment->loadCount(['submissions as pending_grading_count' => function($q) {
            $q->where('status', 'submitted');
        }]);
        
        return view('admin.courses.assessments.projects.show', compact('assessment'));
    }
 
    /**
     * Show the form for editing the specified project assessment.
     */
    public function edit(Assessment $assessment)
    {
        $courses = Course::where('status', 'published')->orderBy('title')->get();
        $modules = CourseModule::orderBy('module_number')->get();
        
        return view('admin.courses.assessments.projects.edit', compact('assessment', 'courses', 'modules'));
    }

    /**
     * Update the specified project assessment.
     */
    public function update(Request $request, Assessment $assessment)
    {
        $projectTopics = $request->input('settings.project_topics', '');
        $derivedTitle = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($projectTopics))), 120, '');

        $request->merge([
            'title' => $derivedTitle ?: $request->input('title', $assessment->title),
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'module_id' => 'nullable|exists:course_modules,id',
            'project_brief' => 'required|string',
            'deliverables' => 'nullable|string',
            'instructions' => 'nullable|string',
            'status' => 'required|in:draft,active,archived',
            'total_marks' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:1|max:100',
            'weight' => 'nullable|integer|min:1|max:100',
            'release_date' => 'nullable|date',
            'due_date' => 'required|date',
            'allow_late_submissions' => 'boolean',
            'max_file_size' => 'nullable|integer|min:1',
            'requires_identity_verification' => 'boolean',
            'assessment_file' => 'nullable|file|mimes:pdf,doc,docx,pptx,xlsx|max:51200',
            'settings' => 'nullable|array',
            'settings.project_topics' => 'required|string',
        ]);

        $validated['allow_late_submissions'] = $request->has('allow_late_submissions');
        $validated['requires_identity_verification'] = $request->has('requires_identity_verification');

        // Handle file upload
        if ($request->hasFile('assessment_file')) {
            // Delete old file
            if ($assessment->file_path) {
                Storage::disk('public')->delete($assessment->file_path);
            }
            
            $file = $request->file('assessment_file');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('assessments/projects', $filename, 'public');
            
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
            $validated['file_extension'] = $file->getClientOriginalExtension();
        }

        // Process settings
        if ($request->has('settings')) {
            $validated['settings'] = $request->settings;
        }

        $assessment->update($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project assessment.
     */
    public function destroy(Assessment $assessment)
    {
        if ($assessment->file_path) {
            Storage::disk('public')->delete($assessment->file_path);
        }
        
        $assessment->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    /**
     * View submissions for a project.
     */
    public function submissions(Assessment $assessment)
    {
        $submissions = $assessment->submissions()
            ->with(['user', 'grader'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);
        
        $statistics = [
            'total' => $assessment->submissions()->count(),
            'pending' => $assessment->submissions()->where('status', 'submitted')->count(),
            'graded' => $assessment->submissions()->where('status', 'graded')->count(),
            'late' => $assessment->submissions()->where('status', 'late')->count(),
            'passed' => $assessment->submissions()->where('passed', true)->count(),
            'failed' => $assessment->submissions()->where('passed', false)->whereNotNull('graded_at')->count(),
            'average_score' => $assessment->submissions()->whereNotNull('percentage')->avg('percentage') ?? 0,
        ];

        return view('admin.courses.assessments.projects.submissions', compact('assessment', 'submissions', 'statistics'));
    }

    public function viewSubmission(AssessmentSubmission $submission)
    {
        $submission->load(['user', 'assessment.course', 'assessment.module', 'grader']);
        
        return view('admin.courses.assessments.projects.submission-view', compact('submission'));
    }

    public function gradeSubmission(Request $request, AssessmentSubmission $submission)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:' . ($submission->assessment->total_marks ?? 100),
            'feedback' => 'nullable|string',
            'passed' => 'boolean',
        ]);

        $submission->score = $validated['score'];
        $submission->percentage = $submission->assessment->total_marks > 0 
            ? ($validated['score'] / $submission->assessment->total_marks) * 100 
            : 0;
        $submission->passed = $request->boolean('passed', $submission->percentage >= $submission->assessment->passing_score);
        $submission->feedback = $validated['feedback'];
        $submission->graded_at = now();
        $submission->graded_by = auth()->id();
        $submission->status = 'graded';
        $submission->save();
        
        // Update assessment statistics
        $submission->assessment->calculateStatistics();

        return redirect()->route('admin.projects.submissions', $submission->assessment)
            ->with('success', 'Submission graded successfully!');
    }

    /**
     * Get modules for a specific course (AJAX).
     */
    public function getModulesByCourse($courseId)
    {
        $modules = CourseModule::where('course_id', $courseId)
            ->orderBy('module_number')
            ->get(['id', 'module_number', 'title']);
        
        return response()->json($modules);
    }
}
