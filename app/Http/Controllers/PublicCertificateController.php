<?php
// app/Http/Controllers/PublicCertificateController.php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Certificate;
use App\Services\ActivityLoggerService;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicCertificateController extends Controller
{
    /**
     * Show the public certificate verification page
     */
    public function index()
    {
        return Inertia::render('Certificate/PublicVerify');
    }

    /**
     * Verify a certificate by its number
     */
    public function verify(Request $request)
    {
        $request->validate([
            'certificate_number' => 'required|string|min:10|max:50',
        ]);

        $certificateNumber = trim(strtoupper($request->certificate_number));

        // Try to find by Enrollment certificate_number first
        $enrollment = Enrollment::where('certificate_number', $certificateNumber)
            ->where('certificate_generated', true)
            ->with(['user', 'course'])
            ->first();

        if ($enrollment) {
            // Log verification attempt
            ActivityLoggerService::log(
                ActivityLog::EVENT_UPDATED,
                'certificates',
                'Certificate verified',
                "Certificate {$certificateNumber} verified successfully",
                $enrollment,
                [
                    'certificate_number' => $certificateNumber,
                    'ip' => $request->ip(),
                    'verification_source' => 'public'
                ],
                ActivityLog::SEVERITY_INFO
            );

            return Inertia::render('Certificate/PublicVerify', [
                'valid' => true,
                'certificate' => [
                    'number' => $enrollment->certificate_number,
                    'issue_date' => $enrollment->certificate_generated_date?->format('F d, Y'),
                    'recipient_name' => $enrollment->user->name,
                    'recipient_email' => $this->maskEmail($enrollment->user->email),
                    'course_title' => $enrollment->course->title,
                    'course_code' => $enrollment->course->code ?? 'N/A',
                    'grade' => $enrollment->final_grade ?? 'Pass',
                    'status' => $enrollment->certificate_status === Enrollment::CERT_STATUS_ACTIVE
                        ? 'Valid'
                        : $enrollment->certificate_status_display,
                    'completion_date' => $enrollment->completed_at?->format('F d, Y'),
                    'issuing_body' => 'IGRCFP',
                    'issuing_body_full' => 'The Institute of Governance, Risk, Compliance & Financial Crime Prevention',
                    'verification_url' => route('certificate.verify.public.index', ['number' => $enrollment->certificate_number]),
                    'badge_url' => asset('images/badges/verified-badge.png'),
                ]
            ]);
        } 

        // Try Certificate model as fallback
        $certificate = Certificate::where('certificate_number', $certificateNumber)
            ->with(['enrollment.user', 'enrollment.course'])
            ->first();

        if ($certificate) {
            // Log verification attempt
            ActivityLoggerService::log(
                ActivityLog::EVENT_UPDATED,
                'certificates',
                'Certificate verified (legacy)',
                "Legacy certificate {$certificateNumber} verified",
                $certificate,
                [
                    'certificate_number' => $certificateNumber,
                    'ip' => $request->ip(),
                    'verification_source' => 'public'
                ],
                ActivityLog::SEVERITY_INFO
            );

            return Inertia::render('Certificate/PublicVerify', [
                'valid' => true,
                'certificate' => [
                    'number' => $certificate->certificate_number,
                    'issue_date' => $certificate->issue_date?->format('F d, Y'),
                    'recipient_name' => $certificate->enrollment->user->name,
                    'recipient_email' => $this->maskEmail($certificate->enrollment->user->email),
                    'course_title' => $certificate->enrollment->course->title,
                    'course_code' => $certificate->enrollment->course->code ?? 'N/A',
                    'grade' => $certificate->grade ?? 'Pass',
                    'status' => $certificate->status === 'active' ? 'Valid' : ucfirst($certificate->status),
                    'issuing_body' => 'IGRCFP',
                    'issuing_body_full' => 'The Institute of Governance, Risk, Compliance & Financial Crime Prevention',
                ]
            ]);
        }

        // Log failed verification attempt
        ActivityLoggerService::log(
            ActivityLog::EVENT_UPDATED,
            'certificates',
            'Certificate verification failed',
            "Invalid certificate number attempted: {$certificateNumber}",
            null,
            [
                'certificate_number' => $certificateNumber,
                'ip' => $request->ip(),
                'verification_source' => 'public',
                'reason' => 'not_found'
            ],
            ActivityLog::SEVERITY_WARNING
        );

        return Inertia::render('Certificate/PublicVerify', [
            'valid' => false,
            'searched_number' => $certificateNumber,
            'message' => 'No valid certificate found with this number. Please verify the certificate number and try again.'
        ]);
    }

    /**
     * Verify certificate via direct URL (for QR codes and links)
     */
    public function verifyByNumber($certificateNumber)
    {
        $request = new Request(['certificate_number' => $certificateNumber]);
        return $this->verify($request);
    }

    /**
     * Mask email for public display
     */
    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) return $email;
        
        $name = $parts[0];
        $domain = $parts[1];
        
        $maskedName = substr($name, 0, 2) . str_repeat('*', max(strlen($name) - 4, 3)) . substr($name, -2);
        
        return $maskedName . '@' . $domain;
    }
}