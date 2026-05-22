<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the user
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->notifications();
        
        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->ofType($request->type);
        }
        
        // Filter by read status
        if ($request->has('status')) {
            if ($request->status === 'read') {
                $query->read();
            } elseif ($request->status === 'unread') {
                $query->unread();
            }
        }
        
        $notifications = $query->paginate(20);
        
        // Get unread count for badge
        $unreadCount = $user->unread_notifications_count;
        
        // Mark all as seen when viewing the page (optional)
        if ($request->has('mark_seen')) {
            $user->unreadNotifications()
                ->update(['read_at' => now()]);
            $unreadCount = 0;
        }
        
        return Inertia::render('Dashboard/Notifications/Index', [
            'notifications' => $notifications->through(fn($n) => [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'message' => $n->message,
                'icon' => $n->icon,
                'color' => $n->color,
                'link' => $n->link,
                'is_read' => $n->isRead(),
                'time_ago' => $n->time_ago,
                'created_at' => $n->created_at->format('M d, Y H:i'),
            ]),
            'unread_count' => $unreadCount,
            'filters' => $request->only(['type', 'status']),
            'types' => [
                ['value' => '', 'label' => 'All Types'],
                ['value' => Notification::TYPE_CERTIFICATE_GENERATED, 'label' => 'Certificates'],
                ['value' => Notification::TYPE_QUIZ_PASSED, 'label' => 'Quiz Results'],
                ['value' => Notification::TYPE_PROJECT_GRADED, 'label' => 'Project Grades'],
                ['value' => Notification::TYPE_COURSE_COMPLETED, 'label' => 'Course Completions'],
                ['value' => Notification::TYPE_MEMBERSHIP_APPROVED, 'label' => 'Memberships'],
                ['value' => Notification::TYPE_MEMBERSHIP_DECLINED, 'label' => 'Memberships'],
                ['value' => Notification::TYPE_MENTOR_APPLICATION_APPROVED, 'label' => 'Mentor Applications'],
                ['value' => Notification::TYPE_MENTOR_APPLICATION_DECLINED, 'label' => 'Mentor Applications'],
                ['value' => Notification::TYPE_MENTORSHIP_MESSAGE, 'label' => 'Mentorship Messages'],
            ],
        ]);
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }
        
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        
        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }
        
        $notification->delete();
        
        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Get unread count (for header badge)
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unread_notifications_count
        ]);
    }

    /**
     * Get recent notifications (for dropdown)
     */
    public function recent()
    {
        $user = auth()->user();
        
        $notifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'message' => $n->message,
                'icon' => $n->icon,
                'link' => $n->link,
                'is_read' => $n->isRead(),
                'time_ago' => $n->time_ago,
            ]);
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unread_notifications_count,
        ]);
    }
}