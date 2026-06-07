<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipApplication;
use App\Mail\ScholarshipAcceptedByUser;
use App\Services\BrevoMailService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ScholarshipAcceptController extends Controller
{
    protected $mailService;

    public function __construct(BrevoMailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function accept(ScholarshipApplication $application)
    {
        // Check if the application is already accepted
        if ($application->status !== 'accepted') {
            abort(404, 'This scholarship application is not in accepted status.');
        }
        
        // Check if already accepted by user
        if ($application->user_accepted) {
            return Inertia::render('Scholarship/Accepted', [
                'application' => $application,
                'alreadyAccepted' => true
            ]);
        }
        
        // Update the application to mark it as accepted by the user
        $application->update([
            'accepted_at' => now(),
            'user_accepted' => true,
        ]);
        
        // Send admin notification using BrevoMailService
        try {
            // Send to single admin email
            // $this->mailService->sendMailable(
            //     'scholarships@igrcfp.org',
            //     new ScholarshipAcceptedByUser($application),
            //     'Scholarship Accepted by Student - Action Required'
            // );
             
            // Optional: Send to multiple admins
            $adminEmails = ['foluso.amusa@morgansconsortium.com', 'eshanokpe@gmail.com', 'enquiries@igrcfp.org', 'scholarships@igrcfp.org'];
            foreach ($adminEmails as $adminEmail) {
                $this->mailService->sendMailable(
                    $adminEmail,
                    new ScholarshipAcceptedByUser($application),
                    'Scholarship Accepted by Student - Action Required'
                );
            }
            
            Log::info('Admin notification sent for scholarship acceptance', [
                'application_id' => $application->id,
                'student_email' => $application->email,
                'student_name' => $application->full_name
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification for scholarship acceptance', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
        }
        
        return Inertia::render('Scholarship/Accepted', [
            'application' => $application,
            'alreadyAccepted' => false
        ]);
    }
}