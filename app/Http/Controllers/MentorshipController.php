<?php

namespace App\Http\Controllers;

use App\Http\Requests\MentorshipDecisionRequest;
use App\Models\MentorProfile;
use App\Models\Mentorship;
use App\Models\MentorshipApplication;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MentorshipController extends Controller
{
    // public function index(Request $request)
    // {
    //     $user = $request->user();
    //     $mentorProfile = $user->mentorProfile;

    //     $menteeApplications = $user->mentorshipApplications()
    //         ->with('mentorProfile.user')
    //         ->latest()
    //         ->get();

    //     $mentorApplications = collect();
    //     $mentorMentorships = collect();

    //     if ($mentorProfile) {
    //         $mentorApplications = MentorshipApplication::where('mentor_profile_id', $mentorProfile->id)
    //             ->with('mentee')
    //             ->latest()
    //             ->get();

    //         $mentorMentorships = $mentorProfile->mentorships()
    //             ->with('mentee')
    //             ->latest()
    //             ->get();
    //     }

    //     $menteeMentorships = $user->mentorshipsAsMentee()
    //         ->with('mentorProfile.user')
    //         ->latest()
    //         ->get();

    //     return view('mentorships.index', [
    //         'mentorProfile' => $mentorProfile,
    //         'menteeApplications' => $menteeApplications,
    //         'mentorApplications' => $mentorApplications,
    //         'mentorMentorships' => $mentorMentorships,
    //         'menteeMentorships' => $menteeMentorships,
    //     ]);
    // }






    public function index(Request $request)
    {
        $user = $request->user();
        $mentorProfile = $user->mentorProfile;

        $menteeApplications = $user->mentorshipApplications()
            ->with('mentorProfile.user')
            ->latest()
            ->get()
            ->map(fn ($app) => [
                'id' => $app->id,
                'mentor_name' => $app->mentorProfile->user->name,
                'status' => $app->status,
                'communication_method' => $app->communication_method,
                'created_at' => $app->created_at->format('M d, Y'),
            ]);

        $mentorApplications = collect();
        $mentorMentorships = collect();

        if ($mentorProfile) {
            $mentorApplications = MentorshipApplication::where('mentor_profile_id', $mentorProfile->id)
                ->with('mentee')
                ->latest()
                ->get()
                ->map(fn ($app) => [
                    'id' => $app->id,
                    'mentee_name' => $app->mentee->name,
                    'goals' => \Str::limit($app->goals, 120),
                    'status' => $app->status,
                    'communication_method' => $app->communication_method,
                ]);

            $mentorMentorships = $mentorProfile->mentorships()
                ->with(['mentee', 'application'])
                ->latest()
                ->get()
                ->map(fn ($m) => [
                    'id' => $m->id,
                    'mentee_name' => $m->mentee->name,
                    'status' => $m->status,
                    'communication_method' => $m->application?->communication_method,
                ]);
        }

        $menteeMentorships = $user->mentorshipsAsMentee()
            ->with(['mentorProfile.user', 'application'])
            ->latest()
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'mentor_name' => $m->mentorProfile->user->name,
                'status' => $m->status,
                'communication_method' => $m->application?->communication_method,
            ]);

        return Inertia::render('Dashboard/Mentorships/Index', [
            'mentorProfile' => $mentorProfile ? [
                'id' => $mentorProfile->id,
            ] : null,

            'menteeApplications' => $menteeApplications,
            'mentorApplications' => $mentorApplications,
            'mentorMentorships' => $mentorMentorships,
            'menteeMentorships' => $menteeMentorships,
        ]);
    }

    public function decide(MentorshipDecisionRequest $request, MentorshipApplication $application)
    {
        $mentorProfile = $application->mentorProfile;

        if (!$mentorProfile || $mentorProfile->user_id !== $request->user()->id) {
            return redirect()
                ->route('dashboard.mentorships.index')
                ->with('error', 'You are not allowed to manage this application.');
        }

        if ($mentorProfile->availability_status === 'not_taking') {
            return redirect()
                ->route('dashboard.mentorships.index')
                ->with('error', 'You are currently not accepting mentees.');
        }

        if ($request->input('decision') === 'accepted' && $mentorProfile->remainingCapacity() <= 0) {
            return redirect()
                ->route('dashboard.mentorships.index')
                ->with('error', 'You have reached your mentorship capacity.');
        }

        $application->update([
            'status' => $request->input('decision'),
            'mentor_feedback' => $request->input('mentor_feedback'),
            'responded_at' => now(),
        ]);

        if ($request->input('decision') === 'accepted') {
            Mentorship::create([
                'mentor_profile_id' => $mentorProfile->id,
                'mentee_id' => $application->mentee_id,
                'mentorship_application_id' => $application->id,
                'status' => 'active',
                'started_at' => now(),
            ]);
        }

        return redirect()
            ->route('dashboard.mentorships.index')
            ->with('success', 'Application updated successfully.');
    }

    // public function show(Request $request, Mentorship $mentorship)
    // {
    //     $mentorProfile = $mentorship->mentorProfile;
    //     $user = $request->user();

    //     if ($mentorProfile->user_id !== $user->id && $mentorship->mentee_id !== $user->id) {
    //         return redirect()
    //             ->route('dashboard.mentorships.index')
    //             ->with('error', 'You are not authorized to view this mentorship.');
    //     }

    //     $updates = $mentorship->updates()->latest()->get()->groupBy('type');

    //     return view('mentorships.dashboard', [
    //         'mentorship' => $mentorship->load(['mentorProfile.user', 'mentee']),
    //         'updates' => $updates,
    //     ]);
    // }












    public function show(Request $request, Mentorship $mentorship)
    {
        $mentorProfile = $mentorship->mentorProfile;
        $user = $request->user();

        if ($mentorProfile->user_id !== $user->id && $mentorship->mentee_id !== $user->id) {
            return redirect()
                ->route('dashboard.mentorships.index')
                ->with('error', 'You are not authorized to view this mentorship.');
        }

        $mentorship->load([
            'mentorProfile.user',
            'mentee',
            'application',
            'messages.user:id,name',
        ]);

        $updates = $mentorship->updates()
            ->latest()
            ->get()
            ->groupBy('type')
            ->map(fn ($group) =>
                $group->map(fn ($u) => [
                    'id' => $u->id,
                    'title' => $u->title,
                    'content' => $u->content,
                    'type' => $u->type,
                    'created_at' => $u->created_at->format('M d, Y'),
                    'scheduled_at' => optional($u->scheduled_at)?->format('M d, Y H:i'),
                    'rating' => $u->rating,
                ])
            );

        $messages = $mentorship->messages
            ->sortBy('created_at')
            ->values()
            ->map(fn ($message) => [
                'id' => $message->id,
                'message' => $message->message,
                'sender_name' => $message->user?->name ?? 'Unknown',
                'sender_id' => $message->user_id,
                'created_at' => $message->created_at->format('M d, Y H:i'),
            ]);

        $isMentor = $mentorProfile->user_id === $user->id;

        return Inertia::render('Dashboard/Mentorships/Show', [
            'mentorship' => [
                'id' => $mentorship->id,
                'mentor_name' => $mentorship->mentorProfile->user->name,
                'mentee_name' => $mentorship->mentee->name,
                'status' => $mentorship->status,
                'started_at' => optional($mentorship->started_at)?->format('M d, Y'),
                'communication_method' => $mentorship->application?->communication_method,
                'user_role' => $isMentor ? 'mentor' : 'mentee',
            ],
            'updates' => $updates,
            'messages' => $messages,
            'currentUserId' => $user->id,
        ]);
    }















    public function complete(Request $request, Mentorship $mentorship)
    {
        $mentorProfile = $mentorship->mentorProfile;
        $user = $request->user();

        if ($mentorProfile->user_id !== $user->id && $mentorship->mentee_id !== $user->id) {
            return redirect()
                ->route('dashboard.mentorships.index')
                ->with('error', 'You are not authorized to update this mentorship.');
        }

        $mentorship->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        if ($mentorProfile) {
            $mentorProfile->increment('completed_mentorships_count');
        }

        return redirect()
            ->route('dashboard.mentorships.show', $mentorship)
            ->with('success', 'Mentorship marked as completed.');
    }
}
