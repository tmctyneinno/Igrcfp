<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipApplication;
use App\Services\BrevoMailService;
use App\Traits\SendsScholarshipNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScholarshipController extends Controller
{
    use SendsScholarshipNotifications;
    
    protected $mailService;

    public function __construct(BrevoMailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function index(Request $request)
    {
        $query = ScholarshipApplication::with('post')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('nationality', 'LIKE', "%{$search}%");
            });
        }

        $applications = $query->paginate(20);

        return view('admin.scholarships.index', compact('applications'));
    }

    public function show(ScholarshipApplication $application)
    {
        $application->load('post');
        return view('admin.scholarships.show', compact('application'));
    }

    public function updateStatus(Request $request, ScholarshipApplication $application)
    {
        // Log the incoming request
        Log::info('Updating scholarship status', [
            'application_id' => $application->id,
            'request_data' => $request->all()
        ]);

        // Validate the request
        $validated = $request->validate([
            'status' => 'required|in:pending,under_review,accepted,rejected',
            'admin_notes' => 'nullable|string',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
        ]);
        
        $oldStatus = $application->status;
        $newStatus = $request->status;
        
        // Check if status is actually changing
        if ($oldStatus === $newStatus) {
            return redirect()->route('admin.scholarships.index')
                ->with('info', 'Application status is already set to ' . ucfirst(str_replace('_', ' ', $newStatus)));
        }
        
        $updateData = [
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes,
        ];
        
        // Handle rejection
        if ($newStatus === 'rejected') {
            $updateData['rejection_reason'] = $request->rejection_reason;
            $updateData['rejected_at'] = now();
            Log::info('Setting rejection data', [
                'reason' => $request->rejection_reason,
                'rejected_at' => now()
            ]);
        }
        
        // Handle acceptance
        if ($newStatus === 'accepted') {
            $updateData['accepted_at'] = now();
        }
        
        // Update the application
        $updated = $application->update($updateData);
        
        Log::info('Application updated', [
            'application_id' => $application->id,
            'updated' => $updated,
            'new_status' => $application->fresh()->status,
            'update_data' => $updateData
        ]);
        
        // Send notification
        try {
            $this->sendStatusChangeNotification(
                $application, 
                $this->mailService, 
                $oldStatus, 
                $newStatus,
                $request->rejection_reason
            );
        } catch (\Exception $e) {
            Log::error('Notification sending failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        $message = 'Application status updated successfully!';
        
        if ($newStatus === 'accepted') {
            $message .= ' A scholarship approval email has been sent to the applicant.';
        } elseif ($newStatus === 'under_review') {
            $message .= ' A notification email has been sent to the applicant.';
        } elseif ($newStatus === 'rejected') {
            $message .= ' A notification email has been sent to the applicant.';
        }

        return redirect()->route('admin.scholarships.index')
            ->with('success', $message);
    }

    public function destroy(ScholarshipApplication $application)
    {
        $application->delete();
        return redirect()->route('admin.scholarships.index')
            ->with('success', 'Application deleted successfully!');
    }
}