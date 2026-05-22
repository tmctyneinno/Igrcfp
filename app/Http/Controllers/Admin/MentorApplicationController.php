<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MentorApplication;
use App\Models\MentorProfile;
use App\Models\Notification;
use Illuminate\Http\Request;

class MentorApplicationController extends Controller
{
    public function index()
    {
        $applications = MentorApplication::with('user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.mentors.applications', compact('applications'));
    }

    public function approve(Request $request, MentorApplication $application)
    {
        $application->update([
            'status' => 'approved',
            'admin_feedback' => $request->input('admin_feedback'),
            'processed_by_admin_id' => $request->user('admin')?->id,
            'processed_at' => now(),
        ]);

        MentorProfile::updateOrCreate(
            ['user_id' => $application->user_id],
            [
                'title' => $application->title,
                'domain' => $application->domain,
                'region' => $application->region,
                'country' => $application->country,
                'bio' => $application->bio,
                'expertise_summary' => $application->expertise_summary,
                'availability_status' => $application->availability_status,
                'languages' => $application->languages,
                'skills' => $application->skills,
                'certifications' => $application->certifications,
                'max_mentees' => $application->max_mentees,
                'is_active' => true,
            ]
        );

        Notification::create([
            'user_id' => $application->user_id,
            'type' => Notification::TYPE_MENTOR_APPLICATION_APPROVED,
            'title' => 'Mentor Application Approved',
            'message' => 'Your mentor application has been approved. You can now offer mentorship on the platform.',
            'data' => ['application_id' => $application->id],
        ]);

        return redirect()
            ->route('admin.mentor-applications.index')
            ->with('success', 'Mentor application approved.');
    }

    public function decline(Request $request, MentorApplication $application)
    {
        $application->update([
            'status' => 'declined',
            'admin_feedback' => $request->input('admin_feedback'),
            'processed_by_admin_id' => $request->user('admin')?->id,
            'processed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $application->user_id,
            'type' => Notification::TYPE_MENTOR_APPLICATION_DECLINED,
            'title' => 'Mentor Application Declined',
            'message' => 'Your mentor application has been declined. Please review the feedback and try again later.',
            'data' => ['application_id' => $application->id],
        ]);

        return redirect()
            ->route('admin.mentor-applications.index')
            ->with('success', 'Mentor application declined.');
    }
}
