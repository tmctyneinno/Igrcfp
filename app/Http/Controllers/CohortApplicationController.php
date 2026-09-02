<?php

namespace App\Http\Controllers;

use App\Http\Requests\CohortApplicationRequest;
use App\Mail\CohortApplicationAdminNotification;
use App\Mail\CohortApplicationSubmitted;
use App\Models\CohortApplication;
use App\Services\BrevoMailService;
use Illuminate\Support\Facades\Log;

class CohortApplicationController extends Controller
{
    /**
     * Store a new cohort application submitted from the Apply modal.
     */
    public function store(CohortApplicationRequest $request, BrevoMailService $brevoMailService)
    {
        $application = CohortApplication::create([
            ...$request->validated(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            $applicantEmail = $application->email;
            $adminEmails = ['foluso.amusa@morgansconsortium.com', 'eshanokpe@gmail.com', 'enquiries@igrcfp.org'];
            
            // $adminEmails = config('mail.admissions_address', 'enquiries@igrcfp.org');
            $adminEmails = is_array($adminEmails)
                ? $adminEmails
                : array_map('trim', explode(',', (string) $adminEmails));
            $adminEmails = array_values(array_filter($adminEmails));

            $brevoMailService->sendMailable(
                $applicantEmail,
                new CohortApplicationSubmitted($application),
                'Your Cohort Application Has Been Received'
            );

            foreach ($adminEmails as $adminEmail) {
                $brevoMailService->sendMailable(
                    $adminEmail,
                    new CohortApplicationAdminNotification($application),
                    'New Cohort Application Received'
                );
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send cohort application emails via Brevo', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Your application has been received. We will be in touch by email shortly.');
    }
}