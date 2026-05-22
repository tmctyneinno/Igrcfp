<?php
// app/Http/Controllers/ExamController.php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Services\ActivityLoggerService;
use App\Models\ActivityLog;
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
        $this->authorizeEnrollmentAccess($enrollment);

        return Inertia::render('Exam/Verification', [
            'enrollment' => $enrollment->load('course')
        ]);
    }

    /**
     * Verify identity with photo
     */
    public function verifyIdentity(Request $request, Enrollment $enrollment)
    {
        $this->authorizeEnrollmentAccess($enrollment);

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

        // Log identity verification
        ActivityLoggerService::log(
            ActivityLog::EVENT_UPDATED,
            'exams',
            'Identity verified for exam',
            "Identity verified for enrollment #{$enrollment->id} by user: " . auth()->user()->email,
            $enrollment,
            [
                'verification_image' => $filename,
                'verified_at' => now()->toDateTimeString(),
                'user_id' => auth()->id()
            ],
            ActivityLog::SEVERITY_INFO
        );

        return redirect()->route('dashboard.courses.show', $enrollment)
            ->with('success', 'Identity verified successfully');
    }

    /**
     * Start an exam
     */
    public function start(Enrollment $enrollment, Exam $exam)
    {
        $this->authorizeEnrollmentAccess($enrollment);

        // Check if identity is verified
        if (!$enrollment->identity_verified) {
            // Log attempt to start exam without verification
            ActivityLoggerService::log(
                ActivityLog::EVENT_LOGIN_FAILED,
                'exams',
                'Exam start blocked - identity not verified',
                "User attempted to start exam without identity verification: " . auth()->user()->email,
                $enrollment,
                [
                    'exam_id' => $exam->id,
                    'reason' => 'identity_not_verified',
                    'user_id' => auth()->id()
                ],
                ActivityLog::SEVERITY_WARNING
            );
            
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

        // Log exam started
        ActivityLoggerService::log(
            ActivityLog::EVENT_CREATED,
            'exams',
            'Exam started',
            "User " . auth()->user()->email . " started exam: {$exam->title}",
            $exam,
            [
                'attempt_id' => $attempt->id,
                'enrollment_id' => $enrollment->id,
                'started_at' => $attempt->started_at->toDateTimeString(),
                'expires_at' => $attempt->expires_at->toDateTimeString(),
                'user_id' => auth()->id()
            ],
            ActivityLog::SEVERITY_INFO
        );

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
        $this->authorizeEnrollmentAccess($attempt->enrollment);

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('dashboard.courses.show', $attempt->enrollment)
                ->with('error', 'Exam is no longer in progress');
        }

        $exam = $attempt->exam;
        $enrollment = $attempt->enrollment;

        // Log exam continued
        ActivityLoggerService::log(
            ActivityLog::EVENT_UPDATED,
            'exams',
            'Exam continued',
            "User " . auth()->user()->email . " continued exam: {$exam->title}",
            $exam,
            [
                'attempt_id' => $attempt->id,
                'enrollment_id' => $enrollment->id,
                'user_id' => auth()->id()
            ],
            ActivityLog::SEVERITY_INFO
        );

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
     * Show exam results
     */
    public function show(Enrollment $enrollment)
    {
        return Inertia::render('Dashboard/Courses/Exam');
    }

    /**
     * Authorize that the authenticated user owns this enrollment
     * Renamed from 'authorize' to avoid conflict with base Controller method
     */
    private function authorizeEnrollmentAccess($enrollment): void
    {
        if ($enrollment->user_id !== auth()->id()) {
            // Log unauthorized access attempt
            ActivityLoggerService::log(
                ActivityLog::EVENT_LOGIN_FAILED,
                'exams',
                'Unauthorized exam access attempt',
                'Unauthorized access attempt to enrollment by user: ' . (auth()->user()->email ?? 'Guest'),
                $enrollment,
                [
                    'enrollment_user_id' => $enrollment->user_id,
                    'attempted_user_id' => auth()->id(),
                    'ip' => request()->ip(),
                    'reason' => 'unauthorized_access'
                ],
                ActivityLog::SEVERITY_WARNING
            );
            
            abort(403, 'Unauthorized access');
        }
    }
}