<?php
// app/Http/Controllers/ExamController.php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExamController extends Controller
{
    /**
     * Verify identity with photo capture
     */
    public function verifyIdentity(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'image' => 'required|string' // Base64 image
        ]);

        // Store verification image
        $imageData = $request->input('image');
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
        
        $filename = 'verifications/' . $enrollment->id . '/' . time() . '.jpg';
        Storage::disk('public')->put($filename, $image);

        // Update enrollment with verification status
        $enrollment->update([
            'identity_verified' => true,
            'verified_at' => now(),
            'verification_image' => $filename
        ]);

        // Log verification
        activity()
            ->performedOn($enrollment)
            ->causedBy(auth()->user())
            ->log('Identity verified');

        return back()->with('success', 'Identity verified successfully');
    }

    /**
     * Start an exam
     */
    public function start(Enrollment $enrollment, Exam $exam)
    {
        // Check if identity is verified
        if (!$enrollment->identity_verified) {
            return back()->with('error', 'Please verify your identity first');
        }

        // Check if exam already started
        $examAttempt = $enrollment->examAttempts()
            ->where('exam_id', $exam->id)
            ->first();

        if (!$examAttempt) {
            // Create new attempt with randomised questions
            $questions = $exam->questions()
                ->inRandomOrder()
                ->limit($exam->question_count)
                ->get();

            $examAttempt = $enrollment->examAttempts()->create([
                'exam_id' => $exam->id,
                'started_at' => now(),
                'expires_at' => now()->addMinutes($exam->duration),
                'questions' => $questions->pluck('id'),
                'status' => 'in_progress'
            ]);
        }

        return Inertia::render('Exam/Take', [
            'exam' => $exam,
            'attempt' => $examAttempt,
            'enrollment' => $enrollment
        ]);
    }

    /**
     * Submit exam answers
     */
    public function submit(Request $request, Enrollment $enrollment, Exam $exam)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string'
        ]);

        $examAttempt = $enrollment->examAttempts()
            ->where('exam_id', $exam->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        // Check if exam expired
        if (now()->gt($examAttempt->expires_at)) {
            return back()->with('error', 'Exam time expired');
        }

        // Calculate score
        $score = $this->calculateScore($exam, $request->answers);
        
        // Check for plagiarism
        $plagiarismScore = $this->checkPlagiarism($request->answers);

        // Update attempt
        $examAttempt->update([
            'answers' => $request->answers,
            'score' => $score,
            'plagiarism_score' => $plagiarismScore,
            'completed_at' => now(),
            'status' => $plagiarismScore > 30 ? 'flagged' : 'completed'
        ]);

        // If diploma level, mark for manual review
        if ($exam->level === 'diploma' || $exam->level === 'advanced_diploma') {
            $examAttempt->update(['needs_review' => true]);
            
            // Notify admins
            event(new ExamNeedsReview($examAttempt));
        }

        return redirect()->route('dashboard.courses.show', $enrollment)
            ->with('success', 'Exam submitted successfully');
    }

    /**
     * Calculate exam score
     */
    private function calculateScore($exam, $answers)
    {
        $totalQuestions = count($answers);
        $correctAnswers = 0;

        foreach ($answers as $questionId => $answer) {
            $question = Question::find($questionId);
            if ($question && $question->correct_answer === $answer) {
                $correctAnswers++;
            }
        }

        return ($correctAnswers / $totalQuestions) * 100;
    }

    /**
     * Check for plagiarism
     */
    private function checkPlagiarism($answers)
    {
        // Implement plagiarism detection logic
        // This could integrate with external services
        return rand(0, 20); // Placeholder
    }
}