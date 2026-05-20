<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMentorshipUpdateRequest;
use App\Models\Mentorship;

class MentorshipUpdateController extends Controller
{
    public function store(StoreMentorshipUpdateRequest $request, Mentorship $mentorship)
    {
        $mentorProfile = $mentorship->mentorProfile;
        $user = $request->user();

        if ($mentorProfile->user_id !== $user->id && $mentorship->mentee_id !== $user->id) {
            return redirect()
                ->route('dashboard.mentorships.index')
                ->with('error', 'You are not authorized to update this mentorship.');
        }

        $mentorship->updates()->create([
            'type' => $request->input('type'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'scheduled_at' => $request->input('scheduled_at'),
            'rating' => $request->input('rating'),
            'created_by' => $user->id,
        ]);

        return redirect()
            ->route('dashboard.mentorships.show', $mentorship)
            ->with('success', 'Update added successfully.');
    }
}
