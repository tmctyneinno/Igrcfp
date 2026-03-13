<?php
// app/Http/Controllers/Admin/AssessmentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\AssessmentQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssessmentController extends Controller
{
    /**
     * Show assessments filtered by type
     */
    public function index(Request $request, Course $course = null)
    {
        $type = $request->get('type', 'all');
        $query = Assessment::with('course', 'module');
        
        if ($course) {
            $query->where('course_id', $course->id);
        }
        
        switch ($type) {
            case 'quizzes':
                $query->quizzes();
                $title = 'Quizzes';
                break;
            case 'module':
                $query->moduleAssessments();
                $title = 'Module Assessments';
                break;
            case 'final':
                $query->finalExams();
                $title = 'Final Exams';
                break;
            case 'diploma':
                $query->diplomaAssessments();
                $title = 'Diploma Assessments';
                break;
            default:
                $title = 'All Assessments';
        }
        
        $assessments = $query->orderBy('created_at', 'desc')->paginate(15);
        $courses = Course::all();
        $modules = CourseModule::all();
        
        return view('admin.assessments.index', compact('assessments', 'courses', 'modules', 'title', 'course'));
    }

    /**
     * Show create form based on assessment type
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'quiz');
        $courses = Course::where('status', 'published')->orderBy('title')->get();
        $modules = CourseModule::orderBy('module_number')->get();
        
        return view('admin.assessments.create', compact('courses', 'modules', 'type'));
    }

    /**
     * Store assessment based on type
     */
    public function store(Request $request)
    {
        $rules = $this->getValidationRules($request->assessment_level);
        $validated = $request->validate($rules);

        // Set type-specific defaults
        $validated = $this->setTypeDefaults($validated, $request);

        // Handle file uploads
        if ($request->hasFile('assessment_file')) {
            $validated = $this->handleFileUpload($request, $validated);
        }

        // Create assessment
        $assessment = Assessment::create($validated);

        // If it's a quiz or module assessment with questions, process them
        if (in_array($request->assessment_level, ['quiz', 'module_assessment', 'final_exam']) 
            && $request->has('questions')) {
            $this->saveQuestions($assessment, $request->questions);
        }

        $redirectRoute = $this->getRedirectRoute($assessment);
        
        return redirect()->route($redirectRoute, $assessment->course_id)
            ->with('success', ucfirst($request->assessment_level) . ' created successfully!');
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
            'status' => 'required|in:draft,active,archived',
        ];

        switch ($type) {
            case 'quiz':
                return array_merge($baseRules, [
                    'total_marks' => 'required|integer|min:1|max:100',
                    'passing_score' => 'required|integer|min:1|max:100',
                    'questions' => 'required|array|min:5|max:10',
                    'questions.*.text' => 'required|string',
                    'questions.*.type' => 'required|in:multiple_choice,true_false',
                    'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array',
                    'questions.*.correct_answer' => 'required|string',
                    'questions.*.points' => 'required|integer|min:1',
                ]);

            case 'module_assessment':
                return array_merge($baseRules, [
                    'duration' => 'required|integer|min:15|max:120',
                    'total_marks' => 'required|integer|min:20|max:100',
                    'passing_score' => 'required|integer|min:50|max:100',
                    'is_timed' => 'boolean',
                    'requires_identity_verification' => 'boolean',
                    'questions' => 'required|array|min:20|max:30',
                ]);

            case 'final_exam':
                return array_merge($baseRules, [
                    'duration' => 'required|integer|min:60|max:180',
                    'total_marks' => 'required|integer|min:50|max:200',
                    'passing_score' => 'required|integer|min:60|max:100',
                    'is_timed' => 'boolean',
                    'requires_identity_verification' => 'accepted', // Must be true
                    'questions' => 'required|array|min:50',
                ]);

            case 'diploma':
                return array_merge($baseRules, [
                    'project_brief' => 'required|string',
                    'total_marks' => 'required|integer|min:50|max:200',
                    'passing_score' => 'required|integer|min:60|max:100',
                    'needs_manual_marking' => 'accepted', // Must be true
                    'requires_identity_verification' => 'accepted', // Must be true
                    'due_date' => 'required|date',
                    'assessment_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
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
                $validated['is_timed'] = false; // Projects aren't timed
                $validated['requires_identity_verification'] = true;
                $validated['needs_manual_marking'] = true;
                break;
        }

        return $validated;
    }

    /**
     * Save questions for quiz/exam type assessments
     */
    private function saveQuestions($assessment, $questions)
    {
        foreach ($questions as $index => $question) {
            AssessmentQuestion::create([
                'assessment_id' => $assessment->id,
                'question_text' => $question['text'],
                'question_type' => $question['type'],
                'options' => $question['options'] ?? null,
                'correct_answer' => $question['correct_answer'],
                'points' => $question['points'],
                'order' => $index + 1,
            ]);
        }
    }

    /**
     * Get redirect route based on assessment type
     */
    private function getRedirectRoute($assessment)
    {
        switch ($assessment->assessment_level) {
            case 'quiz':
                return 'admin.assessments.quizzes';
            case 'module_assessment':
                return 'admin.assessments.module';
            case 'final_exam':
                return 'admin.assessments.final';
            case 'diploma':
                return 'admin.assessments.diploma';
            default:
                return 'admin.assessments.course';
        }
    }
}