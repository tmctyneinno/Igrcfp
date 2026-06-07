<?php

namespace App\Traits;

use App\Mail\ScholarshipUnderReview;
use App\Mail\ScholarshipApproved;
use App\Mail\ScholarshipRejected;
use App\Models\ScholarshipApplication;
use App\Services\BrevoMailService;
use Illuminate\Support\Facades\Log;

trait SendsScholarshipNotifications
{ 
    /**
     * Send under review notification to applicant
     */
    protected function sendUnderReviewNotification(ScholarshipApplication $application, BrevoMailService $mailService): array
    {
        try {
            $mailService->sendMailable(
                $application->email,
                new ScholarshipUnderReview($application),
                'Scholarship Application Under Review - IGRCFP'
            );
            
            Log::info('Scholarship under review notification sent', [
                'application_id' => $application->id,
                'email' => $application->email
            ]);
            
            return ['success' => true, 'message' => 'Under review notification sent successfully'];
        } catch (\Exception $e) {
            Log::error('Failed to send scholarship under review email: ' . $e->getMessage(), [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send approval notification to applicant
     */
    protected function sendApprovalNotification(ScholarshipApplication $application, BrevoMailService $mailService): array
    {
        try {
            $mailService->sendMailable(
                $application->email,
                new ScholarshipApproved($application),
                'Congratulations! Your IGRCFP Scholarship Has Been Approved'
            );
            
            Log::info('Scholarship approval notification sent', [
                'application_id' => $application->id,
                'email' => $application->email,
                'scholarship_type' => $application->scholarship_type ?? 'Not specified'
            ]);
            
            return ['success' => true, 'message' => 'Approval notification sent successfully'];
        } catch (\Exception $e) {
            Log::error('Failed to send scholarship approval email: ' . $e->getMessage(), [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send rejection notification to applicant
     */
    protected function sendRejectionNotification(ScholarshipApplication $application, BrevoMailService $mailService, ?string $reason = null): array
    {
        try {
            $mailService->sendMailable(
                $application->email,
                new ScholarshipRejected($application, $reason),
                'Update on Your IGRCFP Scholarship Application'
            );
            
            Log::info('Scholarship rejection notification sent', [
                'application_id' => $application->id,
                'email' => $application->email,
                'reason' => $reason
            ]);
            
            return ['success' => true, 'message' => 'Rejection notification sent successfully'];
        } catch (\Exception $e) {
            Log::error('Failed to send scholarship rejection email: ' . $e->getMessage(), [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send notification based on status change
     */
    protected function sendStatusChangeNotification(
        ScholarshipApplication $application, 
        BrevoMailService $mailService, 
        string $oldStatus, 
        string $newStatus,
        ?string $rejectionReason = null  // Added parameter for rejection reason
    ): void {
        // Only send notification if the status has actually changed
        if ($oldStatus === $newStatus) {
            Log::info('Status unchanged, no notification sent', [
                'application_id' => $application->id,
                'status' => $oldStatus
            ]);
            return;
        }
        
        $notificationSent = false;
        
        if ($newStatus === 'under_review') {
            $result = $this->sendUnderReviewNotification($application, $mailService);
            $notificationSent = $result['success'];
        }
        
        if ($newStatus === 'accepted') {
            $result = $this->sendApprovalNotification($application, $mailService);
            $notificationSent = $result['success'];
        }
        
        if ($newStatus === 'rejected') {
            // Fixed: Now actually sending rejection notification with reason
            $result = $this->sendRejectionNotification($application, $mailService, $rejectionReason);
            $notificationSent = $result['success'];
        }
        
        if (!$notificationSent && in_array($newStatus, ['accepted', 'under_review', 'rejected'])) {
            Log::warning('Failed to send scholarship status notification', [
                'application_id' => $application->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);
        }
    }
}