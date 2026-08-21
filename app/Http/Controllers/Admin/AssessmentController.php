<?php

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
use App\Services\BrevoMailService;
use App\Mail\EssayRetryNotification;
use Barryvdh\DomPDF\Facade\Pdf;

 
class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Assessment::with(['course', 'module'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('level'))     $query->where('assessment_level', $request->level);
        if ($request->filled('course_id')) $query->where('course_id', $request->course_id);
        if ($request->filled('status'))    $query->where('status', $request->status);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('title', 'LIKE', "%{$search}%")
                                      ->orWhere('description', 'LIKE', "%{$search}%"));
        }

        $assessments = $query->paginate(20);
        $courses     = Course::orderBy('title')->get();
        $statistics  = $this->getStatistics();

        return view('admin.courses.assessments.index',
            compact('assessments', 'courses', 'statistics'));
    }

    public function submissionsList(Request $request)
    {
        $query = AssessmentSubmission::with([
            'user', 
            'assessment.course', 
            'enrollment',
            'grader',
            'assessment.questions'
        ])
        ->orderBy('submitted_at', 'desc');

        // --- Search and Filter Logic ---

        // Filter by Status (Submitted/Pending vs Graded)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Course
        if ($request->filled('course_id')) {
            $query->whereHas('assessment', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        // Search by Student Name or Email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $submissions = $query->paginate(20);

        // Transform data to add Stage and Grade info
        $submissions->getCollection()->transform(function ($submission) {
            // 1. Determine Stage (Quiz/Essay/etc)
            $submission->current_stage = $this->determineAssessmentStage($submission);
            
            // 2. Calculate Grade Label based on Score
            $submission->grade_info = $this->calculateGradeLabel($submission->percentage);
            $submission->score_breakdown = $this->getScoreBreakdown($submission);

            $enrollment = $submission->enrollment;
            $submission->quiz_lock_info = [
                'failed_attempts' => (int) ($enrollment?->quiz_failed_attempts ?? 0),
                'locked_until' => $enrollment?->quiz_locked_until,
                'permanently_locked' => (bool) ($enrollment?->quiz_permanently_locked ?? false),
            ];
            
            return $submission;
        });

        $statistics = [
            'total'     => AssessmentSubmission::count(),
            'pending'   => AssessmentSubmission::where('status', 'submitted')->count(),
            'graded'    => AssessmentSubmission::where('status', 'graded')->count(),
            'avg_score' => AssessmentSubmission::whereNotNull('percentage')->avg('percentage'),
        ];

        // Get courses for filter dropdown
        $courses = \App\Models\Course::orderBy('title')->get();

        return view('admin.courses.assessments.submissions-list', compact('submissions', 'statistics', 'courses'));
    }

    /**
     * Allow an administrator to give a learner a fresh set of course quiz attempts.
     */
    public function resetRetryLock(AssessmentSubmission $submission)
    {
        $enrollment = $submission->enrollment;

        if (!$enrollment) {
            return back()->with('error', 'This submission is not linked to an enrollment, so its retry lock cannot be reset.');
        }

        $enrollment->update([
            'quiz_failed_attempts' => 0,
            'quiz_locked_until' => null,
            'quiz_permanently_locked' => false,
        ]);

        AssessmentSubmission::where('enrollment_id', $enrollment->id)
            ->update(['locked_until' => null]);

        return back()->with('success', "Retry lock reset for {$enrollment->user->name}. The learner can retake this course now.");
    }

    /**
     * Helper to determine the stage based on ACTUAL submission content
     */
    private function determineAssessmentStage($submission)
    {
        $questions = $submission->assessment->questions ?? collect();
        
        $hasMcqQuestions = $questions->contains('question_type', 'mcq');
        $hasEssayQuestions = $questions->contains('question_type', 'essay');

        if ($questions->isEmpty()) {
            return 'Unknown';
        }

        $submittedAnswers = $submission->answers ?? []; 
        
        if ($submittedAnswers instanceof \Illuminate\Support\Collection) {
            $submittedAnswers = $submittedAnswers->toArray();
        }

        // A submission cannot progress beyond the quiz stage until it has a
        // passing result. A score of 49% or lower is always a failed quiz.
        $hasPassed = $submission->passed
            && $submission->percentage !== null
            && (float) $submission->percentage >= 50;

        if (!$hasPassed) {
            return 'Quiz Stage';
        }

        $essayAnswered = false;
        foreach ($submittedAnswers as $answer) {
            $type = $answer['question_type'] ?? ($answer['type'] ?? null);
            $content = $answer['answer'] ?? ($answer['response'] ?? '');
            
            if ($type === 'essay' && !empty(trim(strip_tags($content)))) {
                $essayAnswered = true;
                break;
            }
        }

        if ($hasEssayQuestions && !$essayAnswered) {
            return 'Ready for Essay';
        }
        
        if ($hasEssayQuestions && $essayAnswered) {
            if (in_array($submission->status, ['submitted', 'in_progress'], true)) {
                return 'Essay Under Review';
            }

            return $submission->status === 'graded' ? 'Completed' : 'Essay Stage';
        }
        
        if ($hasMcqQuestions && !$hasEssayQuestions) {
            return 'Quiz Passed';
        }

        return 'Completed';           
    }

    /**
     * Calculate Grade Label based on Percentage
     * Distinction: 75%–100%
     * Pass: 50%–74%
     * Refer/Retake: 40%–49%
     * Fail: 0%–39%
     */
    private function calculateGradeLabel(?float $percentage): array
    {
        // If no score exists yet
        if ($percentage === null) {
            return ['label' => 'Pending', 'class' => 'secondary'];
        }

        // Ensure it's a float
        $score = (float) $percentage;

        if ($score >= 75) {
            return ['label' => 'Distinction', 'class' => 'success']; // Green
        } 
        elseif ($score >= 50) {
            return ['label' => 'Pass', 'class' => 'primary']; // Blue
        } 
        elseif ($score >= 40) {
            return ['label' => 'Refer / Retake', 'class' => 'warning']; // Orange/Yellow
        } 
        else {
            // Covers 0% to 39.9%
            return ['label' => 'Fail', 'class' => 'danger']; // Red
        }
    }

    /**
     * Separate automatically graded quiz marks from manually graded essay marks.
     * The final score is the aggregate of the two parts.
     */
    private function getScoreBreakdown(AssessmentSubmission $submission): array
    {
        $quizEarned = 0.0;
        $quizTotal = 0.0;
        $essayTotal = 0.0;
        $responses = $submission->question_responses ?? [];

        foreach ($submission->assessment->questions ?? [] as $question) {
            $points = (float) ($question->points ?? 0);
            $response = $responses[$question->id] ?? [];
            $isManual = in_array($question->question_type, ['essay', 'case_study'], true);

            if ($isManual) {
                $essayTotal += $points;
                continue;
            }

            $quizTotal += $points;
            $earned = $response['points_earned'] ?? null;
            if ($earned === null) {
                $earned = $question->isAnswerCorrect($response['answer'] ?? null) ? $points : 0;
            }
            $quizEarned += (float) $earned;
        }

        $storedBreakdown = $submission->grader_comments['score_breakdown'] ?? [];
        $finalEarned = $submission->status === 'graded' && $submission->score !== null
            ? (float) $submission->score
            : ($essayTotal == 0 && $submission->score !== null ? (float) $submission->score : null);
        $essayEarned = $storedBreakdown['essay_earned'] ?? null;

        if ($essayEarned === null && $finalEarned !== null && $essayTotal > 0) {
            $essayEarned = max(0, min($essayTotal, $finalEarned - $quizEarned));
        }

        return [
            'quiz_earned' => $quizEarned,
            'quiz_total' => $quizTotal,
            'essay_earned' => $essayEarned === null ? null : (float) $essayEarned,
            'essay_total' => $essayTotal,
            'final_earned' => $finalEarned,
            'final_total' => (float) ($submission->assessment->total_marks ?: ($quizTotal + $essayTotal)),
        ];
    }

    


   
    public function notifyEssayRetry(Request $request, AssessmentSubmission $submission, BrevoMailService $mailer)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:assessment_questions,id',
            'subject'     => 'required|string|max:255',
            'message'     => 'required|string',
        ]);

        if (empty($submission->user->email)) {
            return redirect()->back()->with('error', 'This student has no email address on file.');
        }

        try {
            $mailable = new EssayRetryNotification($validated['subject'], $validated['message']);

            $mailer->sendMailable(
                $submission->user->email,
                // 'eshanokpe@gmail.com',
                $mailable,
                $validated['subject']
            );

            return redirect()->back()->with('success', 'Notification email sent to student successfully!');
        } catch (\Exception $e) {
            \Log::error('Essay retry notification failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error sending email: ' . $e->getMessage());
        }
    }

    public function deleteSubmission(AssessmentSubmission $submission)
    {
        DB::beginTransaction();
        try {
            // If submissions can have uploaded files, clean those up too
            $responses = $submission->question_responses ?? [];
            foreach ($responses as $response) {
                if (!empty($response['uploaded_file']['path'])) {
                    Storage::disk('public')->delete($response['uploaded_file']['path']);
                }
            }

            $submission->delete();
            DB::commit();

            return redirect()->back()->with('success', 'Submission deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Submission deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting submission: ' . $e->getMessage());
        }
    }

    public function all(Request $request)
    {
        $query = Assessment::with(['course', 'module'])
            ->withCount(['questions as essay_questions_count' => fn($q) => $q->where('question_type', 'essay')])
            ->orderBy('created_at', 'desc');

        if ($request->filled('level'))     $query->where('assessment_level', $request->level);
        if ($request->filled('course_id')) $query->where('course_id', $request->course_id);
        if ($request->filled('status'))    $query->where('status', $request->status);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('title', 'LIKE', "%{$search}%")
                                      ->orWhere('description', 'LIKE', "%{$search}%"));
        }

        $assessments = $query->paginate(20);
        $courses     = Course::orderBy('title')->get();
        $statistics  = $this->getStatistics();

        return view('admin.courses.assessments.index',
            compact('assessments', 'courses', 'statistics'));
    }

    public function quizzes(Request $request)
    {
        $query = Assessment::with(['course', 'module'])
            ->withCount(['questions as essay_questions_count' => fn($q) => $q->where('question_type', 'essay')])
            ->where('assessment_level', 'quiz')
            ->orderBy('created_at', 'desc');

        if ($request->filled('course_id')) $query->where('course_id', $request->course_id);
        if ($request->filled('status'))    $query->where('status', $request->status);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('title', 'LIKE', "%{$search}%")
                                      ->orWhere('description', 'LIKE', "%{$search}%"));
        }

        $assessments = $query->paginate(20);
        $courses     = Course::orderBy('title')->get();
        $statistics  = $this->getStatistics();

        return view('admin.courses.assessments.index',
            compact('assessments', 'courses', 'statistics'));
    }

    private function getStatistics($courseId = null): array
    {
        $base = $courseId ? Assessment::where('course_id', $courseId) : new Assessment;

        return [
            'total'              => Assessment::when($courseId, fn($q) => $q->where('course_id', $courseId))->count(),
            'quizzes'            => Assessment::when($courseId, fn($q) => $q->where('course_id', $courseId))->quizzes()->count(),
            'module_assessments' => Assessment::when($courseId, fn($q) => $q->where('course_id', $courseId))->moduleAssessments()->count(),
            'final_exams'        => Assessment::when($courseId, fn($q) => $q->where('course_id', $courseId))->finalExams()->count(),
            'diploma'            => Assessment::when($courseId, fn($q) => $q->where('course_id', $courseId))->diplomaAssessments()->count(),
            'active'             => Assessment::when($courseId, fn($q) => $q->where('course_id', $courseId))->where('status', 'active')->count(),
            'submissions'        => $courseId
                ? AssessmentSubmission::whereHas('assessment', fn($q) => $q->where('course_id', $courseId))->count()
                : AssessmentSubmission::count(),
            'pending_grading'    => AssessmentSubmission::where('status', 'submitted')->count(),
        ];
    }

    public function createQuiz()
    {
        $courses = Course::orderBy('title')->get();
        $modules = collect();
        $type    = 'quiz';
        return view('admin.courses.assessments.create-quiz',
            compact('courses', 'modules', 'type'));
    }

    public function getModulesByCourse($courseId)
    {
        $modules = CourseModule::where('course_id', $courseId)
            ->orderBy('module_number')
            ->get(['id', 'module_number', 'title']);
        return response()->json($modules);
    }

    public function createModuleAssessment()
    {
        $courses = Course::where('status', 'published')->orderBy('title')->get();
        $modules = CourseModule::orderBy('module_number')->get();
        $type    = 'module_assessment';
        return view('admin.courses.assessments.create-module',
            compact('courses', 'modules', 'type'));
    }

    public function createFinalExam()
    {
        $courses = Course::where('status', 'published')->orderBy('title')->get();
        $modules = CourseModule::orderBy('module_number')->get();
        $type    = 'final_exam';
        return view('admin.courses.assessments.create-final',
            compact('courses', 'modules', 'type'));
    }

    public function createDiploma()
    {
        $courses = Course::where('status', 'published')->orderBy('title')->get();
        $modules = CourseModule::orderBy('module_number')->get();
        $type    = 'diploma';
        return view('admin.courses.assessments.create-diploma',
            compact('courses', 'modules', 'type'));
    }

    public function course(Course $course, Request $request)
    {
        $query = Assessment::where('course_id', $course->id)
            ->with('module')
            ->withCount(['questions as essay_questions_count' => fn($q) => $q->where('question_type', 'essay')])
            ->orderBy('assessment_level')
            ->orderBy('created_at', 'desc');

        if ($request->filled('level')) $query->where('assessment_level', $request->level);

        $assessments = $query->paginate(20);
        $courses     = Course::orderBy('title')->get();
        $statistics  = $this->getStatistics($course->id);

        return view('admin.courses.assessments.index',
            compact('assessments', 'course', 'courses', 'statistics'));
    }

    public function store(Request $request)
    {
        $rules     = $this->getValidationRules($request->assessment_level);
        $validated = $request->validate($rules);

        try {
            $validated = $this->setTypeDefaults($validated, $request);

            if ($request->hasFile('assessment_file')) {
                $file     = $request->file('assessment_file');
                $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
                $path     = $file->storeAs('assessments/' . $validated['course_id'], $filename, 'public');
                $validated['file_path']      = $path;
                $validated['file_name']      = $file->getClientOriginalName();
                $validated['file_size']      = $file->getSize();
                $validated['file_extension'] = $file->getClientOriginalExtension();
            }

            if ($request->has('project_brief')) $validated['project_brief'] = $request->project_brief;
            if ($request->has('rubric'))        $validated['rubric'] = json_decode($request->rubric, true);

            if ($request->assessment_level === 'quiz') {
                $exists = Assessment::where('course_id', $validated['course_id'])
                    ->where('assessment_level', 'quiz')
                    ->exists();

                if ($exists) {
                    return back()->withInput()
                        ->with('error', 'A quiz already exists for this course. Only one quiz section is allowed per course.');
                }
            }

            $validated['has_essay'] = collect($request->questions ?? [])
                ->contains(fn($question) => ($question['type'] ?? null) === 'essay');

            $assessment = Assessment::create($validated);

            if (in_array($request->assessment_level, ['quiz', 'module_assessment', 'final_exam'])
                && $request->has('questions')) {
                $this->saveQuestions($assessment, $request->questions);
                $assessment->question_count = count($request->questions);
                $assessment->save();
            }

            return redirect()->route('admin.assessments.all')
                ->with('success', 'Created successfully');

        } catch (\Exception $e) {
            \Log::error('Assessment creation failed: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error creating assessment: ' . $e->getMessage());
        }
    }

    public function show(Assessment $assessment)
    {
        $assessment->load(['course', 'module', 'questions', 'submissions.user' => fn($q) => $q->latest()]);
        return view('admin.courses.assessments.show', compact('assessment'));
    }

    public function edit(Assessment $assessment)
    {
        $courses = Course::orderBy('title')->get();
        $modules = CourseModule::orderBy('module_number')->get();

        $questions = $assessment->questions->sortBy('order')->values();

        $questionsData = $questions
            ->where('question_type', '!=', 'essay')
            ->values()
            ->map(fn($q) => [
                'text'           => $q->question_text,
                'type'           => $q->question_type,
                'points'         => $q->points,
                'difficulty'     => $q->difficulty_level ?? 'medium',
                'options'        => $q->options ?? [],
                'correct_answer' => $q->correct_answer,
                'explanation'    => $q->explanation,
            ]);

        $essayQuestionsData = $questions
            ->where('question_type', 'essay')
            ->values()
            ->map(fn($q) => [
                'text'           => $q->question_text,
                'type'           => $q->question_type,
                'points'         => $q->points,
                'difficulty'     => $q->difficulty_level ?? 'medium',
                'options'        => $q->options ?? [],
                'correct_answer' => $q->correct_answer,
                'explanation'    => $q->explanation,
            ]);

        if ($assessment->assessment_level === 'quiz') {
            return view('admin.courses.assessments.edit-quiz',
                compact('assessment', 'courses', 'modules', 'questionsData', 'essayQuestionsData'));
        }

        return view('admin.courses.assessments.projects.edit',
            compact('assessment', 'courses', 'modules', 'questionsData'));
    }

    public function update(Request $request, Assessment $assessment)
    {
        $rules     = $this->getValidationRules($assessment->assessment_level);
        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            if ($validated['assessment_level'] === 'quiz') {
                $exists = Assessment::where('course_id', $validated['course_id'])
                    ->where('assessment_level', 'quiz')
                    ->where('id', '!=', $assessment->id)
                    ->exists();

                if ($exists) {
                    return back()->withInput()
                        ->with('error', 'A quiz already exists for this course. Only one quiz section is allowed per course.');
                }
            }

            $assessment->title         = $validated['title'];
            $assessment->description   = $validated['description']   ?? $assessment->description;
            $assessment->course_id     = $validated['course_id'];
            $assessment->module_id     = $validated['module_id']     ?? null;
            $assessment->status        = $validated['status'];
            $assessment->duration      = $validated['duration']      ?? $assessment->duration;
            $assessment->total_marks   = $validated['total_marks']   ?? $assessment->total_marks;
            $assessment->passing_score = $validated['passing_score'] ?? $assessment->passing_score;
            $assessment->weight        = $validated['weight']        ?? $assessment->weight;

            if ($request->hasFile('assessment_file')) {
                if ($assessment->file_path) {
                    Storage::disk('public')->delete($assessment->file_path);
                }
                $file     = $request->file('assessment_file');
                $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
                $path     = $file->storeAs('assessments/' . $validated['course_id'], $filename, 'public');
                $assessment->file_path      = $path;
                $assessment->file_name      = $file->getClientOriginalName();
                $assessment->file_size      = $file->getSize();
                $assessment->file_extension = $file->getClientOriginalExtension();
            }

            if ($request->boolean('remove_file') && $assessment->file_path) {
                Storage::disk('public')->delete($assessment->file_path);
                $assessment->file_path      = null;
                $assessment->file_name      = null;
                $assessment->file_size      = null;
                $assessment->file_extension = null;
            }

            $assessment->save();

            if ($request->has('questions')) {
                DB::table('assessment_questions')
                    ->where('assessment_id', $assessment->id)
                    ->delete();

                $this->saveQuestions($assessment, $request->questions);
                $assessment->question_count = count($request->questions);
                $assessment->has_essay = collect($request->questions)
                    ->contains(fn($question) => ($question['type'] ?? null) === 'essay');
                $assessment->save();
            }

            DB::commit();

            return redirect()->route('admin.assessments.show', $assessment->id)
                ->with('success', 'Quiz updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Assessment update failed: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return back()->withInput()
                ->with('error', 'Error updating: ' . $e->getMessage());
        }
    }

    public function destroy(Assessment $assessment)
    {
        DB::beginTransaction();
        try {
            if ($assessment->file_path) {
                Storage::disk('public')->delete($assessment->file_path);
            }
            DB::table('assessment_questions')->where('assessment_id', $assessment->id)->delete();
            $assessment->submissions()->delete();
            $assessment->delete();
            DB::commit();
            return redirect()->route('admin.assessments.all')
                ->with('success', 'Assessment deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Assessment deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Error deleting assessment: ' . $e->getMessage());
        }
    }

    public function upload(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title'                          => 'required|string|max:255',
            'description'                    => 'nullable|string',
            'assessment_level'               => 'required|in:quiz,module_assessment,final_exam,diploma',
            'type'                           => 'required|in:exam,assignment,quiz,project',
            'status'                         => 'required|in:draft,active,archived',
            'duration'                       => 'nullable|integer|min:1',
            'total_marks'                    => 'nullable|integer|min:1',
            'passing_score'                  => 'nullable|integer|min:1|max:100',
            'due_date'                       => 'nullable|date',
            'due_time'                       => 'nullable',
            'is_timed'                       => 'boolean',
            'requires_identity_verification' => 'boolean',
            'needs_manual_marking'           => 'boolean',
            'assessment_file'                => 'nullable|file|mimes:pdf,doc,docx,xlsx,zip|max:51200',
            'project_brief'                  => 'required_if:assessment_level,diploma|nullable|string',
        ]);

        $fileData = [];
        if ($request->hasFile('assessment_file')) {
            $file     = $request->file('assessment_file');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs('assessments/' . $course->id, $filename, 'public');
            $fileData = [
                'file_path'      => $path,
                'file_name'      => $file->getClientOriginalName(),
                'file_size'      => $file->getSize(),
                'file_extension' => $file->getClientOriginalExtension(),
            ];
        }

        if ($request->filled('due_date')) {
            $dueDateTime = $request->due_date;
            if ($request->filled('due_time')) $dueDateTime .= ' ' . $request->due_time;
            $validated['due_date'] = date('Y-m-d H:i:s', strtotime($dueDateTime));
        }

        $validated['course_id'] = $course->id;
        $validated = array_merge($validated, $this->setTypeDefaults($validated, $request), $fileData);
        Assessment::create($validated);

        return redirect()->route('admin.assessments.course', $course->id)
            ->with('success', 'Assessment uploaded successfully!');
    }

    public function submissions(Assessment $assessment)
    {
        $submissions = $assessment->submissions()
            ->with(['user', 'grader'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);
        return view('admin.courses.assessments.submissions',
            compact('assessment', 'submissions'));
    }
 
       public function viewSubmission($encodedId)
    {
        // 1. Decode the ID using the Hashids trait
        $id = AssessmentSubmission::decodeId($encodedId);
    
        if (!$id) {
            abort(404, 'Invalid submission link.');
        }

        // 2. Fetch the submission with necessary relationships
        $submission = AssessmentSubmission::with([
            'user', 
            'assessment.course', 
            'assessment.questions', 
            'grader'
        ])->findOrFail($id);
        
        $assessment = $submission->assessment;
        $responses = $submission->question_responses ?? [];
        
        // 3. Categorize Questions & Calculate Auto-Score
        $mcqQuestions = collect();
        $essayQuestions = collect();
        $projectQuestions = collect(); 
        
        $autoScore = 0;       // Raw points earned from MCQs
        $mcqTotalPoints = 0;  // Total possible points from MCQs
        
        foreach ($assessment->questions as $question) {
            $response = $responses[$question->id] ?? null;
            $points = $question->points ?? 0;
            
            $item = [
                'question' => $question,
                'response' => $response,
                'max_points' => $points
            ];

            // Calculate Auto-Score for MCQs/Short Answers
            if (in_array($question->question_type, ['multiple_choice', 'true_false', 'short_answer'])) {
                $mcqTotalPoints += $points;
                if (($response['correct'] ?? false)) {
                    $autoScore += $points;
                }
                $mcqQuestions->push($item);
                
            } elseif ($question->question_type === 'essay') {
                $essayQuestions->push($item);
            } else {
                $projectQuestions->push($item);
            }
        }
 
        // 4. Extract Uploaded Files for easy access
        $uploadedFiles = collect($responses)
            ->filter(fn($r) => isset($r['uploaded_file']['path']) && !empty($r['uploaded_file']['path']))
            ->map(fn($r) => $r['uploaded_file']);

        // 5. Return View with Correct Data
        return view('admin.courses.assessments.submission', compact(
            'submission', 
            'assessment', 
            'mcqQuestions', 
            'essayQuestions', 
            'projectQuestions', 
            'uploadedFiles',
            'autoScore',      // Pass raw MCQ points (e.g., 15)
            'mcqTotalPoints'  // Pass total possible MCQ points (e.g., 20)
        ));
    }

    public function gradeSubmission(Request $request, AssessmentSubmission $submission)
    {
        $validated = $request->validate([
            'final_score' => 'required|numeric|min:0|max:' . ($submission->assessment->total_marks ?? 100),
            'feedback'    => 'nullable|string',
            'essay_scores' => 'nullable|array',
            'essay_scores.*' => 'nullable|numeric|min:0',
            'examiner_report' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $submission->loadMissing('assessment.questions');
        $breakdown = $this->getScoreBreakdown($submission);
        $essayScores = $validated['essay_scores'] ?? [];
        $essayEarned = 0.0;

        foreach ($submission->assessment->questions as $question) {
            if (!in_array($question->question_type, ['essay', 'case_study'], true)) {
                continue;
            }

            $awarded = (float) ($essayScores[$question->id] ?? 0);
            if ($awarded > (float) $question->points) {
                return back()->withErrors([
                    "essay_scores.{$question->id}" => 'Essay marks cannot exceed the question maximum.',
                ])->withInput();
            }
            $essayEarned += $awarded;
        }

        // The final result is always the aggregate of the auto-graded quiz
        // marks and the administrator's essay marks.
        $finalScore = $breakdown['quiz_earned'] + $essayEarned;

        // Save the final score
        $submission->score = $finalScore;
        
        // Recalculate percentage based on total marks
        $totalMarks = $submission->assessment->total_marks ?? 100;
        $submission->percentage = ($finalScore / $totalMarks) * 100;
        
        $submission->passed = $submission->percentage >= ($submission->assessment->passing_score ?? 50);
        $submission->status = 'graded';
        $submission->feedback = $validated['feedback'];
        $submission->grader_id = auth()->id();
        $submission->graded_at = now();

        if ($request->hasFile('examiner_report')) {
            if ($submission->examiner_report_path) {
                Storage::disk('public')->delete($submission->examiner_report_path);
            }

            $report = $request->file('examiner_report');
            $submission->examiner_report_path = $report->store('examiner-reports', 'public');
            $submission->examiner_report_name = $report->getClientOriginalName();
        }

        $submission->grader_comments = array_merge($submission->grader_comments ?? [], [
            'score_breakdown' => [
                'quiz_earned' => $breakdown['quiz_earned'],
                'quiz_total' => $breakdown['quiz_total'],
                'essay_scores' => $essayScores,
                'essay_earned' => $essayEarned,
                'essay_total' => $breakdown['essay_total'],
                'final_earned' => $finalScore,
                'final_total' => $totalMarks,
            ],
        ]);
        
        $submission->save();

        return redirect()->back()->with('success', 'Submission graded successfully!');
    }


    public function examinerReport(Request $request, AssessmentSubmission $submission)
    {
        if (!$submission->examiner_report_path) {
            abort(404, 'Examiner report not found.');
        }

        $disk = Storage::disk('public');
        if (!$disk->exists($submission->examiner_report_path)) {
            abort(404, 'Examiner report file not found.');
        }

        if ($request->boolean('download')) {
            return $disk->download(
                $submission->examiner_report_path,
                $submission->examiner_report_name ?: 'examiner-report'
            );
        }

        return response()->file($disk->path($submission->examiner_report_path), [
            'Content-Type' => $disk->mimeType($submission->examiner_report_path) ?: 'application/octet-stream',
        ]);
    }

    public function exportSubmissionPdf($encodedId)
    {
        // Decode ID (using your existing Hashids logic)
        $id = AssessmentSubmission::decodeId($encodedId);
        if (!$id) abort(404);

        $submission = AssessmentSubmission::with([
            'user', 
            'assessment.course', 
            'assessment.questions', 
            'grader'
        ])->findOrFail($id);
        
        $assessment = $submission->assessment;
        $responses = $submission->question_responses ?? [];
        
        // Re-use your categorization logic
        $mcqQuestions = collect();
        $essayQuestions = collect();
        $projectQuestions = collect(); 
        
        foreach ($assessment->questions as $question) {
            $response = $responses[$question->id] ?? null;
            $item = ['question' => $question, 'response' => $response];

            if (in_array($question->question_type, ['multiple_choice', 'true_false', 'short_answer'])) {
                $mcqQuestions->push($item);
            } elseif ($question->question_type === 'essay') {
                $essayQuestions->push($item);
            } else {
                $projectQuestions->push($item);
            }
        }

        $uploadedFiles = collect($responses)
            ->filter(fn($r) => isset($r['uploaded_file']['path']) && !empty($r['uploaded_file']['path']))
            ->map(fn($r) => $r['uploaded_file']);

        // Load the PDF-specific view
        $pdf = Pdf::loadView('admin.courses.assessments.submission-pdf', compact(
            'submission', 'assessment', 'mcqQuestions', 'essayQuestions', 'projectQuestions', 'uploadedFiles'
        ));

        // Professional PDF Settings
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('Submission_' . $submission->user->name . '_' . $assessment->slug . '.pdf');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'assessment_ids'   => 'required|array',
            'assessment_ids.*' => 'exists:assessments,id',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->assessment_ids as $id) {
                $assessment = Assessment::find($id);
                if ($assessment) {
                    if ($assessment->file_path) {
                        Storage::disk('public')->delete($assessment->file_path);
                    }
                    $assessment->delete();
                }
            }
            DB::commit();
            return redirect()->back()
                ->with('success', count($request->assessment_ids) . ' assessments deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting assessments: ' . $e->getMessage());
        }
    }

    private function getValidationRules($type): array
    {
        $baseRules = [
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'course_id'        => 'required|exists:courses,id',
            'module_id'        => 'nullable|exists:course_modules,id',
            'assessment_level' => 'required|in:quiz,module_assessment,final_exam,diploma',
            'type'             => 'required|in:exam,assignment,quiz,project',
            'status'           => 'required|in:draft,active,archived',
        ];

        switch ($type) {
            case 'quiz':
                return array_merge($baseRules, [
                    'duration'                   => 'required|integer|min:1|max:180',
                    'total_marks'                => 'nullable|integer|min:1|max:100',
                    'passing_score'              => 'nullable|integer|min:1|max:100',
                    'questions'                  => 'nullable|array',
                    'questions.*.text'           => 'required_with:questions|string',
                    'questions.*.type'           => 'required_with:questions|string|in:multiple_choice,true_false,short_answer,essay',
                    'questions.*.points'         => 'required_with:questions|integer|min:1',
                    'questions.*.options'        => 'nullable|array',
                    'questions.*.correct_answer' => 'nullable|string',
                    'questions.*.explanation'    => 'nullable|string',
                ]);

            case 'module_assessment':
                return array_merge($baseRules, [
                    'duration'                       => 'nullable|integer|min:15|max:120',
                    'total_marks'                    => 'nullable|integer|min:20|max:100',
                    'passing_score'                  => 'nullable|integer|min:50|max:100',
                    'is_timed'                       => 'sometimes|boolean',
                    'requires_identity_verification' => 'sometimes|boolean',
                    'questions'                      => 'nullable|array',
                    'questions.*.text'               => 'required_with:questions|string',
                    'questions.*.type'               => 'required_with:questions|string|in:multiple_choice,true_false,essay',
                    'questions.*.points'             => 'required_with:questions|integer|min:1',
                    'questions.*.options'            => 'nullable|array',
                    'questions.*.correct_answer'     => 'nullable|string',
                ]);

            case 'final_exam':
                return array_merge($baseRules, [
                    'duration'                       => 'required|integer|min:60|max:180',
                    'total_marks'                    => 'nullable|integer|min:50|max:200',
                    'passing_score'                  => 'nullable|integer|min:60|max:100',
                    'is_timed'                       => 'sometimes|boolean',
                    'requires_identity_verification' => 'sometimes|boolean',
                    'questions'                      => 'nullable|array',
                    'questions.*.text'               => 'required_with:questions|string',
                    'questions.*.type'               => 'required_with:questions|string|in:multiple_choice,true_false,essay,case_study',
                    'questions.*.points'             => 'required_with:questions|integer|min:1',
                    'questions.*.options'            => 'nullable|array',
                    'questions.*.correct_answer'     => 'nullable|string',
                ]);

            case 'diploma':
                return array_merge($baseRules, [
                    'project_brief'                  => 'required|string',
                    'total_marks'                    => 'nullable|integer|min:50|max:200',
                    'passing_score'                  => 'nullable|integer|min:60|max:100',
                    'needs_manual_marking'           => 'sometimes|boolean',
                    'requires_identity_verification' => 'sometimes|boolean',
                    'due_date'                       => 'nullable|date',
                    'rubric'                         => 'nullable|json',
                ]);

            default:
                return $baseRules;
        }
    }

    private function setTypeDefaults(array $validated, Request $request): array
    {
        switch ($request->assessment_level) {
            case 'quiz':
                $validated['is_timed']                       = false;
                $validated['requires_identity_verification'] = false;
                $validated['needs_manual_marking']           = false;
                break;
            case 'module_assessment':
                $validated['is_timed']                       = $request->has('is_timed');
                $validated['requires_identity_verification'] = $request->has('requires_identity_verification');
                $validated['needs_manual_marking']           = false;
                break;
            case 'final_exam':
                $validated['is_timed']                       = true;
                $validated['requires_identity_verification'] = true;
                $validated['needs_manual_marking']           = false;
                break;
            case 'diploma':
                $validated['is_timed']                       = false;
                $validated['requires_identity_verification'] = true;
                $validated['needs_manual_marking']           = true;
                break;
        }
        return $validated;
    }

    private function saveQuestions(Assessment $assessment, array $questions): void
    {
        foreach ($questions as $order => $questionData) {
            $row = [
                'assessment_id'    => $assessment->id,
                'module_id'        => $assessment->module_id ?? $questionData['module_id'] ?? null,
                'question_text'    => $questionData['text'],
                'question_type'    => $questionData['type'],
                'points'           => $questionData['points'],
                'difficulty_level' => $questionData['difficulty'] ?? 'medium',
                'order'            => $order,
                'is_required'      => true,
                'correct_answer'   => null,
                'correct_answers'  => null,
            ];

            switch ($questionData['type']) {
                case 'multiple_choice':
                    $options = array_values(array_filter(
                        $questionData['options'] ?? [],
                        fn($o) => trim((string) $o) !== ''
                    ));
                    $row['options'] = $options;
                    $indexRaw = $questionData['correct_answer'] ?? null;
                    if ($indexRaw !== null && $indexRaw !== '') {
                        $index = (int) $indexRaw;
                        $row['correct_answer'] = $options[$index] ?? $indexRaw;
                    }
                    break;

                case 'true_false':
                    $row['options'] = ['True', 'False'];
                    $raw = $questionData['correct_answer'] ?? null;
                    $row['correct_answer'] = $raw !== null
                        ? ucfirst(strtolower(trim($raw))) : null;
                    break;

                case 'short_answer':
                    $row['correct_answer'] = isset($questionData['correct_answer'])
                        ? trim($questionData['correct_answer']) : null;
                    break;

                case 'multiple_answer':
                    $raw = $questionData['correct_answers'] ?? [];
                    $row['correct_answers'] = is_array($raw) ? $raw : json_decode($raw, true);
                    $row['options'] = array_values(array_filter(
                        $questionData['options'] ?? [],
                        fn($o) => trim((string) $o) !== ''
                    ));
                    break;

                case 'essay':
                    $row['explanation'] = trim($questionData['explanation'] ?? '') ?: null;
                    break;
                case 'case_study':
                    break;
            }

            AssessmentQuestion::create($row);
        }
    }
}
