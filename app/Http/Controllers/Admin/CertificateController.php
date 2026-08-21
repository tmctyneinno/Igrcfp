<?php

// app/Http/Controllers/Admin/CertificateController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Admin;
use App\Models\Course;
use App\Models\AssessmentSubmission;
use App\Services\ActivityLoggerService;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;

class CertificateController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $admin = Auth::guard('admin')->user();
            if (!$admin || !$admin->isAdmin()) {
                abort(403, 'Access denied. Only administrators can manage certificates.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of certificates
     */
    public function index(Request $request)
    {
        $query = Enrollment::query()
            ->with(['user', 'course']);

        // Filter by certificate generated status
        if ($request->has('certificate_generated')) {
            $query->where('certificate_generated', $request->certificate_generated);
        }

        // Filter by enrollment status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by certificate status
        if ($request->filled('certificate_status')) {
            $query->where('certificate_status', $request->certificate_status);
        }

        // Search by student name, email, or certificate number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('course', function($courseQuery) use ($search) {
                      $courseQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('certificate_generated_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('certificate_generated_date', '<=', $request->date_to);
        }

        $certificates = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Get courses for filter dropdown
        $courses = Course::select('id', 'title')->orderBy('title')->get();

        // Statistics
        $statistics = $this->getStatistics();

        return view('admin.certificates.index', compact('certificates', 'statistics', 'courses'));
    }

    /**
     * Show certificate details
     */
    public function show(Enrollment $enrollment)
    {
        $enrollment->load([
            'user', 
            'course', 
            'certificateStatusUpdatedBy',
            'assessmentSubmissions.assessment'
        ]);

        // Get verification history from activity logs
        $verificationHistory = ActivityLog::where('subject_type', Enrollment::class)
            ->where('subject_id', $enrollment->id)
            ->where('module', 'certificates')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get available certificate statuses
        $certificateStatuses = Enrollment::getCertificateStatuses();

        return view('admin.certificates.show', compact('enrollment', 'verificationHistory', 'certificateStatuses'));
    }

    /**
     * Preview a generated certificate as a PDF in-browser.
     */
    public function preview(Enrollment $enrollment)
    {
        return $this->renderCertificatePdf($enrollment, 'inline');
    }

    /**
     * Download a generated certificate as a PDF.
     */
    public function download(Enrollment $enrollment)
    {
        return $this->renderCertificatePdf($enrollment, 'attachment');
    }


    protected function renderCertificatePdf(Enrollment $enrollment, string $disposition = 'inline')
    {
        if (!$enrollment->certificate_generated || empty($enrollment->certificate_number)) {
            abort(404, 'Certificate not generated yet.');
        }

        $data = [
            'student' => $enrollment->user,
            'course' => $enrollment->course,
            'enrollment' => $enrollment,
            'completion_date' => ($enrollment->certificate_generated_date ?? now())->format('F d, Y'),
            'certificate_number' => $enrollment->certificate_number,
            'instructor_name' => $enrollment->course->instructor->name ?? 'Course Instructor',
            'verification_url' => $enrollment->verification_url,
        ];

        $pdf = Pdf::loadView('certificates.template', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'dpi' => 100,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
        ]);

        $filename = 'certificate-' . ($enrollment->course->slug ?? 'course') . '.pdf';
        $response = response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf');

        if ($disposition === 'attachment') {
            return $response->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }

        return $response->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    /**
     * Show form to generate certificate
     */
    public function edit(Enrollment $enrollment)
    {
        $enrollment->load([
            'user', 
            'course',
            'assessmentSubmissions.assessment'
        ]);

        return view('admin.certificates.edit', compact('enrollment'));
    }

    /**
     * Generate certificate for enrollment
     */
    public function generate(Request $request, Enrollment $enrollment)
    {
        $admin = Auth::guard('admin')->user();

        // Validate
        $request->validate([
            'final_grade' => 'nullable|string|max:50',
            'admin_notes' => 'nullable|string|max:500',
            'use_calculated_grade' => 'nullable|boolean',
            'assessment_submission_id' => 'nullable|integer',
        ]);

        // When generation is initiated from an assessment result, the exact
        // final score must be graded and meet the certificate threshold.
        if ($request->filled('assessment_submission_id')) {
            $submission = $enrollment->assessmentSubmissions()
                ->whereKey($request->integer('assessment_submission_id'))
                ->first();

            if (!$submission || $submission->status !== 'graded' || $submission->percentage === null) {
                return back()->with('error', 'A graded final score is required before a certificate can be generated.');
            }

            if ((float) $submission->percentage < 75) {
                return back()->with('error', 'A final score of at least 75% is required before a certificate can be generated.');
            }
        }

        if ($enrollment->hasIssuedCertificate()) {
            return back()->with('error', 'A certificate has already been generated for this enrollment.');
        }

        // Get grade from assessments if not manually specified
        if ($request->boolean('use_calculated_grade', true) && !$request->filled('final_grade')) {
            $grade = $enrollment->calculateFinalGrade();
            $gradeSource = 'assessment_calculated';
        } else {
            $grade = $request->final_grade ?? 'Pass';
            $gradeSource = 'manual';
        }

        // Get grade breakdown for logging
        $gradeBreakdown = $enrollment->getGradeBreakdownAttribute();

        // Generate certificate number if not exists
        if (!$enrollment->certificate_number) {
            $enrollment->generateCertificateNumber();
        }

        // Generate certificate
        $enrollment->generateCertificate($grade, $request->admin_notes, $admin->id);

        // Log certificate generation with grade details
        ActivityLoggerService::log(
            ActivityLog::EVENT_CREATED,
            'certificates',
            'Certificate generated by admin',
            "Admin {$admin->name} generated certificate for {$enrollment->user->name} - {$enrollment->course->title} (Grade: {$grade})",
            $enrollment,
            [
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'certificate_number' => $enrollment->certificate_number,
                'student_name' => $enrollment->user->name,
                'student_email' => $enrollment->user->email,
                'course_title' => $enrollment->course->title,
                'final_grade' => $grade,
                'grade_source' => $gradeSource,
                'overall_percentage' => $gradeBreakdown['overall_percentage'],
                'graded_submissions' => $gradeBreakdown['graded_submissions'],
                'passed_submissions' => $gradeBreakdown['passed_submissions'],
                'ip' => $request->ip()
            ],
            ActivityLog::SEVERITY_INFO
        );

        // Send notification to student
        try {
            \App\Models\Notification::create([
                'user_id' => $enrollment->user_id,
                'type' => 'certificate_generated',
                'title' => 'Certificate Generated! 🎓',
                'message' => "Congratulations! Your certificate for '{$enrollment->course->title}' has been generated with grade: {$grade}.",
                'data' => [
                    'enrollment_id' => $enrollment->id,
                    'course_slug' => $enrollment->course->slug,
                    'certificate_number' => $enrollment->certificate_number,
                    'grade' => $grade,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send certificate notification: ' . $e->getMessage());
        }

        // Send email notification
        try {
            if (class_exists(\App\Mail\CertificateGeneratedMail::class)) {
                \Mail::to($enrollment->user->email)->send(new \App\Mail\CertificateGeneratedMail($enrollment));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send certificate email: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.certificates.show', $enrollment)
            ->with('success', "Certificate generated successfully for {$enrollment->user->name}. Grade: {$grade}. Certificate #: {$enrollment->certificate_number}");
    }

    /**
     * Update certificate status
     */
    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'status' => 'required|in:active,revoked,expired,suspended,pending',
            'revocation_reason' => 'required_if:status,revoked|nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $enrollment->certificate_status ?? 'pending';
        $newStatus = $request->status;

        // Update certificate status
        $enrollment->updateCertificateStatus($newStatus, $request->revocation_reason, $admin->id);

        // Update notes if provided
        if ($request->filled('admin_notes')) {
            $enrollment->update([
                'notes' => $enrollment->notes . "\n[Certificate Status Update - " . now()->format('Y-m-d H:i') . " by {$admin->name}]: Status changed from {$oldStatus} to {$newStatus}. Notes: " . $request->admin_notes
            ]);
        }

        // Log status change
        $severity = $newStatus === 'revoked' ? ActivityLog::SEVERITY_WARNING : ActivityLog::SEVERITY_INFO;
        
        ActivityLoggerService::log(
            ActivityLog::EVENT_STATUS_CHANGE,
            'certificates',
            'Certificate status updated',
            "Admin {$admin->name} changed certificate status from '{$oldStatus}' to '{$newStatus}' for {$enrollment->user->name}",
            $enrollment,
            [
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'certificate_number' => $enrollment->certificate_number,
                'student_name' => $enrollment->user->name,
                'student_email' => $enrollment->user->email,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'revocation_reason' => $request->revocation_reason,
                'ip' => $request->ip()
            ],
            $severity
        );

        // Notify student of status change
        $this->notifyStudentOfStatusChange($enrollment, $oldStatus, $newStatus, $request->revocation_reason);

        $statusLabel = ucfirst($newStatus);
        $message = "Certificate status updated to '{$statusLabel}' successfully.";
        
        if ($newStatus === 'active' && $oldStatus === 'revoked') {
            $message = "Certificate has been reactivated successfully.";
        }

        return redirect()
            ->route('admin.certificates.show', $enrollment)
            ->with('success', $message);
    }

    /**
     * Bulk generate certificates
     */
    public function bulkGenerate(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'enrollment_ids' => 'required|string',
        ]);

        $enrollmentIds = json_decode($request->enrollment_ids, true);

        if (!is_array($enrollmentIds) || empty($enrollmentIds)) {
            return redirect()
                ->route('admin.certificates.index')
                ->with('error', 'No enrollments selected.');
        }

        $count = 0;
        $failed = 0;
        $generatedCertificates = [];

        foreach ($enrollmentIds as $enrollmentId) {
            $enrollment = Enrollment::find($enrollmentId);
            
            if ($enrollment && !$enrollment->certificate_generated) {
                try {
                    $grade = $enrollment->calculateFinalGrade();
                    
                    // Generate certificate number
                    if (!$enrollment->certificate_number) {
                        $enrollment->generateCertificateNumber();
                    }
                    
                    // Generate certificate
                    $enrollment->generateCertificate($grade, 'Bulk generated by ' . $admin->name, $admin->id);
                    
                    $generatedCertificates[] = [
                        'id' => $enrollment->id,
                        'student' => $enrollment->user->name,
                        'certificate_number' => $enrollment->certificate_number,
                        'grade' => $grade,
                    ];
                    
                    $count++;
                } catch (\Exception $e) {
                    \Log::error("Failed to generate certificate for enrollment #{$enrollmentId}: " . $e->getMessage());
                    $failed++;
                }
            }
        }

        // Log bulk generation
        ActivityLoggerService::log(
            ActivityLog::EVENT_CREATED,
            'certificates',
            'Bulk certificates generated',
            "Admin {$admin->name} generated {$count} certificates in bulk",
            null,
            [
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'certificates_generated' => $count,
                'failed' => $failed,
                'total_attempted' => count($enrollmentIds),
                'generated_certificates' => $generatedCertificates,
                'ip' => $request->ip()
            ],
            ActivityLog::SEVERITY_INFO
        );

        $message = "{$count} certificates generated successfully.";
        if ($failed > 0) {
            $message .= " {$failed} failed. Check logs for details.";
        }

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', $message);
    }

    /**
     * Get certificate statistics
     */
    private function getStatistics(): array
    {
        return [
            'total_certificates' => Enrollment::where('certificate_generated', true)->count(),
            'today_generated' => Enrollment::where('certificate_generated', true)
                ->whereDate('certificate_generated_date', today())
                ->count(),
            'pending_certificates' => Enrollment::where('certificate_generated', false)
                ->where('status', 'completed')
                ->count(),
            'total_verified' => 0, // Will be updated once certificate_verified column is added
            'active_certificates' => Enrollment::where('certificate_generated', true)
                ->where('certificate_status', Enrollment::CERT_STATUS_ACTIVE)
                ->count(),
            'revoked_certificates' => Enrollment::where('certificate_status', Enrollment::CERT_STATUS_REVOKED)
                ->count(),
            'expired_certificates' => Enrollment::where('certificate_status', Enrollment::CERT_STATUS_EXPIRED)
                ->count(),
        ];
    }

    /**
     * Notify student of certificate status change
     */
    private function notifyStudentOfStatusChange(Enrollment $enrollment, string $oldStatus, string $newStatus, ?string $reason = null): void
    {
        try {
            $notificationData = [
                'user_id' => $enrollment->user_id,
                'type' => 'certificate_status_changed',
                'data' => [
                    'enrollment_id' => $enrollment->id,
                    'certificate_number' => $enrollment->certificate_number,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ],
            ];

            if ($newStatus === 'revoked') {
                $notificationData['title'] = 'Certificate Status Updated';
                $notificationData['message'] = "Your certificate for '{$enrollment->course->title}' has been revoked. Reason: {$reason}. Please contact support for more information.";
                $notificationData['data']['reason'] = $reason;
            } elseif ($newStatus === 'active' && $oldStatus === 'revoked') {
                $notificationData['title'] = 'Certificate Reactivated';
                $notificationData['message'] = "Your certificate for '{$enrollment->course->title}' has been reactivated.";
            } elseif ($newStatus === 'suspended') {
                $notificationData['title'] = 'Certificate Suspended';
                $notificationData['message'] = "Your certificate for '{$enrollment->course->title}' has been temporarily suspended. Please contact support for more information.";
            } else {
                $notificationData['title'] = 'Certificate Status Updated';
                $notificationData['message'] = "Your certificate status for '{$enrollment->course->title}' has been updated to: " . ucfirst($newStatus);
            }

            \App\Models\Notification::create($notificationData);
        } catch (\Exception $e) {
            \Log::error('Failed to send certificate status notification: ' . $e->getMessage());
        }
    }
}
