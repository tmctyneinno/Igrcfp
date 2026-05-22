<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMentorApplicationRequest;
use App\Models\MentorApplication;
use App\Services\ActivityLoggerService;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MentorApplicationController extends Controller
{
    public function create(Request $request)
    {
        $existingApplication = $request->user()->mentorApplications()
            ->latest()
            ->first();

        $blockedStatuses = ['pending', 'approved'];
        $canSubmitNewApplication = !$existingApplication || !in_array($existingApplication->status, $blockedStatuses, true);

        // Log mentor application page view
        ActivityLoggerService::log(
            ActivityLog::EVENT_UPDATED,
            'mentors',
            'Viewed mentor application form',
            "User viewed mentor application page",
            $existingApplication,
            [
                'user_id' => $request->user()->id,
                'has_existing_application' => $existingApplication ? true : false,
                'existing_status' => $existingApplication->status ?? null,
                'can_submit_new' => $canSubmitNewApplication
            ],
            ActivityLog::SEVERITY_INFO
        );

        return Inertia::render('Dashboard/Mentors/Apply', [
            'existingApplication' => $existingApplication ? [
                'status' => $existingApplication->status,
                'admin_feedback' => $existingApplication->admin_feedback,
            ] : null,
            'canSubmitNewApplication' => $canSubmitNewApplication,
        ]);
    }

    public function store(StoreMentorApplicationRequest $request)
    {
        $existing = $request->user()->mentorApplications()
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            $message = $existing->status === 'approved'
                ? 'You already have an approved mentor application.'
                : 'Your previous mentor application is still pending review.';

            // Log blocked application attempt
            ActivityLoggerService::log(
                ActivityLog::EVENT_UPDATED,
                'mentors',
                'Mentor application blocked - existing application',
                "User attempted to submit new application while existing one is {$existing->status}",
                $existing,
                [
                    'user_id' => $request->user()->id,
                    'existing_application_id' => $existing->id,
                    'existing_status' => $existing->status,
                    'reason' => $existing->status === 'approved' ? 'already_approved' : 'pending_review'
                ],
                ActivityLog::SEVERITY_WARNING
            );

            return redirect()
                ->route('dashboard.mentors.apply-to-become')
                ->with('info', $message);
        }

        // Create the mentor application
        $application = MentorApplication::create([
            'user_id' => $request->user()->id,
            'title' => $request->input('title'),
            'domain' => $request->input('domain'),
            'region' => $request->input('region'),
            'country' => $request->input('country'),
            'bio' => $request->input('bio'),
            'expertise_summary' => $request->input('expertise_summary'),
            'availability_status' => $request->input('availability_status'),
            'languages' => $this->splitToArray($request->input('languages')),
            'skills' => $this->splitToArray($request->input('skills')),
            'certifications' => $this->splitToArray($request->input('certifications')),
            'max_mentees' => $request->input('max_mentees'),
            'status' => 'pending',
        ]);

        // Log successful application submission
        ActivityLoggerService::log(
            ActivityLog::EVENT_CREATED,
            'mentors',
            'Mentor application submitted',
            "User submitted mentor application in domain: {$application->domain}",
            $application,
            [
                'user_id' => $request->user()->id,
                'application_id' => $application->id,
                'domain' => $application->domain,
                'region' => $application->region,
                'country' => $application->country,
                'title' => $application->title,
                'availability_status' => $application->availability_status,
                'max_mentees' => $application->max_mentees,
                'languages_count' => count($application->languages ?? []),
                'skills_count' => count($application->skills ?? []),
                'certifications_count' => count($application->certifications ?? []),
                'ip' => $request->ip()
            ],
            ActivityLog::SEVERITY_INFO
        );

        return redirect()
            ->route('dashboard.mentors.apply-to-become')
            ->with('success', 'Your mentor application has been submitted.');
    }

    /**
     * Split comma-separated string into array
     */
    private function splitToArray(?string $value): ?array
    {
        if (!$value) {
            return null;
        }

        $parts = array_filter(array_map('trim', explode(',', $value)));
        return $parts ?: null;
    }
}