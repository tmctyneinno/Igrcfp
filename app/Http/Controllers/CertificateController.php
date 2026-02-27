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

        // Generate certificate number if not exists
        if (!$enrollment->certificate_number) {
            $enrollment->certificate_number = $this->generateCertificateNumber($enrollment);
        }

        // Set certificate as generated
        $enrollment->certificate_generated = true;
        $enrollment->certificate_generated_date = now();
        $enrollment->save();

        // Prepare data for certificate
        $data = [
            'student' => auth()->user(),
            'course' => $enrollment->course,
            'enrollment' => $enrollment,
            'completion_date' => now()->format('F d, Y'),
            'certificate_number' => $enrollment->certificate_number
        ];

        // Load view and generate PDF
        $pdf = Pdf::loadView('certificates.template', $data);
        
        // Download the PDF
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
            'course' => $enrollment->course,
            'completion_date' => $enrollment->certificate_generated_date 
                ? $enrollment->certificate_generated_date->format('F d, Y')
                : now()->format('F d, Y')
        ]);
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
            'certificate_number' => $enrollment->certificate_number
        ];

        $pdf = Pdf::loadView('certificates.template', $data);
        
        return $pdf->download('certificate-'.$enrollment->course->slug.'.pdf');
    }

    private function generateCertificateNumber($enrollment)
    {
        return 'CERT-' . date('Y') . '-' . str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
    }
}