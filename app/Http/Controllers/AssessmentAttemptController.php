<?php
// app/Http/Controllers/AssessmentAttemptController.php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\AssessmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AssessmentAttemptController extends Controller
{
    /**
     * Take a quiz assessment
     */
    public function takeQuiz($enrollmentId, $assessmentId)
    {
        $enrollment = Enrollment::with('course')
            ->where('id', $enrollmentId)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $assessment = Assessment::with('questions')
            ->where('id', $assessmentId)
            ->where('course_id', $enrollment->course_id)
            ->where('assessment_level', 'quiz')
            ->firstOrFail();

        // Check if already completed
        $existingSubmission = AssessmentSubmission::where('assessment_id', $assessmentId)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->first();

        if ($existingSubmission) {
            return redirect()->route('dashboard.courses.show', $enrollment->course->slug)
                ->with('error', 'You have already completed this quiz.');
        }

        // Check for existing attempt
        $attempt = AssessmentAttempt::where('assessment_id', $assessmentId)
            ->where('user_id', auth()->id())
            ->where('status', 'in_progress')
            ->first();

        if (!$attempt) {
            // Create new attempt
            $attempt = AssessmentAttempt::create([
                'assessment_id' => $assessmentId,
                'user_id' => auth()->id(),
                'enrollment_id' => $enrollmentId,
                'started_at' => now(),
                'status' => 'in_progress',
                'attempt_number' => 1,
            ]);
        }

        // Prepare questions (shuffle options for multiple choice)
        $questions = $assessment->questions->map(function($question) {
            $data = [
                'id' => $question->id,
                'text' => $question->question_text,
                'type' => $question->question_type,
                'points' => $question->points,
            ];
            
            if ($question->question_type === 'multiple_choice' && $question->options) {
                $options = $question->options;
                shuffle($options);
                $data['options'] = $options;
            }
            
            return $data;
        })->shuffle(); // Shuffle questions

        return Inertia::render('Assessment/Quiz', [
            'enrollment' => [
                'id' => $enrollment->id,
                'course' => [
                    'id' => $enrollment->course->id,
                    'title' => $enrollment->course->title,
                    'slug' => $enrollment->course->slug,
                ]
            ],
            'assessment' => [
                'id' => $assessment->id,
                'title' => $assessment->title,
                'description' => $assessment->description,
                'duration' => $assessment->duration,
                'total_marks' => $assessment->total_marks,
                'passing_score' => $assessment->passing_score,
                'questions' => $questions,
                'question_count' => $questions->count(),
            ],
            'attempt' => [
                'id' => $attempt->id,
                'started_at' => $attempt->started_at,
                'time_remaining' => $assessment->duration ? now()->diffInSeconds($attempt->started_at->copy()->addMinutes($assessment->duration), false) : null,
            ]
        ]);
    }

    /**
     * Continue a quiz assessment
     */
    public function continueQuiz($enrollmentId, $assessmentId)
    {
        $enrollment = Enrollment::where('id', $enrollmentId)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $attempt = AssessmentAttempt::where('assessment_id', $assessmentId)
            ->where('user_id', auth()->id())
            ->where('status', 'in_progress')
            ->firstOrFail();

        $assessment = Assessment::with('questions')
            ->where('id', $assessmentId)
            ->firstOrFail();

        // Prepare questions with saved answers
        $savedAnswers = $attempt->answers_snapshot ?? [];
        
        $questions = $assessment->questions->map(function($question) use ($savedAnswers) {
            $data = [
                'id' => $question->id,
                'text' => $question->question_text,
                'type' => $question->question_type,
                'points' => $question->points,
                'saved_answer' => $savedAnswers[$question->id] ?? null,
            ];
            
            if ($question->question_type === 'multiple_choice' && $question->options) {
                $options = $question->options;
                shuffle($options);
                $data['options'] = $options;
            }
            
            return $data;
        });

        return Inertia::render('Assessment/Quiz', [
            'enrollment' => [
                'id' => $enrollment->id,
                'course' => [
                    'id' => $enrollment->course->id,
                    'title' => $enrollment->course->title,
                    'slug' => $enrollment->course->slug,
                ]
            ],
            'assessment' => [
                'id' => $assessment->id,
                'title' => $assessment->title,
                'description' => $assessment->description,
                'duration' => $assessment->duration,
                'total_marks' => $assessment->total_marks,
                'passing_score' => $assessment->passing_score,
                'questions' => $questions,
                'question_count' => $questions->count(),
            ],
            'attempt' => [
                'id' => $attempt->id,
                'started_at' => $attempt->started_at,
                'saved_answers' => $attempt->answers_snapshot,
                'last_question' => $attempt->last_question_index ?? 0,
                'time_remaining' => $assessment->duration ? now()->diffInSeconds($attempt->started_at->copy()->addMinutes($assessment->duration), false) : null,
            ]
        ]);
    }

    /**
     * Submit quiz answers
     */
    public function submitQuiz(Request $request, $enrollmentId, $assessmentId)
    {
        $request->validate([
            'answers' => 'required|array',
            'time_spent' => 'nullable|integer',
        ]);

        $enrollment = Enrollment::where('id', $enrollmentId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $assessment = Assessment::with('questions')
            ->where('id', $assessmentId)
            ->firstOrFail();

        $attempt = AssessmentAttempt::where('assessment_id', $assessmentId)
            ->where('user_id', auth()->id())
            ->where('status', 'in_progress')
            ->firstOrFail();

        DB::beginTransaction();

        try {
            // Calculate score
            $totalPoints = 0;
            $earnedPoints = 0;
            $results = [];

            foreach ($assessment->questions as $question) {
                $totalPoints += $question->points;
                $userAnswer = $request->answers[$question->id] ?? null;
                
                $isCorrect = $this->checkAnswer($question, $userAnswer);
                
                if ($isCorrect) {
                    $earnedPoints += $question->points;
                }

                $results[$question->id] = [
                    'correct' => $isCorrect,
                    'user_answer' => $userAnswer,
                    'correct_answer' => $question->correct_answer,
                    'points_earned' => $isCorrect ? $question->points : 0,
                ];
            }

            $score = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100, 2) : 0;
            $passed = $score >= $assessment->passing_score;

            // Create submission
            $submission = AssessmentSubmission::create([
                'assessment_id' => $assessmentId,
                'user_id' => auth()->id(),
                'enrollment_id' => $enrollmentId,
                'started_at' => $attempt->started_at,
                'submitted_at' => now(),
                'answers' => $request->answers,
                'results' => $results,
                'score' => $score,
                'percentage' => $score,
                'passed' => $passed,
                'time_spent' => $request->time_spent,
                'status' => 'completed',
            ]);

            // Update attempt
            $attempt->update([
                'status' => 'completed',
                'completed_at' => now(),
                'submission_id' => $submission->id,
            ]);

            DB::commit();

            return redirect()->route('dashboard.courses.show', $enrollment->course->slug)
                ->with('success', $passed ? 'Quiz completed! You passed!' : 'Quiz completed. Keep studying and try again.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Quiz submission failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to submit quiz. Please try again.');
        }
    }

    /**
     * Review quiz results
     */
    public function reviewQuiz($enrollmentId, $assessmentId)
    {
        $enrollment = Enrollment::where('id', $enrollmentId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $submission = AssessmentSubmission::where('assessment_id', $assessmentId)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();

        $assessment = Assessment::with('questions')
            ->where('id', $assessmentId)
            ->firstOrFail();

        // Prepare questions with results
        $questions = $assessment->questions->map(function($question) use ($submission) {
            $result = $submission->results[$question->id] ?? null;
            
            return [
                'id' => $question->id,
                'text' => $question->question_text,
                'type' => $question->question_type,
                'points' => $question->points,
                'options' => $question->options,
                'correct_answer' => $question->correct_answer,
                'user_answer' => $result['user_answer'] ?? null,
                'is_correct' => $result['correct'] ?? false,
                'points_earned' => $result['points_earned'] ?? 0,
            ];
        });

        return Inertia::render('Assessment/QuizReview', [
            'enrollment' => [
                'id' => $enrollment->id,
                'course' => [
                    'id' => $enrollment->course->id,
                    'title' => $enrollment->course->title,
                    'slug' => $enrollment->course->slug,
                ]
            ],
            'assessment' => [
                'id' => $assessment->id,
                'title' => $assessment->title,
                'total_marks' => $assessment->total_marks,
                'passing_score' => $assessment->passing_score,
            ],
            'submission' => [
                'score' => $submission->score,
                'passed' => $submission->passed,
                'submitted_at' => $submission->submitted_at,
                'time_spent' => $submission->time_spent,
            ],
            'questions' => $questions,
        ]);
    }

    /**
     * Save quiz progress (AJAX endpoint for auto-save)
     */
    public function saveQuizProgress(Request $request, $attemptId)
    {
        $request->validate([
            'answers' => 'required|array',
            'last_question' => 'required|integer',
        ]);

        $attempt = AssessmentAttempt::where('id', $attemptId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $attempt->update([
            'answers_snapshot' => $request->answers,
            'last_question_index' => $request->last_question,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Check if answer is correct
     */
    private function checkAnswer($question, $answer)
    {
        if (!$answer) return false;

        switch ($question->question_type) {
            case 'multiple_choice':
            case 'true_false':
                return $answer == $question->correct_answer;
            
            case 'short_answer':
                // Case-insensitive comparison, trim whitespace
                return strtolower(trim($answer)) == strtolower(trim($question->correct_answer));
            
            default:
                return false;
        }
    }
}