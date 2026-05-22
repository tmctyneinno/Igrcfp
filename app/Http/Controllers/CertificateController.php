<?php

namespace App\Http\Controllers;

use App\Models\Enrollment; 
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Certificate;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\CertificateGeneratedMail;

class CertificateController extends Controller
{
    
    public function generate(Enrollment $enrollment)
    {
        // Check if user is authorized
        if ($enrollment->user_id !== auth()->id()) {
            abort(403);
        }

        // Generate certificate number if not exists
        if (!$enrollment->certificate_number) {
            $enrollment->certificate_number = $this->generateCertificateNumber($enrollment);
        }

        // Mark certificate as generated
        if (!$enrollment->certificate_generated) {
            $enrollment->certificate_generated = true;
            $enrollment->certificate_generated_date = now();
            $enrollment->save();
        } 

        // ✅ Send notification (email/in-app)
        $this->sendCertificateNotification($enrollment);
        // ✅ Send email notification
        try {
            Mail::to($enrollment->user->email)->send(new CertificateGeneratedMail($enrollment));
        } catch (\Exception $e) {
            \Log::error('Failed to send certificate email: ' . $e->getMessage());
            // Don't block certificate generation if email fails
        }
        

        // Prepare data for the view
        $data = [
            'student' => auth()->user(),
            'course' => $enrollment->course,
            'enrollment' => $enrollment,
            'completion_date' => ($enrollment->certificate_generated_date ?? now())->format('F d, Y'),
            'certificate_number' => $enrollment->certificate_number,
            'instructor_name' => $enrollment->course->instructor->name ?? 'Course Instructor',
            'verification_url' => route('dashboard.certificate.verify', $enrollment->certificate_number)
        ]; 

        $pdf = PDF::loadView('certificates.template', $data);
        
        // ✅ Set paper size and orientation
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'dpi' => 100,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
             'isPhpEnabled' => true,
        ]);
        return $pdf->download('certificate-' . $enrollment->course->slug . '.pdf');
    }

    public function preview(Enrollment $enrollment)
    {
        if ($enrollment->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$enrollment->certificate_number) {
            $enrollment->certificate_number = $this->generateCertificateNumber($enrollment);
            $enrollment->save();
        }

        $data = [
            'student' => auth()->user(),
            'course' => $enrollment->course,
            'enrollment' => $enrollment,
            'completion_date' => ($enrollment->certificate_generated_date ?? now())->format('F d, Y'),
            'certificate_number' => $enrollment->certificate_number,
            'instructor_name' => $enrollment->course->instructor->name ?? 'Course Instructor',
            'verification_url' => route('dashboard.certificate.verify', $enrollment->certificate_number)
        ];

        $pdf = PDF::loadView('certificates.template', $data);
        $pdf->setPaper('A4', 'landscape');

        // ✅ Return as inline PDF (view in browser)
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="certificate-' . $enrollment->course->slug . '.pdf"');
    }

    public function download(Enrollment $enrollment)
    {
        if ($enrollment->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$enrollment->certificate_generated) {
            return back()->with('error', 'Certificate not found. Please generate it first.');
        }

        $data = [
            'student' => auth()->user(),
            'course' => $enrollment->course,
            'enrollment' => $enrollment,
            'completion_date' => $enrollment->certificate_generated_date->format('F d, Y'),
            'certificate_number' => $enrollment->certificate_number,
            'instructor_name' => $enrollment->course->instructor->name ?? 'Course Instructor',
            'verification_url' => route('dashboard.certificate.verify', $enrollment->certificate_number)
        ];

        $pdf = PDF::loadView('certificates.template', $data);
        $pdf->setPaper('A4', 'landscape');

        // ✅ Return as download with proper headers
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="certificate-' . $enrollment->course->slug . '.pdf"')
            ->header('Cache-Control', 'private, no-cache, must-revalidate');
    }


    private function generateCertificateNumber($enrollment)
    {
        return 'CERT-' . date('Y') . '-' . str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
    }

    public function badge(Enrollment $enrollment)
    {
        if ($enrollment->user_id !== auth()->id()) {
            abort(403);
        }

        // Generate badge data
        $badge = [
            'id' => $enrollment->certificate?->certificate_number,
            'name' => $enrollment->course->title . ' Badge',
            'image' => asset('images/badges/' . strtolower(str_replace(' ', '-', $enrollment->course->title)) . '.png'),
            'criteria' => route('dashboard.certificate.verify', $enrollment->certificate?->certificate_number),
            'issuer' => [
                'name' => 'IGRCFP',
                'url' => config('app.url')
            ]
        ];

        return Inertia::render('Certificate/Badge', [
            'badge' => $badge,
            'enrollment' => $enrollment
        ]);
    }

    /**
     * Verify certificate by number
     */
     public function verify($certificateNumber)
    {
        // Find enrollment by certificate number
        $enrollment = Enrollment::where('certificate_number', $certificateNumber)
            ->where('certificate_generated', true)
            ->with(['user', 'course'])
            ->first();

        if (!$enrollment) {
            // Log failed verification attempt
            if (class_exists(ActivityLoggerService::class)) {
                ActivityLoggerService::log(
                    ActivityLog::EVENT_UPDATED,
                    'certificates',
                    'Certificate verification failed',
                    "Invalid certificate number attempted: {$certificateNumber}",
                    null,
                    [
                        'certificate_number' => $certificateNumber,
                        'ip' => request()->ip(),
                        'reason' => 'not_found'
                    ],
                    ActivityLog::SEVERITY_WARNING
                );
            }

            return Inertia::render('Certificate/Verify', [
                'valid' => false,
                'message' => 'Certificate not found. Please check the certificate number and try again.'
            ]);
        }

        // Check if certificate is active
        $isValid = $enrollment->certificate_status === Enrollment::CERT_STATUS_ACTIVE;
        
        // Log successful verification
        if (class_exists(ActivityLoggerService::class)) {
            ActivityLoggerService::log(
                ActivityLog::EVENT_UPDATED,
                'certificates',
                'Certificate verified',
                "Certificate {$certificateNumber} verified successfully",
                $enrollment,
                [
                    'certificate_number' => $certificateNumber,
                    'ip' => request()->ip(),
                    'status' => $enrollment->certificate_status
                ],
                ActivityLog::SEVERITY_INFO
            );
        }

        return Inertia::render('Certificate/Verify', [
            'valid' => $isValid,
            'certificate' => [
                'number' => $enrollment->certificate_number,
                'issue_date' => $enrollment->certificate_generated_date?->format('F d, Y'),
                'recipient' => $enrollment->user->name,
                'course' => $enrollment->course->title,
                'grade' => $enrollment->final_grade ?? $this->calculateGrade($enrollment),
                'status' => $enrollment->certificate_status_display,
                'is_active' => $isValid,
            ]
        ]);
    }

    /**
     * Public certificate registry
     */
    public function registry(Request $request)
    {
        $query = Certificate::with(['enrollment.course', 'enrollment.user'])
            ->where('status', 'active');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('certificate_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('enrollment.user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('enrollment.course', function($q) use ($search) {
                      $q->where('title', 'LIKE', "%{$search}%");
                  });
            });
        }

        $certificates = $query->paginate(20);

        return Inertia::render('Certificate/Registry', [
            'certificates' => $certificates,
            'filters' => $request->only(['search'])
        ]);
    }

    /**
     * Generate certificate
     */
    private function generateCertificate(Enrollment $enrollment)
    {
        // Generate unique certificate number
        $certificateNumber = 'IGRCFP-' . strtoupper(uniqid()) . '-' . $enrollment->id;

        return Certificate::create([
            'enrollment_id' => $enrollment->id,
            'certificate_number' => $certificateNumber,
            'issue_date' => now(),
            'status' => 'active',
            'grade' => $this->calculateGrade($enrollment),
            'metadata' => [
                'generated_by' => 'system', 
                'verification_url' => route('dashboard.certificate.verify', $certificateNumber)
            ]
        ]);
    }

    /**
     * Calculate grade based on exam results
     */
    private function calculateGrade(Enrollment $enrollment): string
{
    // Check if Enrollment model has the calculateFinalGrade method
    if (method_exists($enrollment, 'calculateFinalGrade')) {
        return $enrollment->calculateFinalGrade();
    }

    // Fallback calculation using AssessmentSubmission
    $submissions = \App\Models\AssessmentSubmission::where('enrollment_id', $enrollment->id)
        ->where('status', 'graded')
        ->get();

    if ($submissions->isEmpty()) {
        return 'Pass';
    }

    $averagePercentage = $submissions->avg('percentage');
    
    if ($averagePercentage >= 90) {
        return 'Distinction';
    }
    if ($averagePercentage >= 75) {
        return 'Merit';
    }
    if ($averagePercentage >= 60) {
        return 'Pass';
    }
    if ($averagePercentage >= 40) {
        return 'Referral';
    }
    
    return 'Fail';
}

    /**
     * Send notification when certificate is generated
     */
    private function sendCertificateNotification(Enrollment $enrollment)
    {
        $user = $enrollment->user;
        $course = $enrollment->course;

        // Create in-app notification
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'certificate_generated',
            'title' => 'Certificate Generated! 🎓',
            'message' => "Congratulations! Your certificate for '{$course->title}' has been generated.",
            'data' => [
                'enrollment_id' => $enrollment->id,
                'course_slug' => $course->slug,
                'certificate_number' => $enrollment->certificate_number,
            ],
            'read_at' => null,
        ]);

        // ✅ Send email notification
        try {
            Mail::to($enrollment->user->email)->send(new CertificateGeneratedMail($enrollment));
        } catch (\Exception $e) {
            \Log::error('Failed to send certificate email: ' . $e->getMessage());
            // Don't block certificate generation if email fails
        }
    }

}