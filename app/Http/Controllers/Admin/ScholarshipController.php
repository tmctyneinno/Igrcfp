<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipApplication;
use App\Services\BrevoMailService;
use App\Traits\SendsScholarshipNotifications;
use Illuminate\Http\Request;

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
        $request->validate([
            'status' => 'required|in:pending,under_review,accepted,rejected',
            'admin_notes' => 'nullable|string',
        ]);
        
        $oldStatus = $application->status;
        $newStatus = $request->status;
        
        if ($oldStatus === $newStatus) {
            return redirect()->route('admin.scholarships.index')
                ->with('info', 'Application status is already set to ' . ucfirst(str_replace('_', ' ', $newStatus)));
        }

        $application->update([
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes,
        ]);
        
        // Send notification
        $this->sendStatusChangeNotification($application, $this->mailService, $oldStatus, $newStatus);

        $message = 'Application status updated successfully!';
        
        if ($newStatus === 'accepted') {
            $message .= ' A scholarship approval email has been sent to the applicant.';
        } elseif ($newStatus === 'under_review') {
            $message .= ' A notification email has been sent to the applicant.';
        }

        // Redirect to index page instead of back
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