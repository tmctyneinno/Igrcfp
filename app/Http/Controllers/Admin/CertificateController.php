<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function generate(Enrollment $enrollment)
    {
        // Check if user is authorized
        if ($enrollment->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if course is completed
        if ($enrollment->progress < 100) {
            return back()->with('error', 'You need to complete the course first.');
        }

        // Mark certificate as generated
        $enrollment->update([
            'certificate_generated' => true,
            'certificate_generated_date' => now(),
            'certificate_number' => $this->generateCertificateNumber($enrollment)
        ]);

        // Generate PDF
        $pdf = PDF::loadView('certificates.template', [
            'student' => auth()->user(),
            'course' => $enrollment->course,
            'enrollment' => $enrollment,
            'completion_date' => now()->format('F d, Y'),
            'certificate_number' => $enrollment->certificate_number
        ]);

        return $pdf->download('certificate-'.$enrollment->course->slug.'.pdf');
    }

    public function preview(Enrollment $enrollment)
    {
        if ($enrollment->user_id !== auth()->id()) {
            abort(403);
        }

        return view('certificates.preview', [
            'enrollment' => $enrollment,
            'student' => auth()->user(),
            'course' => $enrollment->course
        ]);
    }

    public function download(Enrollment $enrollment)
    {
        if ($enrollment->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$enrollment->certificate_generated) {
            return back()->with('error', 'Certificate not found.');
        }

        $pdf = PDF::loadView('certificates.template', [
            'student' => auth()->user(),
            'course' => $enrollment->course,
            'enrollment' => $enrollment,
            'completion_date' => $enrollment->certificate_generated_date->format('F d, Y'),
            'certificate_number' => $enrollment->certificate_number
        ]);

        return $pdf->download('certificate-'.$enrollment->course->slug.'.pdf');
    }

    private function generateCertificateNumber($enrollment)
    {
        return 'CERT-' . strtoupper(uniqid()) . '-' . $enrollment->id;
    }
}