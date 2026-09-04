<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CohortApplicationStatusUpdated;
use App\Models\CohortApplication;
use App\Services\BrevoMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CohortApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = CohortApplication::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('cohort', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(20);

        return view('admin.cohort-applications.index', compact('applications'));
    }

    public function show(CohortApplication $application)
    {
        return view('admin.cohort-applications.show', compact('application'));
    }

    public function updateStatus(Request $request, CohortApplication $application, BrevoMailService $mailService)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,reviewing,admitted,rejected,withdrawn',
            'admin_notes' => 'nullable|string|max:2000',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:2000',
        ]);

        $oldStatus = $application->status;
        $newStatus = $validated['status'];

        if ($oldStatus === $newStatus && ($request->admin_notes ?? null) === $application->admin_notes) {
            return redirect()->route('admin.cohort-applications.show', $application)
                ->with('info', 'No changes detected.');
        }

        $application->update([
            'status' => $newStatus,
            'reviewed_at' => ($newStatus === 'new') ? null : now(),
            'admin_notes' => $validated['admin_notes'] ?? $application->admin_notes,
            'rejection_reason' => $newStatus === 'rejected'
                ? ($validated['rejection_reason'] ?? $application->rejection_reason)
                : null,
        ]);

        if ($oldStatus !== $newStatus) {
            try {
                $this->sendStatusNotification($application, $mailService, $newStatus, $validated['rejection_reason'] ?? null);
            } catch (\Throwable $e) {
                Log::error('Failed to send cohort application status email', [
                    'application_id' => $application->id,
                    'status' => $newStatus,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $message = 'Application status updated successfully.';

        if ($newStatus === 'reviewing') {
            $message = 'Application marked as under review and the applicant has been notified.';
        } elseif ($newStatus === 'admitted') {
            $message = 'Application admitted successfully and the applicant has been notified.';
        } elseif ($newStatus === 'rejected') {
            $message = 'Application rejected and the applicant has been notified.';
        } elseif ($newStatus === 'withdrawn') {
            $message = 'Application marked as withdrawn and the applicant has been notified.';
        }

        return redirect()->route('admin.cohort-applications.show', $application)
            ->with('success', $message);
    }

    protected function sendStatusNotification(CohortApplication $application, BrevoMailService $mailService, string $status, ?string $reason = null): void
    {
        if (! in_array($status, ['reviewing', 'admitted', 'rejected', 'withdrawn'], true)) {
            return;
        }

        $mailService->sendMailable(
            $application->email,
            new CohortApplicationStatusUpdated($application, $status, $reason),
            'Cohort Application Status Update'
        );
    }
}
