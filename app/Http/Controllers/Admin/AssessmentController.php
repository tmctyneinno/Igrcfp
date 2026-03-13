<?php
// app/Http/Controllers/Admin/AssessmentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\CourseModule; 
use App\Models\AssessmentQuestion;
use App\Models\AssessmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    /**
     * Display all assessments
     */
    public function index(Request $request)
    {
        $query = Assessment::with(['course', 'module'])
            ->orderBy('created_at', 'desc');

        // Filter by assessment level
        if ($request->has('level') && $request->level != '') {
            $query->where('assessment_level', $request->level);
        }

        // Filter by course
        if ($request->has('course_id') && $request->course_id != '') {
            $query->where('course_id', $request->course_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $assessments = $query->paginate(15);
        $courses = Course::orderBy('title')->get();
        
        // Statistics
        $statistics = [
            'total' => Assessment::count(),
            'quizzes' => Assessment::quizzes()->count(),
            'module_assessments' => Assessment::moduleAssessments()->count(),
            'final_exams' => Assessment::finalExams()->count(),
            'diploma' => Assessment::diplomaAssessments()->count(),
            'active' => Assessment::where('status', 'active')->count(),
            'submissions' => AssessmentSubmission::count(),
            'pending_grading' => AssessmentSubmission::where('status', 'submitted')->count(),
        ];

        return view('admin.courses.assessments.index', compact('assessments', 'courses', 'statistics'));
    }

    public function all(Request $request)
    {
        $query = Assessment::with(['course', 'module'])
            ->orderBy('created_at', 'desc');

        // Filter by assessment level
        if ($request->has('level') && $request->level != '') {
            $query->where('assessment_level', $request->level);
        }

        // Filter by course
        if ($request->has('course_id') && $request->course_id != '') {
            $query->where('course_id', $request->course_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $assessments = $query->paginate(15);
        $courses = Course::orderBy('title')->get();
        
        // Statistics
        $statistics = [
            'total' => Assessment::count(),
            'quizzes' => Assessment::quizzes()->count(),
            'module_assessments' => Assessment::moduleAssessments()->count(),
            'final_exams' => Assessment::finalExams()->count(),
            'diploma' => Assessment::diplomaAssessments()->count(),
            'active' => Assessment::where('status', 'active')->count(),
            'submissions' => AssessmentSubmission::count(),
            'pending_grading' => AssessmentSubmission::where('status', 'submitted')->count(),
        ];

        return view('admin.courses.assessments.index', compact('assessments', 'courses', 'statistics'));
    }

    public function createQuiz()
    {
        $courses = Course::where('status', 'published')->orderBy('title')->get();
        $modules = CourseModule::orderBy('module_number')->get();
        $type = 'quiz';
        
        return view('admin.assessments.create-quiz', compact('courses', 'modules', 'type'));
    }

    /**
     * Show assessments for a specific course
     */
    public function course(Course $course, Request $request)
    {
        $query = Assessment::where('course_id', $course->id)
            ->with('module')
            ->orderBy('assessment_level')
            ->orderBy('created_at', 'desc');

        if ($request->has('level') && $request->level != '') {
            $query->where('assessment_level', $request->level);
        }

        $assessments = $query->paginate(15);
        $courses = Course::orderBy('title')->get();
        
        $statistics = [
            'total' => Assessment::where('course_id', $course->id)->count(),
            'quizzes' => Assessment::where('course_id', $course->id)->quizzes()->count(),
            'module_assessments' => Assessment::where('course_id', $course->id)->moduleAssessments()->count(),
            'final_exams' => Assessment::where('course_id', $course->id)->finalExams()->count(),
            'diploma' => Assessment::where('course_id', $course->id)->diplomaAssessments()->count(),
            'active' => Assessment::where('course_id', $course->id)->where('status', 'active')->count(),
            'submissions' => AssessmentSubmission::whereHas('assessment', fn($q) => $q->where('course_id', $course->id))->count(),
        ];

        return view('admin.courses.assessments.index', compact('assessments', 'course', 'courses', 'statistics'));
    }

    /**
     * Show create form
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'quiz');
        $courses = Course::where('status', 'published')->orderBy('title')->get();
        $modules = CourseModule::orderBy('module_number')->get();
        
        return view('admin.courses.assessments.create', compact('courses', 'modules', 'type'));
    }

    /**
     * Store assessment
     */
    public function store(Request $request)
    {
         // Debug: Log all incoming data
        \Log::info('Assessment Store Request:', [
            'all_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'files' => $request->allFiles(),
        ]);
        $rules = $this->getValidationRules($request->assessment_level);
        $validated = $request->validate($rules);

        DB::beginTransaction();
        
        try {
            // Set type-specific defaults
            $validated = $this->setTypeDefaults($validated, $request);
            \Log::info('Validation Rules:', $rules);
            // Handle file upload
            if ($request->hasFile('assessment_file')) {
                $file = $request->file('assessment_file');
                $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('assessments/' . $validated['course_id'], $filename, 'public');
                
                $validated['file_path'] = $path;
                $validated['file_name'] = $file->getClientOriginalName();
                $validated['file_size'] = $file->getSize();
                $validated['file_extension'] = $file->getClientOriginalExtension();
            }

            // Handle project brief for diploma
            if ($request->has('project_brief')) {
                $validated['project_brief'] = $request->project_brief;
            }

            // Handle rubric for manual marking
            // if ($request->has('rubric')) {
            //     $validated['rubric'] = json_decode($request->rubric, true);
            // }

            // Create assessment
            $assessment = Assessment::create($validated);

            // Save questions for quiz/exam types
            if (in_array($request->assessment_level, ['quiz', 'module_assessment', 'final_exam']) 
                && $request->has('questions')) {
                $this->saveQuestions($assessment, $request->questions);
                $assessment->question_count = count($request->questions);
                $assessment->save();
            }

            DB::commit();

            $message = $this->getSuccessMessage($request->assessment_level);
            return redirect()->route('admin.assessments.course', $assessment->course_id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Assessment creation failed:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Error creating assessment: ' . $e->getMessage());
        }
    }

    /**
     * Show assessment details
     */
    public function show(Assessment $assessment)
    {
        $assessment->load(['course', 'module', 'questions', 'submissions.user' => function($q) {
            $q->latest();
        }]);

        return view('admin.assessments.show', compact('assessment'));
    }

    /**
     * Show edit form
     */
    public function edit(Assessment $assessment)
    {
        $courses = Course::orderBy('title')->get();
        $modules = CourseModule::orderBy('module_number')->get();
        
        return view('admin.assessments.edit', compact('assessment', 'courses', 'modules'));
    }

    /**
     * Update assessment
     */
    public function update(Request $request, Assessment $assessment)
    {
        $rules = $this->getValidationRules($assessment->assessment_level);
        $validated = $request->validate($rules);

        DB::beginTransaction();
        
        try {
            // Handle file upload
            if ($request->hasFile('assessment_file')) {
                // Delete old file
                if ($assessment->file_path) {
                    Storage::disk('public')->delete($assessment->file_path);
                }
                
                $file = $request->file('assessment_file');
                $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('assessments/' . $validated['course_id'], $filename, 'public');
                
                $validated['file_path'] = $path;
                $validated['file_name'] = $file->getClientOriginalName();
                $validated['file_size'] = $file->getSize();
                $validated['file_extension'] = $file->getClientOriginalExtension();
            }

            // Update assessment
            $assessment->update($validated);

            // Update questions if provided
            if ($request->has('questions')) {
                // Delete old questions
                $assessment->questions()->delete();
                $this->saveQuestions($assessment, $request->questions);
                $assessment->question_count = count($request->questions);
                $assessment->save();
            }

            DB::commit();

            return redirect()->route('admin.assessments.show', $assessment->id)
                ->with('success', 'Assessment updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating assessment: ' . $e->getMessage());
        }
    }

    /**
     * Delete assessment
     */
    public function destroy(Assessment $assessment)
    {
        DB::beginTransaction();
        
        try {
            // Delete file
            if ($assessment->file_path) {
                Storage::disk('public')->delete($assessment->file_path);
            }
            
            // Delete related data
            $assessment->questions()->delete();
            $assessment->submissions()->delete();
            $assessment->attempts()->delete();
            
            $courseId = $assessment->course_id;
            $assessment->delete();

            DB::commit();

            return redirect()->route('admin.assessments.course', $courseId)
                ->with('success', 'Assessment deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting assessment: ' . $e->getMessage());
        }
    }

    /**
     * Upload assessment from modal
     */
    public function upload(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assessment_level' => 'required|in:quiz,module_assessment,final_exam,diploma',
            'type' => 'required|in:exam,assignment,quiz,project',
            'status' => 'required|in:draft,active,archived',
            'duration' => 'nullable|integer|min:1',
            'total_marks' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:1|max:100',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable',
            'is_timed' => 'boolean',
            'requires_identity_verification' => 'boolean',
            'needs_manual_marking' => 'boolean',
            'assessment_file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,zip|max:51200',
            'project_brief' => 'required_if:assessment_level,diploma|nullable|string',
        ]);

        // Handle file upload
        $fileData = [];
        if ($request->hasFile('assessment_file')) {
            $file = $request->file('assessment_file');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('assessments/' . $course->id, $filename, 'public');
            
            $fileData = [
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_extension' => $file->getClientOriginalExtension(),
            ];
        }

        // Combine date and time
        if ($request->filled('due_date')) {
            $dueDateTime = $request->due_date;
            if ($request->filled('due_time')) {
                $dueDateTime .= ' ' . $request->due_time;
            }
            $validated['due_date'] = date('Y-m-d H:i:s', strtotime($dueDateTime));
        }

        // Set type-specific defaults
        $validated['course_id'] = $course->id;
        $validated = array_merge($validated, $this->setTypeDefaults($validated, $request), $fileData);

        Assessment::create($validated);

        return redirect()->route('admin.courses.assessments.course', $course->id)
            ->with('success', 'Assessment uploaded successfully!');
    }

    /**
     * View submissions for an assessment
     */
    public function submissions(Assessment $assessment)
    {
        $submissions = $assessment->submissions()
            ->with(['user', 'grader'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);

        return view('admin.courses.assessments.submissions', compact('assessment', 'submissions'));
    }

    /**
     * View single submission
     */
    public function viewSubmission(AssessmentSubmission $submission)
    {
        $submission->load(['user', 'assessment.course', 'assessment.questions', 'grader']);
        
        return view('admin.courses.assessments.submission', compact('submission'));
    }

    /**
     * Grade submission
     */
    public function gradeSubmission(Request $request, AssessmentSubmission $submission)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:' . ($submission->assessment->total_marks ?? 100),
            'feedback' => 'nullable|string',
        ]);

        $submission->markAsGraded(
            $validated['score'],
            $validated['feedback'],
            auth()->id()
        );

        return redirect()->route('admin.courses.assessments.submissions', $submission->assessment_id)
            ->with('success', 'Submission graded successfully!');
    }

    /**
     * Get validation rules based on assessment type
     */
    private function getValidationRules($type)
{
    $baseRules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'course_id' => 'required|exists:courses,id',
        'module_id' => 'nullable|exists:course_modules,id',
        'assessment_level' => 'required|in:quiz,module_assessment,final_exam,diploma',
        'type' => 'required|in:exam,assignment,quiz,project',
        'status' => 'required|in:draft,active,archived',
    ];

    switch ($type) {
        case 'quiz':
            return array_merge($baseRules, [
                'total_marks' => 'nullable|integer|min:1|max:100',
                'passing_score' => 'nullable|integer|min:1|max:100',
                'questions' => 'nullable|array',
                'questions.*.text' => 'required_with:questions|string',
                'questions.*.type' => 'required_with:questions|string',
                'questions.*.points' => 'required_with:questions|integer|min:1',
                'questions.*.options' => 'nullable|array',
                'questions.*.correct_answer' => 'nullable|string', // Changed to nullable
            ]);

        case 'module_assessment':
            return array_merge($baseRules, [
                'duration' => 'nullable|integer|min:15|max:120',
                'total_marks' => 'nullable|integer|min:20|max:100',
                'passing_score' => 'nullable|integer|min:50|max:100',
                'is_timed' => 'sometimes|boolean',
                'requires_identity_verification' => 'sometimes|boolean',
                'questions' => 'nullable|array',
                'questions.*.text' => 'required_with:questions|string',
                'questions.*.type' => 'required_with:questions|string',
                'questions.*.points' => 'required_with:questions|integer|min:1',
            ]);

        case 'final_exam':
            return array_merge($baseRules, [
                'duration' => 'nullable|integer|min:60|max:180',
                'total_marks' => 'nullable|integer|min:50|max:200',
                'passing_score' => 'nullable|integer|min:60|max:100',
                'is_timed' => 'sometimes|boolean',
                'requires_identity_verification' => 'sometimes|boolean',
                'questions' => 'nullable|array',
                'questions.*.text' => 'required_with:questions|string',
                'questions.*.type' => 'required_with:questions|string',
                'questions.*.points' => 'required_with:questions|integer|min:1',
            ]);

        case 'diploma':
            return array_merge($baseRules, [
                'project_brief' => 'required|string',
                'total_marks' => 'nullable|integer|min:50|max:200',
                'passing_score' => 'nullable|integer|min:60|max:100',
                'needs_manual_marking' => 'sometimes|boolean',
                'requires_identity_verification' => 'sometimes|boolean',
                'due_date' => 'nullable|date',
                // 'rubric' => 'nullable|json',
            ]);

        default:
            return $baseRules;
    }
}

    /**
     * Set type-specific default values
     */
    private function setTypeDefaults($validated, $request)
    {
        switch ($request->assessment_level) {
            case 'quiz':
                $validated['is_timed'] = false;
                $validated['requires_identity_verification'] = false;
                $validated['needs_manual_marking'] = false;
                $validated['duration'] = null;
                break;

            case 'module_assessment':
                $validated['is_timed'] = $request->has('is_timed');
                $validated['requires_identity_verification'] = $request->has('requires_identity_verification');
                $validated['needs_manual_marking'] = false;
                break;

            case 'final_exam':
                $validated['is_timed'] = true;
                $validated['requires_identity_verification'] = true;
                $validated['needs_manual_marking'] = false;
                break;

            case 'diploma':
                $validated['is_timed'] = false;
                $validated['requires_identity_verification'] = true;
                $validated['needs_manual_marking'] = true;
                break;
        }

        return $validated;
    }

    /**
     * Save questions for assessment
     */
    private function saveQuestions($assessment, $questions)
    {
        foreach ($questions as $index => $question) {
            AssessmentQuestion::create([
                'assessment_id' => $assessment->id,
                'question_text' => $question['text'],
                'question_type' => $question['type'],
                'options' => $question['options'] ?? null,
                'correct_answer' => $question['correct_answer'] ?? null,
                'points' => $question['points'] ?? 1,
                'order' => $index + 1,
                'difficulty_level' => $question['difficulty'] ?? 'medium',
                'explanation' => $question['explanation'] ?? null,
            ]);
        }
    }

    /**
     * Get success message based on assessment type
     */
    private function getSuccessMessage($type)
    {
        $messages = [
            'quiz' => 'Quiz created successfully!',
            'module_assessment' => 'Module assessment created successfully!',
            'final_exam' => 'Final exam created successfully!',
            'diploma' => 'Diploma assessment created successfully!',
        ];

        return $messages[$type] ?? 'Assessment created successfully!';
    }

    /**
     * Bulk delete assessments
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'assessment_ids' => 'required|array',
            'assessment_ids.*' => 'exists:assessments,id'
        ]);

        DB::beginTransaction();
        
        try {
            foreach ($request->assessment_ids as $id) {
                $assessment = Assessment::find($id);
                if ($assessment) {
                    // Delete file
                    if ($assessment->file_path) {
                        Storage::disk('public')->delete($assessment->file_path);
                    }
                    $assessment->delete();
                }
            }

            DB::commit();

            return redirect()->back()->with('success', count($request->assessment_ids) . ' assessments deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting assessments: ' . $e->getMessage());
        }
    }
}