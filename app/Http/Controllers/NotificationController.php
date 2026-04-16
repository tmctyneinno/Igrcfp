<?php

namespace App\Http\Controllers;

use App\Models\MentorshipMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $recentNotifications = MentorshipMessage::query()
            ->with(['user:id,name', 'mentorship.mentorProfile.user:id,name', 'mentorship.mentee:id,name'])
            ->where('user_id', '!=', $user->id)
            ->where(function ($query) use ($user) {
                $query->whereHas('mentorship', function ($mentorshipQuery) use ($user) {
                    $mentorshipQuery->where('mentee_id', $user->id)
                        ->orWhereHas('mentorProfile', function ($mentorQuery) use ($user) {
                            $mentorQuery->where('user_id', $user->id);
                        });
                });
            })
            ->latest()
            ->take(20)
            ->get()
            ->map(function (MentorshipMessage $message) use ($user) {
                $otherParty = $message->mentorship?->mentee_id === $user->id
                    ? $message->mentorship?->mentorProfile?->user?->name
                    : $message->mentorship?->mentee?->name;

                return [
                    'id' => $message->id,
                    'mentorship_id' => $message->mentorship_id,
                    'sender_name' => $message->user?->name ?? 'Unknown',
                    'counterparty_name' => $otherParty ?? 'Mentorship contact',
                    'preview' => Str::limit($message->message, 120),
                    'created_at' => $message->created_at?->format('M d, Y H:i'),
                    'is_unread' => is_null($message->read_at),
                ];
            })->values();

        return Inertia::render('Dashboard/Notifications/Index', [
            'settings' => [
                'email_notifications' => (bool) $user->email_notifications,
                'sms_notifications' => (bool) $user->sms_notifications,
                'newsletter_subscription' => (bool) $user->newsletter_subscription,
                'marketing_emails' => (bool) $user->marketing_emails,
            ],
            'unreadCount' => $this->getUnreadCount($user->id),
            'recentNotifications' => $recentNotifications,
        ]);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => ['required', 'boolean'],
            'sms_notifications' => ['required', 'boolean'],
            'newsletter_subscription' => ['required', 'boolean'],
            'marketing_emails' => ['required', 'boolean'],
        ]);

        $request->user()->update($validated);

        return back()->with('success', 'Notification settings updated successfully.');
    }

    public function count(Request $request)
    {
        return response()->json([
            'unread_count' => $this->getUnreadCount($request->user()->id),
        ]);
    }

    private function getUnreadCount(int $userId): int
    {
        return MentorshipMessage::query()
            ->where('user_id', '!=', $userId)
            ->whereNull('read_at')
            ->where(function ($query) use ($userId) {
                $query->whereHas('mentorship', function ($mentorshipQuery) use ($userId) {
                    $mentorshipQuery->where('mentee_id', $userId)
                        ->orWhereHas('mentorProfile', function ($mentorQuery) use ($userId) {
                            $mentorQuery->where('user_id', $userId);
                        });
                });
            })
            ->count();
    }
}
