<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMentorApplicationRequest;
use App\Models\MentorApplication;
use Illuminate\Http\Request;
use Inertia\Inertia;


class MentorApplicationController extends Controller
{
    // public function create(Request $request)
    // {
    //     $existingApplication = $request->user()->mentorApplications()
    //         ->latest()
    //         ->first();

    //     return view('mentors.apply', [
    //         'existingApplication' => $existingApplication,
    //     ]);
    // }




    public function create(Request $request)
    {
        $existingApplication = $request->user()->mentorApplications()
            ->latest()
            ->first();

        return Inertia::render('Dashboard/Mentors/Apply', [
            'existingApplication' => $existingApplication ? [
                'status' => $existingApplication->status,
                'admin_feedback' => $existingApplication->admin_feedback,
            ] : null,
        ]);
    }

    public function store(StoreMentorApplicationRequest $request)
    {
        $existing = $request->user()->mentorApplications()
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return redirect()
                ->route('dashboard.mentors.apply-to-become')
                ->with('info', 'You already have an active mentor application.');
        }

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

        return redirect()
            ->route('dashboard.mentors.apply-to-become')
            ->with('success', 'Your mentor application has been submitted.');
    }

    private function splitToArray(?string $value): ?array
    {
        if (!$value) {
            return null;
        }

        $parts = array_filter(array_map('trim', explode(',', $value)));
        return $parts ?: null;
    }
}
