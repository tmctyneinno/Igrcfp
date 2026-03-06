<?php
// app/Http/Controllers/ExamController.php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ExamController extends Controller
{
    /**
     * Show identity verification page
     */
    public function showVerification(Enrollment $enrollment)
    {
        $this->authorize('access', $enrollment);

        return Inertia::render('Exam/Verification', [
            'enrollment' => $enrollment->load('course')
        ]);
    }

    /**
     * Verify identity with photo
     */
    public function verifyIdentity(Request $request, Enrollment $enrollment)
    {
        $this->authorize('access', $enrollment);

        $request->validate([
            'image' => 'required|string'
        ]);

        // Store verification image
        $imageData = $request->input('image');
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
        
        $filename = 'verifications/' . $enrollment->id . '/' . time() . '.jpg';
        Storage::disk('public')->put($filename, $image);

        // Update enrollment
        $enrollment->update([
            'identity_verified' => true,
            'verified_at' => now(),
            'verification_image' => $filename
        ]);

        return redirect()->route('dashboard.courses.show', $enrollment)
            ->with('success', 'Identity verified successfully');
    }

    /**
     * Start an exam
     */
    public function start(Enrollment $enrollment, Exam $exam)
    {
        $this->authorize('access', $enrollment);

        // Check if identity is verified
        if (!$enrollment->identity_verified) {
            return redirect()->back()->with('error', 'Please verify your identity first');
        }

        // Check if exam already exists
        $attempt = ExamAttempt::firstOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'exam_id' => $exam->id
            ],
            [
                'started_at' => now(),
                'expires_at' => now()->addMinutes($exam->duration),
                'status' => 'in_progress'
            ]
        );

        // If exam already completed, don't allow restart
        if ($attempt->status === 'completed') {
            return redirect()->back()->with('error', 'Exam already completed');
        }

        // Get randomized questions
        $questions = $exam->questions()
            ->inRandomOrder()
            ->get()
            ->map(fn($q) => [
                'id' => $q->id,
                'text' => $q->text,
                'options' => $q->options,
                'type' => $q->type
            ]);

        return Inertia::render('Exam/Taking', [
            'exam' => [
                'id' => $exam->id,
                'title' => $exam->title,
                'duration' => $exam->duration,
                'questions' => $questions,
                'total_questions' => $questions->count()
            ],
            'attempt' => [
                'id' => $attempt->id,
                'started_at' => $attempt->started_at,
                'expires_at' => $attempt->expires_at,
                'status' => $attempt->status
            ],
            'enrollment' => [
                'id' => $enrollment->id,
                'course_id' => $enrollment->course_id
            ]
        ]);
    }

    /**
     * Continue an in-progress exam
     */
    public function continue(ExamAttempt $attempt)
    {
        $this->authorize('access', $attempt->enrollment);

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('dashboard.courses.show', $attempt->enrollment)
                ->with('error', 'Exam is no longer in progress');
        }

        $exam = $attempt->exam;
        $enrollment = $attempt->enrollment;

        // Get questions (maintain order from original attempt)
        $questions = $exam->questions()
            ->whereIn('id', array_keys($attempt->answers ?? []))
            ->orWhereNotIn('id', array_keys($attempt->answers ?? []))
            ->get()
            ->map(fn($q) => [
                'id' => $q->id,
                'text' => $q->text,
                'options' => $q->options,
                'type' => $q->type
            ]);

        return Inertia::render('Exam/Taking', [
            'exam' => [
                'id' => $exam->id,
                'title' => $exam->title,
                'duration' => $exam->duration,
                'questions' => $questions,
                'total_questions' => $questions->count()
            ],
            'attempt' => [
                'id' => $attempt->id,
                'started_at' => $attempt->started_at,
                'expires_at' => $attempt->expires_at,
                'answers' => $attempt->answers ?? [],
                'status' => $attempt->status
            ],
            'enrollment' => [
                'id' => $enrollment->id,
                'course_id' => $enrollment->course_id
            ]
        ]);
    }

    /**
     * Authorize that user owns this enrollment
     */
    private function authorize(string $ability, $enrollment)
    {
        if ($enrollment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }
    }

    public function show(Enrollment $enrollment)
    {
        // Check if user owns this enrollment
        if ($enrollment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        // Get the exam details
        $exam = Exam::where('course_id', $enrollment->course_id)
            ->with(['questions' => function($query) {
                $query->inRandomOrder();
            }])
            ->first();

        if (!$exam) {
            return redirect()->back()->with('error', 'No exam found for this course');
        }

        return Inertia::render('Dashboard/Course/Exam', [
            'enrollment' => [
                'id' => $enrollment->id,
                'course_id' => $enrollment->course_id,
                'user_id' => $enrollment->user_id,
                'status' => $enrollment->status,
                'identity_verified' => $enrollment->identity_verified ?? false,
            ],
            'exam' => [
                'id' => $exam->id,
                'title' => $exam->title,
                'description' => $exam->description,
                'duration' => $exam->duration,
                'total_questions' => $exam->questions->count(),
                'questions' => $exam->questions->map(function($question) {
                    return [
                        'id' => $question->id,
                        'text' => $question->text,
                        'type' => $question->type,
                        'options' => $question->options,
                        'points' => $question->points ?? 1,
                    ];
                }),
            ],
            'time_limit' => now()->addMinutes($exam->duration)->toIso8601String(),
        ]);
    }

}