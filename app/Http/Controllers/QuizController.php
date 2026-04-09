<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Assessment;
use App\Models\Enrollment;
use App\Models\AssessmentAttempt;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizController extends Controller
{
    public function take(Course $course, $assessmentId)
    {
        $user = auth()->user();
        
        $assessment = $this->findAssessment($assessmentId);
        
        if (!$assessment) {
            abort(404, 'Assessment not found');
        }
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['enrolled', 'active', 'completed'])
            ->firstOrFail();
        
        // Get the next attempt number
        $lastAttempt = AssessmentAttempt::where('user_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->where('enrollment_id', $enrollment->id)
            ->orderBy('attempt_number', 'desc')
            ->first();
        
        $attemptNumber = $lastAttempt ? $lastAttempt->attempt_number + 1 : 1;
        
        // Check if there's an existing not_started or in_progress attempt
        $attempt = AssessmentAttempt::where('user_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->where('enrollment_id', $enrollment->id)
            ->whereIn('status', ['not_started', 'in_progress'])
            ->first();
        
        if (!$attempt) {
            $attempt = new AssessmentAttempt();
            $attempt->user_id = $user->id;
            $attempt->assessment_id = $assessment->id;
            $attempt->enrollment_id = $enrollment->id;
            $attempt->attempt_number = $attemptNumber;
            $attempt->status = 'not_started';
            $attempt->started_at = now();
            $attempt->answers = json_encode([]);
            $attempt->save();
        }
        
        // If already completed, redirect to results
        $completedAttempt = AssessmentAttempt::where('user_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->where('enrollment_id', $enrollment->id)
            ->where('status', 'completed')
            ->first();
            
        if ($completedAttempt) {
            return redirect()->route('dashboard.quiz.results', [
                'course' => $course->slug,
                'assessment' => $assessmentId
            ]);
        }
        
        // Update status to in_progress
        $attempt->status = 'in_progress';
        $attempt->started_at = $attempt->started_at ?? now();
        $attempt->save();
        
        // Get ALL modules with their questions for this assessment
        $modules = $this->getModulesWithQuestions($course, $assessment);
        
        // Get ALL questions for the quiz
        $allQuestions = $assessment->questions()
            ->select('id', 'question_text', 'options', 'marks', 'correct_answer', 'module_id')
            ->inRandomOrder()
            ->get()
            ->map(function ($question) {
                $options = $question->options;
                if (is_string($options)) {
                    $options = json_decode($options, true);
                }
                
                return [
                    'id' => $question->id,
                    'text' => $question->question_text,
                    'options' => $options,
                    'marks' => $question->marks ?? 1,
                    'correct_answer' => $question->correct_answer,
                    'module_id' => $question->module_id,
                ];
            });
        
        $timeLimit = $assessment->duration * 60;
        $timeRemaining = $timeLimit;
        
        if ($attempt->started_at) {
            $elapsed = now()->diffInSeconds($attempt->started_at);
            $timeRemaining = max(0, $timeLimit - $elapsed);
        }
        
        // Debug log
        \Log::info('Quiz modules data:', [
            'course_id' => $course->id,
            'assessment_id' => $assessment->id,
            'modules_count' => count($modules),
            'questions_count' => $allQuestions->count()
        ]);
        
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
            'modules' => $modules,
            'questions' => $allQuestions,
            'timeRemaining' => $timeRemaining,
            'timeLimit' => $timeLimit,
        ]);
    }
    
    /**
     * Get all modules with their questions for the sidebar
     */
    private function getModulesWithQuestions($course, $assessment)
    {
        // If assessment is linked to a specific module, only show that module
        if ($assessment->module_id) {
            $module = $course->modules()->find($assessment->module_id);
            if (!$module) {
                return [];
            }
            
            $moduleQuestions = $assessment->questions()
                ->select('id', 'question_text', 'options', 'marks', 'correct_answer')
                ->get()
                ->map(function ($q) {
                    $options = $q->options;
                    if (is_string($options)) {
                        $options = json_decode($options, true);
                    }
                    return [
                        'id' => $q->id,
                        'text' => $q->question_text,
                        'options' => $options,
                        'marks' => $q->marks ?? 1,
                        'correct_answer' => $q->correct_answer,
                    ];
                });
            
            return [[
                'id' => $module->id,
                'title' => $module->title,
                'module_number' => $module->module_number,
                'questions' => $moduleQuestions->values()->toArray(),
                'questions_count' => $moduleQuestions->count(),
            ]];
        }
        
        // Otherwise, show all modules that have questions for this assessment
        return $course->modules()
            ->orderBy('module_number')
            ->get()
            ->map(function ($module) use ($assessment) {
                $moduleQuestions = $assessment->questions()
                    ->where('module_id', $module->id)
                    ->select('id', 'question_text', 'options', 'marks', 'correct_answer')
                    ->get()
                    ->map(function ($q) {
                        $options = $q->options;
                        if (is_string($options)) {
                            $options = json_decode($options, true);
                        }
                        return [
                            'id' => $q->id,
                            'text' => $q->question_text,
                            'options' => $options,
                            'marks' => $q->marks ?? 1,
                            'correct_answer' => $q->correct_answer,
                        ];
                    });
                
                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'module_number' => $module->module_number,
                    'questions' => $moduleQuestions->values()->toArray(),
                    'questions_count' => $moduleQuestions->count(),
                ];
            })
            ->filter(function ($module) {
                return $module['questions_count'] > 0;
            })
            ->values()
            ->toArray();
    }
    
    public function submit(Request $request, Course $course, $assessmentId)
    {
        $user = auth()->user();
        $assessment = $this->findAssessment($assessmentId);
        
        if (!$assessment) {
            abort(404, 'Assessment not found');
        }
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();
        
        $attempt = AssessmentAttempt::where('user_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->where('enrollment_id', $enrollment->id)
            ->where('status', 'in_progress')
            ->firstOrFail();
        
        $answers = $request->input('answers', []);
        $questions = $assessment->questions()->get();
        
        $totalMarks = 0;
        $earnedMarks = 0;
        $correctAnswers = 0;
        
        foreach ($questions as $question) {
            $marks = $question->marks ?? 1;
            $totalMarks += $marks;
            $userAnswer = $answers[$question->id] ?? null;
            $correctAnswer = $question->correct_answer;
            
            if ($userAnswer === $correctAnswer) {
                $earnedMarks += $marks;
                $correctAnswers++;
            }
        }
        
        $score = $totalMarks > 0 ? round(($earnedMarks / $totalMarks) * 100) : 0;
        $passed = $score >= ($assessment->passing_score ?? 70);
        
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
        
        if ($passed) {
            $enrollment->updateProgress();
        }
        
        return redirect()->route('dashboard.quiz.results', [
            'course' => $course->slug,
            'assessment' => $assessmentId
        ])->with('success', $passed ? 'Congratulations! You passed the quiz!' : 'Quiz submitted.');
    }
    
    public function results(Course $course, $assessmentId)
    {
        $user = auth()->user();
        $assessment = $this->findAssessment($assessmentId);
        
        if (!$assessment) {
            abort(404, 'Assessment not found');
        }
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();
        
        $attempt = AssessmentAttempt::where('user_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->where('enrollment_id', $enrollment->id)
            ->where('status', 'completed')
            ->firstOrFail();
        
        $questions = $assessment->questions()->get();
        $answers = json_decode($attempt->answers, true) ?? [];
        
        $questionsWithResults = $questions->map(function ($question) use ($answers) {
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = $userAnswer === $question->correct_answer;
            
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
                'marks' => $question->marks ?? 1,
            ];
        });
        
        return Inertia::render('Dashboard/Quiz/Results', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
            ],
            'assessment' => [
                'id' => $assessment->id,
                'title' => $assessment->title,
                'passing_score' => $assessment->passing_score ?? 70,
                'total_marks' => $attempt->total_marks,
            ],
            'attempt' => [
                'id' => $attempt->id,
                'score' => $attempt->score,
                'earned_marks' => $attempt->earned_marks,
                'total_marks' => $attempt->total_marks,
                'correct_answers' => $attempt->correct_answers,
                'passed' => $attempt->passed,
                'completed_at' => $attempt->completed_at ? $attempt->completed_at->format('M d, Y H:i') : null,
            ],
            'questions' => $questionsWithResults,
        ]);
    }
    
    public function continue(Course $course, $assessmentId)
    {
        return $this->take($course, $assessmentId);
    }
    
    public function saveProgress(Request $request, AssessmentAttempt $attempt)
    {
        $attempt->update([
            'answers' => json_encode($request->input('answers', [])),
            'last_activity_at' => now(),
        ]);
        
        return response()->json(['success' => true]);
    }
    
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
}