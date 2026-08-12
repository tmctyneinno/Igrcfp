<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        // Load relationships efficiently
        $enrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->paginate(15);

        // Initialize Stage Counters
        $stageCounts = [
            'not_started' => 0,
            'quiz_stage' => 0,
            'essay_stage' => 0,
            'completed' => 0,
        ];

        // Enrich enrollments with Assessment Stage and Count them
        $enrollments->getCollection()->transform(function ($enrollment) use (&$stageCounts) {
            $stageInfo = $this->getAssessmentStageDetailed($enrollment);
            $enrollment->assessment_stage_label = $stageInfo['label'];
            $enrollment->assessment_stage_key = $stageInfo['key'];
            
            // Increment counter
            if (isset($stageCounts[$stageInfo['key']])) {
                $stageCounts[$stageInfo['key']]++;
            }
            
            return $enrollment;
        });

        // Get standard summary data
        $summary = [
            'total' => Enrollment::count(),
            'pending_payment' => Enrollment::where('status', 'pending_payment')->count(),
            'enrolled' => Enrollment::where('status', 'enrolled')->count(),
            'completed_enrollment' => Enrollment::where('status', 'completed')->count(),
            'cancelled' => Enrollment::where('status', 'cancelled')->count(),
        ]; 
            
        return view('admin.enrollments.index', compact('enrollments', 'summary', 'stageCounts'));
    }

    /**
     * Determine the current assessment stage for an enrollment
     */
    private function getAssessmentStageDetailed($enrollment)
    {
        $userId = $enrollment->user_id;
        $courseId = $enrollment->course_id;

        // 1. Check for Quiz (Part A)
        $quiz = Assessment::where('course_id', $courseId)
            ->where('assessment_level', 'quiz')
            ->first();

        if (!$quiz) {
            return ['label' => 'No Assessments', 'key' => 'not_started'];
        }

        // Once a learner has passed, a later retake must not move them back
        // to the quiz stage.
        $quizSubmission = AssessmentSubmission::where('assessment_id', $quiz->id)
            ->where('user_id', $userId)
            ->where('passed', true)
            ->where('percentage', '>=', 50)
            ->latest()
            ->first();

        // If no passing attempt exists, use the latest attempt to distinguish
        // between an unstarted assessment and one that needs a retry.
        if (!$quizSubmission) {
            $latestQuizSubmission = AssessmentSubmission::where('assessment_id', $quiz->id)
                ->where('user_id', $userId)
                ->latest()
                ->first();

            return $latestQuizSubmission
                ? ['label' => 'Quiz (Failed/Retry)', 'key' => 'quiz_stage']
                : ['label' => 'Not Started', 'key' => 'not_started'];
        }

        // 2. Check for Essay/Project (Part B)
        $essayAssessment = Assessment::where('course_id', $courseId)
            ->whereIn('assessment_level', ['diploma', 'project', 'module_assessment'])
            ->whereHas('questions', function($q) {
                $q->where('question_type', 'essay');
            })
            ->first();

        if (!$essayAssessment) {
            return ['label' => 'Quiz Passed', 'key' => 'completed'];
        }

        $passedEssaySubmission = AssessmentSubmission::where('assessment_id', $essayAssessment->id)
            ->where('user_id', $userId)
            ->where('status', 'graded')
            ->where('passed', true)
            ->where('percentage', '>=', 50)
            ->latest()
            ->first();

        if ($passedEssaySubmission) {
            return ['label' => 'Assessment Completed', 'key' => 'completed'];
        }

        $essaySubmission = AssessmentSubmission::where('assessment_id', $essayAssessment->id)
            ->where('user_id', $userId)
            ->latest()
            ->first();

        if (!$essaySubmission) {
            return ['label' => 'Ready for Essay', 'key' => 'essay_stage'];
        }

        if ($essaySubmission->status === 'submitted' || $essaySubmission->status === 'in_progress') {
            return ['label' => 'Essay Under Review', 'key' => 'essay_stage'];
        }

        if ($essaySubmission->status === 'graded') {
            return ['label' => 'Essay (Failed/Retry)', 'key' => 'essay_stage'];
        }

        return ['label' => 'Quiz Passed', 'key' => 'quiz_stage'];
    }
    

    public function export(Request $request)
    {
        $query = Enrollment::with(['user', 'course', 'transaction']);

        // Apply filters if any
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('course', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->latest()->get();

        // Generate CSV
        $filename = 'enrollments-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'ID', 'Student Name', 'Student Email', 'Course Title', 
            'Enrollment Date', 'Amount', 'Payment Status', 'Enrollment Status', 'Progress'
        ];

        $callback = function() use ($enrollments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($enrollments as $enrollment) {
                fputcsv($file, [
                    $enrollment->id,
                    $enrollment->user->name ?? 'N/A',
                    $enrollment->user->email ?? 'N/A',
                    $enrollment->course->title ?? 'N/A',
                    $enrollment->created_at->format('Y-m-d H:i:s'),
                    $enrollment->amount ?? 0,
                    $enrollment->transaction->status ?? 'No Payment',
                    $enrollment->status, 
                    ($enrollment->progress ?? 0) . '%',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function pending()
    {
        $enrollments = Enrollment::with(['user', 'course', 'transaction'])
            ->where('status', 'pending_payment')
            ->latest()
            ->paginate(15);
            
        return view('admin.enrollments.pending', compact('enrollments'));
    }

    public function completed()
    {
        $enrollments = Enrollment::with(['user', 'course', 'transaction'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);
            
        return view('admin.enrollments.completed', compact('enrollments'));
    }

    public function cancelled(Request $request)
{
    $query = Enrollment::with(['user', 'course', 'transaction'])
        ->where('status', 'cancelled');

    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('user', function($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('course', function($courseQuery) use ($search) {
                $courseQuery->where('title', 'like', "%{$search}%");
            });
        });
    }

    // Apply refund status filter
    if ($request->filled('refund_status')) {
        if ($request->refund_status === 'refunded') {
            $query->whereHas('transaction', function($q) {
                $q->where('status', 'refunded');
            });
        } elseif ($request->refund_status === 'pending_refund') {
            $query->whereHas('transaction', function($q) {
                $q->where('status', 'pending_refund');
            });
        } elseif ($request->refund_status === 'no_refund') {
            $query->whereDoesntHave('transaction', function($q) {
                $q->whereIn('status', ['refunded', 'pending_refund']);
            });
        }
    }

    $enrollments = $query->latest()->paginate($request->per_page ?? 15);

    // Calculate summary data
    $totalRefunded = Enrollment::where('status', 'cancelled')
        ->whereHas('transaction', function($q) {
            $q->where('status', 'refunded');
        })
        ->sum('amount');

    $totalEnrollments = Enrollment::count();
    $cancelledCount = Enrollment::where('status', 'cancelled')->count();
    $cancellationRate = $totalEnrollments > 0 
        ? round(($cancelledCount / $totalEnrollments) * 100, 2) 
        : 0;

    return view('admin.enrollments.cancelled', compact(
        'enrollments', 
        'totalRefunded', 
        'cancellationRate'
    ));
}
 
    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['user', 'course', 'transaction']);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $enrollment->update(['status' => $request->status]);
        
        return redirect()->back()->with('success', 'Enrollment status updated successfully.');
    }

    public function destroy(Enrollment $enrollment)
    {
        
        if ($enrollment->transaction) {
            // Example: If you want to delete the transaction record as well
            $enrollment->transaction->delete(); 
            
            // Or if you use a refund system:
            $enrollment->transaction->update(['status' => 'refunded']);
        }

        // Delete the enrollment
        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment deleted successfully.');
    }
}
