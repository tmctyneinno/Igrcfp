<?php

namespace App\Http\Controllers;

use App\Models\Mentorship;
use App\Models\MentorshipMessage;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
 
class MentorshipMessageController extends Controller
{
    public function index(Request $request, Mentorship $mentorship)
    {
        $user = $request->user();

        if (!$this->isParticipant($mentorship, $user->id)) {
            abort(403);
        }

        $sinceId = (int) $request->integer('since_id', 0);

        $query = $mentorship->messages()
            ->with('user:id,name')
            ->orderBy('id');

        if ($sinceId > 0) {
            $query->where('id', '>', $sinceId);
        }

        $messages = $query->get()->map(function (MentorshipMessage $message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_name' => $message->user?->name ?? 'Unknown',
                'sender_id' => $message->user_id,
                'created_at' => $message->created_at?->format('M d, Y H:i'),
            ];
        })->values();

        $this->markAsRead($mentorship->id, $user->id);

        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function store(Request $request, Mentorship $mentorship)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $user = $request->user();
        if (!$this->isParticipant($mentorship, $user->id)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You are not authorized to post in this mentorship chat.',
                ], 403);
            }

            return redirect()->route('dashboard.mentorships.index')
                ->with('error', 'You are not authorized to post in this mentorship chat.');
        }

        $message = $mentorship->messages()->create([
            'user_id' => $user->id,
            'message' => $request->string('message')->toString(),
        ]);

        $recipientId = $mentorship->mentorProfile?->user_id === $user->id
            ? $mentorship->mentee_id
            : $mentorship->mentorProfile?->user_id;

        if ($recipientId && $recipientId !== $user->id) {
            Notification::create([
                'user_id' => $recipientId,
                'type' => Notification::TYPE_MENTORSHIP_MESSAGE,
                'title' => 'New Mentorship Message',
                'message' => "{$user->name} sent you a mentorship message.",
                'data' => [
                    'mentorship_id' => $mentorship->id,
                    'message_id' => $message->id,
                ],
            ]);
        }

        $payload = [
            'id' => $message->id,
            'message' => $message->message,
            'sender_name' => $user->name,
            'sender_id' => $user->id,
            'created_at' => $message->created_at?->format('M d, Y H:i'),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $payload,
            ]);
        }

        return redirect()
            ->route('dashboard.mentorships.show', $mentorship)
            ->with('success', 'Message sent.');
    }

    private function isParticipant(Mentorship $mentorship, int $userId): bool
    {
        $mentorUserId = $mentorship->mentorProfile?->user_id;

        return $mentorUserId === $userId || $mentorship->mentee_id === $userId;
    }

    private function markAsRead(int $mentorshipId, int $userId): void
    {
        MentorshipMessage::query()
            ->where('mentorship_id', $mentorshipId)
            ->where('user_id', '!=', $userId)
            ->whereNull('read_at')
            ->update([
                'read_at' => Carbon::now(),
            ]);
    }
}
