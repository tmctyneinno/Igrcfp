<?php
 
namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Assessment;
use App\Models\CourseModuleUser;
use App\Models\Enrollment;
use App\Models\AssessmentSubmission;
use App\Models\AssessmentAttempt;
use App\Models\Notification; // ✅ ADD THIS
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
 
class QuizController extends Controller
{
    /**
     * Take a quiz (initialize or continue)
     */
    public function take(Course $course, $assessmentId)
    {
        $user = auth()->user();
        
        $assessment = $this->findAssessment($assessmentId);
        
        if (!$assessment) {
            abort(404, 'Assessment not found');
        }
        
        // Verify enrollment
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['enrolled', 'active', 'completed'])
            ->firstOrFail();

        if (!$this->allModulesRead($course, $enrollment)) {
            return redirect()->route('dashboard.courses.show', $course->slug)
                ->with('error', 'Please read all module content before taking the quiz.');
        }
        
        // Get or create attempt for THIS assessment
        $attempt = $this->getOrCreateAttempt($user->id, $assessment->id, $enrollment->id);
        
        // Get ALL questions for this assessment
        $allQuestions = $assessment->questions()
            ->select('id', 'question_text', 'question_type', 'options', 'points', 'correct_answer', 'module_id')
            ->inRandomOrder()
            ->get()
            ->map(function ($question) {
                return $this->formatQuestion($question);
            });
        
        // Calculate time remaining
        $timeLimit = $assessment->duration * 60;
        $timeRemaining = $this->calculateTimeRemaining($attempt, $timeLimit);
        
        return Inertia::render('Dashboard/Quiz/Take', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
            ],
            'assessment' => [
                'id' => $assessment->id,
                'title' => $assessment->title,
                'description' => $assessment->description,
                'essay_instructions' => $assessment->essay_instructions,
                'duration' => $assessment->duration,
                'total_marks' => $assessment->total_marks,
                'passing_score' => $assessment->passing_score,
                'questions_count' => $allQuestions->count(),
            ],
            'enrollment' => [
                'id' => $enrollment->id,
            ],
            'attempt' => [
                'id' => $attempt->id,
                'status' => $attempt->status,
                'answers' => json_decode($attempt->answers, true) ?? [],
            ],
            'modules' => [],
            'questions' => $allQuestions,
            'timeRemaining' => $timeRemaining,
            'timeLimit' => $timeLimit,
        ]);
    }
    
    /**
     * Auto-save quiz progress
     */
    public function saveProgress(Request $request, AssessmentAttempt $attempt)
    {
        // Verify user owns this attempt
        if ($attempt->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $attempt->update([
            'answers' => json_encode($request->input('answers', [])),
            'last_activity_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Progress saved',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
    
    /**
     * Submit a completed quiz (ONE quiz at a time)
     */
    public function submit(Request $request, Course $course, $assessmentId)
    {
        \Log::info('Submit method called', [
            'course' => $course->slug,
            'assessment_id' => $assessmentId,
            'skip_results' => $request->input('skip_results'),
            'answers' => $request->input('answers')
        ]);
        
        $user = auth()->user();
        $assessment = $this->findAssessment($assessmentId);
        
        if (!$assessment) {
            return response()->json(['error' => 'Assessment not found'], 404);
        }
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        
        if (!$enrollment) {
            return response()->json(['error' => 'Enrollment not found'], 404);
        }

        if (!$this->allModulesRead($course, $enrollment)) {
            return response()->json(['error' => 'Please read all module content before submitting the quiz.'], 403);
        }

        $request->validate([
            'essay_files' => ['nullable', 'array'],
            'essay_files.*' => ['file', 'mimes:pdf,doc,docx,txt,rtf', 'max:20480'],
        ]);
        
        // Find or create attempt
        $attempt = AssessmentAttempt::where('user_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->where('enrollment_id', $enrollment->id)
            ->where('status', 'in_progress')
            ->first();
        
        if (!$attempt) {
            $attempt = AssessmentAttempt::where('user_id', $user->id)
                ->where('assessment_id', $assessment->id)
                ->where('enrollment_id', $enrollment->id)
                ->where('status', 'not_started')
                ->first();
            
            if ($attempt) {
                $attempt->update(['status' => 'in_progress']);
            }
        }
        
        if (!$attempt) {
            $lastAttempt = AssessmentAttempt::where('user_id', $user->id)
                ->where('assessment_id', $assessment->id)
                ->where('enrollment_id', $enrollment->id)
                ->orderBy('attempt_number', 'desc')
                ->first();
            
            $attemptNumber = $lastAttempt ? $lastAttempt->attempt_number + 1 : 1;
            
            $attempt = AssessmentAttempt::create([
                'user_id' => $user->id,
                'assessment_id' => $assessment->id,
                'enrollment_id' => $enrollment->id,
                'attempt_number' => $attemptNumber,
                'status' => 'in_progress',
                'started_at' => now(),
                'answers' => json_encode([]),
            ]);
        }
        
        $answers = $request->input('answers', []);
        if (is_string($answers)) {
            $decodedAnswers = json_decode($answers, true);
            $answers = is_array($decodedAnswers) ? $decodedAnswers : [];
        }
        $essayFiles = $request->file('essay_files', []);
        $questions = $assessment->questions()->get();
        $requiresManualMarking = $assessment->needs_manual_marking || $questions->contains('question_type', 'essay');
        
        $totalMarks = 0;
        $earnedMarks = 0;
        $correctAnswers = 0;
        $questionResponses = [];
        
        foreach ($questions as $question) {
            $marks = $question->points ?? 1;
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = $question->isAnswerCorrect($userAnswer);
            $isManualQuestion = in_array($question->question_type, ['essay', 'case_study'], true);
            
            $pointsEarned = null;

            if (!$isManualQuestion) {
                $totalMarks += $marks;
                $pointsEarned = ($isCorrect === true) ? $marks : 0;
            }
            
            // Store detailed question responses for the submission
            $questionResponses[$question->id] = [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'answer' => $userAnswer,
                'correct_answer' => $isManualQuestion ? null : $question->correct_answer,
                'points_earned' => $pointsEarned,
                'points_possible' => $marks,
                'correct' => $isManualQuestion ? null : $isCorrect === true,
            ];

            if ($question->question_type === 'essay' && isset($essayFiles[$question->id])) {
                $file = $essayFiles[$question->id];
                $path = $file->store("quiz-essays/{$course->id}/{$assessment->id}/{$user->id}", 'public');

                $questionResponses[$question->id]['uploaded_file'] = [
                    'path' => $path,
                    'url' => Storage::url($path),
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }
            
            if (!$isManualQuestion && $isCorrect === true) {
                $earnedMarks += $marks;
                $correctAnswers++;
            }
        }
        
        $score = $totalMarks > 0 ? round(($earnedMarks / $totalMarks) * 100) : 0;
        $passed = !$requiresManualMarking && $score >= ($assessment->passing_score ?? 70);
        
        // Update the attempt
        $attempt->update([
            'status' => 'completed',
            'completed_at' => now(),
            'answers' => json_encode($answers),
            'score' => $score,
            'earned_marks' => $earnedMarks,
            'total_marks' => $totalMarks,
            'correct_answers' => $correctAnswers,
            'passed' => $passed,
        ]);
        
        // ✅ CREATE OR UPDATE ASSESSMENT SUBMISSION
        $submission = AssessmentSubmission::where('assessment_id', $assessment->id)
            ->where('user_id', $user->id)
            ->where('enrollment_id', $enrollment->id)
            ->first();
        
        $isNewSubmission = !$submission;
        
        if ($isNewSubmission) {
            // Get attempt count for this assessment
            $attemptCount = AssessmentSubmission::where('assessment_id', $assessment->id)
                ->where('user_id', $user->id)
                ->count();
            
            $submission = new AssessmentSubmission();
            $submission->assessment_id = $assessment->id;
            $submission->user_id = $user->id;
            $submission->enrollment_id = $enrollment->id;
            $submission->attempt_number = $attemptCount + 1;
            $submission->started_at = $attempt->started_at;
        }
        
        // Calculate time spent
        $timeSpent = 0;
        if ($attempt->started_at) {
            $timeSpent = now()->diffInSeconds($attempt->started_at);
        } elseif ($submission->started_at) {
            $timeSpent = now()->diffInSeconds($submission->started_at);
        }
        
        $submission->submitted_at = now();
        $submission->status = $requiresManualMarking ? 'submitted' : 'graded';
        $submission->answers = $answers;
        $submission->question_responses = $questionResponses;
        $submission->score = $earnedMarks;
        $submission->percentage = $score;
        $submission->passed = $passed;
        $submission->time_spent = $timeSpent;
        $submission->ip_address = $request->ip();
        $submission->user_agent = $request->userAgent();
        
        // If auto-graded, set graded_at
        if (!$requiresManualMarking) {
            $submission->graded_at = now();
        }
        
        $submission->save();
        
        // Link attempt to submission
        $attempt->update(['submission_id' => $submission->id]);
        
        // Update assessment statistics
        $assessment->calculateStatistics();
        
        // Update enrollment progress if passed
        if ($passed) {
            $enrollment->updateProgress();
        }
        
        // ✅ CREATE NOTIFICATION FOR QUIZ SUBMISSION
        $this->createQuizNotification($user, $course, $assessment, $passed, $score);
        
        \Log::info('Quiz submitted successfully', [
            'score' => $score,
            'passed' => $passed,
            'submission_id' => $submission->id,
            'skip_results' => $request->input('skip_results')
        ]);
        
        // ✅ ALWAYS return JSON for fetch requests
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Quiz submitted successfully!',
                'score' => $score,
                'passed' => $passed,
                'manual_review' => $requiresManualMarking,
                'submission_id' => $submission->id,
            ]);
        }
        
        // For Inertia requests, return redirect
        return Inertia::location(route('dashboard.quiz.results', [
            'course' => $course->slug,
            'assessment' => $assessmentId
        ]));
    }

    /**
     * ✅ Create notification for quiz submission
     */
    private function createQuizNotification($user, $course, $assessment, $passed, $score)
    {
        $moduleName = $assessment->module ? "Module {$assessment->module->module_number}: {$assessment->module->title}" : $course->title;
        
        if ($passed) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'quiz_passed',
                'title' => '🎉 Quiz Passed!',
                'message' => "Congratulations! You passed the '{$assessment->title}' quiz with a score of {$score}%.",
                'data' => [
                    'course_slug' => $course->slug,
                    'assessment_id' => $assessment->id,
                    'module_name' => $moduleName,
                    'score' => $score,
                    'passed' => true,
                ],
            ]);
        } else {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'quiz_failed',
                'title' => '📝 Quiz Submitted',
                'message' => "You scored {$score}% on '{$assessment->title}'. You can retake the quiz to improve your score.",
                'data' => [
                    'course_slug' => $course->slug,
                    'assessment_id' => $assessment->id,
                    'module_name' => $moduleName,
                    'score' => $score,
                    'passed' => false,
                    'passing_score' => $assessment->passing_score,
                ],
            ]);
        }
    }

    /**
     * ✅ Check and notify when all module quizzes are completed
     */
    private function checkAndNotifyAllQuizzesCompleted($user, $course, $enrollment)
    {
        $allQuizzes = Assessment::where('course_id', $course->id)
            ->where('assessment_level', 'quiz')
            ->where('status', 'active')
            ->get();
        
        $passedQuizzes = Assessment::where('course_id', $course->id)
            ->where('assessment_level', 'quiz')
            ->where('status', 'active')
            ->whereHas('attempts', function($q) use ($user, $enrollment) {
                $q->where('user_id', $user->id)
                    ->where('enrollment_id', $enrollment->id)
                    ->where('passed', true);
            })
            ->get();
        
        // Check if all quizzes are passed
        if ($allQuizzes->count() > 0 && $passedQuizzes->count() === $allQuizzes->count()) {
            // Check if notification already sent
            $existingNotification = Notification::where('user_id', $user->id)
                ->where('type', 'all_quizzes_completed')
                ->where('data->course_slug', $course->slug)
                ->exists();
            
            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'all_quizzes_completed',
                    'title' => '🏆 All Quizzes Completed!',
                    'message' => "You've passed all quizzes for '{$course->title}'! You're now eligible for the final project assessment.",
                    'data' => [
                        'course_slug' => $course->slug,
                        'course_title' => $course->title,
                        'total_quizzes' => $allQuizzes->count(),
                    ],
                ]);
            }
        }
    }
    
    /**
     * View quiz results
     */
    public function results(Course $course, $assessmentId = null)
    {
        $user = auth()->user();
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();
        
        // Get ALL quizzes for this course with their attempts
        $allQuizzes = Assessment::where('course_id', $course->id)
            ->where('assessment_level', 'quiz')
            ->where('status', 'active')
            ->with(['module'])
            ->get()
            ->map(function ($quiz) use ($user, $enrollment) {
                // Get the latest completed attempt for this quiz
                $attempt = AssessmentAttempt::where('user_id', $user->id)
                    ->where('assessment_id', $quiz->id)
                    ->where('enrollment_id', $enrollment->id)
                    ->where('status', 'completed')
                    ->latest()
                    ->first();
                
                // Get questions with user's answers
                $questions = $quiz->questions()->get();
                $answers = $attempt ? (json_decode($attempt->answers, true) ?? []) : [];
                
                $questionsWithResults = $questions->map(function ($question) use ($answers) {
                    $userAnswer = $answers[$question->id] ?? null;
                    $isCorrect = $question->isAnswerCorrect($userAnswer);
                    
                    $options = $question->options;
                    if (is_string($options)) {
                        $options = json_decode($options, true);
                    }
                    
                    return [
                        'id' => $question->id,
                        'text' => $question->question_text,
                        'options' => $options,
                        'correct_answer' => $question->correct_answer,
                        'user_answer' => $userAnswer,
                        'is_correct' => $isCorrect,
                        'points' => $question->points ?? 1,
                    ];
                });
                
                return [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'description' => $quiz->description,
                    'duration' => $quiz->duration,
                    'total_marks' => $quiz->total_marks,
                    'passing_score' => $quiz->passing_score,
                    'module' => $quiz->module ? [
                        'id' => $quiz->module->id,
                        'title' => $quiz->module->title,
                        'module_number' => $quiz->module->module_number,
                    ] : null,
                    'attempt' => $attempt ? [
                        'id' => $attempt->id,
                        'score' => $attempt->score,
                        'earned_marks' => $attempt->earned_marks,
                        'total_marks' => $attempt->total_marks,
                        'correct_answers' => $attempt->correct_answers,
                        'passed' => $attempt->passed,
                        'completed_at' => $attempt->completed_at ? $attempt->completed_at->format('M d, Y H:i') : null,
                    ] : null,
                    'questions' => $questionsWithResults,
                    'has_attempt' => !is_null($attempt),
                ];
            });
        
        // Calculate overall stats
        $completedQuizzes = $allQuizzes->filter(fn($q) => $q['has_attempt']);
        $passedQuizzes = $allQuizzes->filter(fn($q) => $q['attempt']['passed'] ?? false);
        
        $overallStats = [
            'total_quizzes' => $allQuizzes->count(),
            'completed_quizzes' => $completedQuizzes->count(),
            'passed_quizzes' => $passedQuizzes->count(),
            'average_score' => $completedQuizzes->count() > 0 
                ? round($completedQuizzes->avg(fn($q) => $q['attempt']['score'] ?? 0)) 
                : 0,
            'total_points' => $completedQuizzes->sum(fn($q) => $q['attempt']['earned_marks'] ?? 0),
            'total_possible' => $completedQuizzes->sum(fn($q) => $q['attempt']['total_marks'] ?? 0),
        ];

        // ✅ GET PROJECT ASSESSMENT STATUS
        $projectAssessment = Assessment::where('course_id', $course->id)
            ->whereIn('assessment_level', ['diploma', 'project'])
            ->where('status', 'active')
            ->first();

        $projectStatus = null;
        if ($projectAssessment) {
            $submission = AssessmentSubmission::where('assessment_id', $projectAssessment->id)
                ->where('user_id', $user->id)
                ->where('enrollment_id', $enrollment->id)
                ->first();
            
            $projectStatus = [
                'id' => $projectAssessment->id,
                'title' => $projectAssessment->title,
                'has_submitted' => !is_null($submission),
                'is_graded' => $submission && $submission->status === 'graded',
                'has_passed' => $submission && $submission->passed,
                'score' => $submission ? $submission->percentage : null,
                'submitted_at' => $submission ? $submission->submitted_at?->format('M d, Y') : null,
                'graded_at' => $submission ? $submission->graded_at?->format('M d, Y') : null,
            ];
            
            // ✅ Check if user is eligible for final project and hasn't been notified
            if ($passedQuizzes->count() === $allQuizzes->count() && $allQuizzes->count() > 0) {
                $existingNotification = Notification::where('user_id', $user->id)
                    ->where('type', 'project_eligible')
                    ->where('data->course_slug', $course->slug)
                    ->exists();
                
                if (!$existingNotification) {
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'project_eligible',
                        'title' => '🎯 Final Project Unlocked!',
                        'message' => "You've passed all quizzes! You can now submit your final project for '{$course->title}'.",
                        'data' => [
                            'course_slug' => $course->slug,
                            'course_title' => $course->title,
                            'project_id' => $projectAssessment->id,
                        ],
                    ]);
                }
            }
        }
        
        // Get the specific assessment if provided (for highlighting)
        $currentAssessment = $assessmentId ? $this->findAssessment($assessmentId) : null;
        
        return Inertia::render('Dashboard/Quiz/Results', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
            ],
            'currentAssessment' => $currentAssessment ? [
                'id' => $currentAssessment->id,
                'title' => $currentAssessment->title,
            ] : null,
            'quizzes' => $allQuizzes->values(),
            'overallStats' => $overallStats,
            'projectStatus' => $projectStatus,
            'enrollment' => [
                'id' => $enrollment->id,
                'progress' => $enrollment->progress,
                'status' => $enrollment->status,
            ],
        ]);
    }
    
    /**
     * Continue a quiz (alias for take)
     */
    public function continue(Course $course, $assessmentId)
    {
        return $this->take($course, $assessmentId);
    }
    
    // ==================== HELPER METHODS ====================
    
    /**
     * Get or create an attempt for a quiz
     */
    private function getOrCreateAttempt($userId, $assessmentId, $enrollmentId)
    {
        // Check for existing in-progress attempt
        $attempt = AssessmentAttempt::where('user_id', $userId)
            ->where('assessment_id', $assessmentId)
            ->where('enrollment_id', $enrollmentId)
            ->whereIn('status', ['not_started', 'in_progress'])
            ->first();
        
        if ($attempt) {
            // Reuse existing attempt
            $attempt->update([
                'status' => 'in_progress',
                'started_at' => $attempt->started_at ?? now(),
            ]);
            return $attempt;
        }
        
        // Create new attempt with proper attempt number
        $lastAttempt = AssessmentAttempt::where('user_id', $userId)
            ->where('assessment_id', $assessmentId)
            ->where('enrollment_id', $enrollmentId)
            ->orderBy('attempt_number', 'desc')
            ->first();
        
        $attemptNumber = $lastAttempt ? $lastAttempt->attempt_number + 1 : 1;
        
        return AssessmentAttempt::create([
            'user_id' => $userId,
            'assessment_id' => $assessmentId,
            'enrollment_id' => $enrollmentId,
            'attempt_number' => $attemptNumber,
            'status' => 'in_progress',
            'started_at' => now(),
            'answers' => json_encode([]),
        ]);
    }
    
    /**
     * Calculate remaining time for a quiz
     */
    private function calculateTimeRemaining($attempt, $timeLimit)
    {
        if (!$attempt->started_at) {
            return $timeLimit;
        }
        
        $elapsed = now()->diffInSeconds($attempt->started_at);
        return max(0, $timeLimit - $elapsed);
    }
    
    /**
     * Format a question for frontend display
     */
    private function formatQuestion($question)
    {
        $options = $question->options;
        if (is_string($options)) {
            $options = json_decode($options, true);
        }
        
        return [
            'id' => $question->id,
            'text' => $question->question_text,
            'type' => $question->question_type,
            'options' => $options ?? [],
            'marks' => $question->points ?? $question->marks ?? 1,
            'correct_answer' => $question->correct_answer,
            'module_id' => $question->module_id,
        ];
    }
    
    /**
     * Score a quiz submission
     */
    private function scoreQuiz($assessment, $answers)
    {
        $questions = $assessment->questions()->get();
        
        $totalMarks = 0;
        $earnedMarks = 0;
        $correctAnswers = 0;
        
        foreach ($questions as $question) {
            $marks = $question->points ?? 1;
            $totalMarks += $marks;
            
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = $question->isAnswerCorrect($userAnswer);
            
            if ($isCorrect === true) {
                $earnedMarks += $marks;
                $correctAnswers++;
            }
        }
        
        $score = $totalMarks > 0 ? round(($earnedMarks / $totalMarks) * 100) : 0;
        $passed = $score >= ($assessment->passing_score ?? 70);
        
        return [
            'score' => $score,
            'earned_marks' => $earnedMarks,
            'total_marks' => $totalMarks,
            'correct_answers' => $correctAnswers,
            'passed' => $passed,
        ];
    }
    
    /**
     * Get all modules with their questions for the sidebar
     */
    private function getModulesWithQuestions($course, $assessment)
    {
        $user = auth()->user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        
        return $course->modules()
            ->orderBy('module_number')
            ->get()
            ->map(function ($module) use ($course, $user, $enrollment) {
                // Get ALL quizzes for this module
                $moduleQuizzes = Assessment::where('course_id', $course->id)
                    ->where('module_id', $module->id)
                    ->where('assessment_level', 'quiz')
                    ->where('status', 'active')
                    ->get()
                    ->map(function ($quiz) use ($user, $enrollment) {
                        $questions = $quiz->questions()
                            ->select('id', 'question_text', 'options', 'marks', 'correct_answer')
                            ->get()
                            ->map(function ($q) {
                                return $this->formatQuestion($q);
                            });
                        
                        // ✅ Get user's attempt for this quiz
                        $attempt = AssessmentAttempt::where('user_id', $user->id)
                            ->where('assessment_id', $quiz->id)
                            ->where('enrollment_id', $enrollment->id)
                            ->where('status', 'completed')
                            ->latest()
                            ->first();
                        
                        return [
                            'id' => $quiz->id,
                            'title' => $quiz->title,
                            'description' => $quiz->description,
                            'duration' => $quiz->duration,
                            'total_marks' => $quiz->total_marks,
                            'passing_score' => $quiz->passing_score,
                            'questions' => $questions->values()->toArray(),
                            'questions_count' => $questions->count(),
                            'status' => $quiz->status ?? 'not_started',
                            'attempt' => $attempt ? [
                                'id' => $attempt->id,
                                'score' => $attempt->score,
                                'passed' => $attempt->passed,
                                'completed_at' => $attempt->completed_at ? $attempt->completed_at->format('M d, Y') : null,
                            ] : null,
                        ];
                    });
                
                // Get lesson completion status for this module
                $lessons = $module->lessons()
                    ->withCompletionStatus($user->id, $enrollment->id ?? null)
                    ->get()
                    ->map(function ($lesson) {
                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'completed' => (bool) ($lesson->completed ?? false),
                        ];
                    });
                
                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'module_number' => $module->module_number,
                    'quizzes' => $moduleQuizzes->values()->toArray(),
                    'quizzes_count' => $moduleQuizzes->count(),
                    'lessons' => $lessons->values()->toArray(),
                ];
            })
            ->filter(function ($module) {
                return $module['quizzes_count'] > 0;
            })
            ->values()
            ->toArray();
    }
    
    /**
     * Find assessment by ID or module ID
     */
    private function findAssessment($assessmentId)
    {
        if (is_numeric($assessmentId)) {
            return Assessment::find($assessmentId);
        }
        
        if (str_starts_with($assessmentId, 'module-')) {
            $moduleId = (int) str_replace('module-', '', $assessmentId);
            
            return Assessment::where('module_id', $moduleId)
                ->where('assessment_level', 'quiz')
                ->first();
        }
        
        return Assessment::find($assessmentId);
    }

    private function allModulesRead(Course $course, Enrollment $enrollment): bool
    {
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
}
