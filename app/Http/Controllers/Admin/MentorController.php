<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMentorProfileRequest;
use App\Http\Requests\UpdateMentorProfileRequest;
use App\Models\MentorProfile;
use App\Models\User;

class MentorController extends Controller
{
    public function index()
    {
        $mentors = MentorProfile::with('user')->orderByDesc('created_at')->paginate(20);

        return view('admin.mentors.index', compact('mentors'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('admin.mentors.create', compact('users'));
    }

    public function store(StoreMentorProfileRequest $request)
    {
        MentorProfile::create([
            'user_id' => $request->input('user_id'),
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
            'is_active' => (bool) $request->input('is_active', true),
        ]);

        return redirect()
            ->route('admin.mentors.index')
            ->with('success', 'Mentor profile created.');
    }

    public function edit(MentorProfile $mentor)
    {
        $users = User::orderBy('name')->get();

        return view('admin.mentors.edit', [
            'mentor' => $mentor,
            'users' => $users,
        ]);
    }

    public function update(UpdateMentorProfileRequest $request, MentorProfile $mentor)
    {
        $mentor->update([
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
            'is_active' => (bool) $request->input('is_active', true),
        ]);

        return redirect()
            ->route('admin.mentors.index')
            ->with('success', 'Mentor profile updated.');
    }

    public function toggle(MentorProfile $mentor)
    {
        $mentor->update(['is_active' => !$mentor->is_active]);

        return redirect()
            ->route('admin.mentors.index')
            ->with('success', 'Mentor status updated.');
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
