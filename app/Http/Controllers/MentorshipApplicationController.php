<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMentorshipApplicationRequest;
use App\Models\MentorProfile;
use App\Models\MentorshipApplication;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MentorshipApplicationController extends Controller
{
    // public function create(MentorProfile $mentor)
    // {
    //     if ($mentor->user_id === auth()->id()) {
    //         return redirect()
    //             ->route('dashboard.mentors.index')
    //             ->with('error', 'You cannot apply to yourself.');
    //     }
    //     if (!$mentor->is_active || $mentor->availability_status === 'not_taking') {
    //         return redirect()
    //             ->route('dashboard.mentors.index')
    //             ->with('error', 'This mentor is not accepting applications right now.');
    //     }

    //     if ($mentor->remainingCapacity() <= 0) {
    //         return redirect()
    //             ->route('dashboard.mentors.show', $mentor)
    //             ->with('error', 'This mentor has reached their current capacity.');
    //     }

    //     return view('mentorships.apply', [
    //         'mentor' => $mentor->load('user'),
    //     ]);
    // }




    public function create(MentorProfile $mentor)
    {
        if ($mentor->user_id === auth()->id()) {
            return redirect()
                ->route('dashboard.mentors.index')
                ->with('error', 'You cannot apply to yourself.');
        }

        if (!$mentor->is_active || $mentor->availability_status === 'not_taking') {
            return redirect()
                ->route('dashboard.mentors.index')
                ->with('error', 'This mentor is not accepting applications right now.');
        }

        if ($mentor->remainingCapacity() <= 0) {
            return redirect()
                ->route('dashboard.mentors.show', $mentor)
                ->with('error', 'This mentor has reached their current capacity.');
        }

        $mentor->load('user');

        return Inertia::render('Dashboard/Mentorships/Apply', [
            'mentor' => [
                'id' => $mentor->id,
                'name' => $mentor->user->name,
            ],
        ]);
    }





    
    public function store(StoreMentorshipApplicationRequest $request, MentorProfile $mentor)
    {
        if ($mentor->user_id === $request->user()->id) {
            return redirect()
                ->route('dashboard.mentors.index')
                ->with('error', 'You cannot apply to yourself.');
        }
        if (!$mentor->is_active || $mentor->availability_status === 'not_taking') {
            return redirect()
                ->route('dashboard.mentors.index')
                ->with('error', 'This mentor is not accepting applications right now.');
        }

        if ($mentor->remainingCapacity() <= 0) {
            return redirect()
                ->route('dashboard.mentors.show', $mentor)
                ->with('error', 'This mentor has reached their current capacity.');
        }

        $existing = MentorshipApplication::where('mentor_profile_id', $mentor->id)
            ->where('mentee_id', $request->user()->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->first();

        if ($existing) {
            return redirect()
                ->route('dashboard.mentorships.index')
                ->with('info', 'You already have an open application with this mentor.');
        }

        MentorshipApplication::create([
            'mentor_profile_id' => $mentor->id,
            'mentee_id' => $request->user()->id,
            'goals' => $request->input('goals'),
            'preferred_duration' => $request->input('preferred_duration'),
            'availability' => $request->input('availability'),
            'communication_method' => $request->input('communication_method'),
            'notes' => $request->input('notes'),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('dashboard.mentorships.index')
            ->with('success', 'Your mentorship application has been submitted.');
    }
}
