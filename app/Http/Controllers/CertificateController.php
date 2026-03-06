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

        // Mark certificate as generated
        $enrollment->certificate_generated = true;
        $enrollment->certificate_generated_date = now();
        $enrollment->save();

        // Prepare data for the view - MAKE SURE ALL VARIABLES ARE INCLUDED
        // Prepare data for the view
        $data = [
            'student' => auth()->user(),
            'course' => $enrollment->course,
            'enrollment' => $enrollment,
            'completion_date' => now()->format('F d, Y'),
            'certificate_number' => $enrollment->certificate_number,
            'organization_name' => config('app.name'), // or your organization name
            'organization_tagline' => 'Excellence in Education', // customize this
            'organization_logo' => public_path('images/logo.png'), // path to your logo
            'instructor_name' => $enrollment->course->instructor->name ?? 'Course Instructor',
            'verification_url' => route('certificates.verify', $enrollment->certificate_number)
        ];

        // Generate PDF
        $pdf = PDF::loadView('certificates.template', $data);

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
                : now()->format('F d, Y'),
            'certificate_number' => $enrollment->certificate_number ?? 'Pending' // ADD THIS
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

        // Prepare data for the view - MAKE SURE ALL VARIABLES ARE INCLUDED
        $data = [
            'student' => auth()->user(),
            'course' => $enrollment->course,
            'enrollment' => $enrollment,
            'completion_date' => $enrollment->certificate_generated_date->format('F d, Y'),
            'certificate_number' => $enrollment->certificate_number // THIS IS CRITICAL
        ];

        $pdf = PDF::loadView('certificates.template', $data);
        
        return $pdf->download('certificate-'.$enrollment->course->slug.'.pdf');
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
            'criteria' => route('certificate.verify', $enrollment->certificate?->certificate_number),
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
        $certificate = Certificate::where('certificate_number', $certificateNumber)
            ->with(['enrollment.course', 'enrollment.user'])
            ->first();

        if (!$certificate) {
            return Inertia::render('Certificate/Verify', [
                'valid' => false,
                'message' => 'Certificate not found'
            ]);
        }

        return Inertia::render('Certificate/Verify', [
            'valid' => true,
            'certificate' => [
                'number' => $certificate->certificate_number,
                'issue_date' => $certificate->created_at->format('F d, Y'),
                'recipient' => $certificate->enrollment->user->name,
                'course' => $certificate->enrollment->course->title,
                'grade' => $certificate->grade,
                'status' => $certificate->status
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
                'verification_url' => route('certificate.verify', $certificateNumber)
            ]
        ]);
    }

    /**
     * Calculate grade based on exam results
     */
    private function calculateGrade(Enrollment $enrollment)
    {
        $exams = $enrollment->examResults;
        
        if ($exams->isEmpty()) {
            return 'Pass';
        }

        $average = $exams->avg('score');
        
        if ($average >= 90) return 'Distinction';
        if ($average >= 75) return 'Merit';
        if ($average >= 60) return 'Pass';
        
        return 'Pass';
    }
}