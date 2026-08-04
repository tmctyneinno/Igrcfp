<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function recent(): JsonResponse
    {
        $admin = Auth::guard('admin')->user();

        return response()->json([
            'unread_count' => $admin->adminNotifications()->unread()->count(),
            'notifications' => $admin->adminNotifications()
                ->latest()
                ->take(8)
                ->get()
                ->map(fn (AdminNotification $notification) => [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'color_class' => $notification->color_class,
                    'url' => $notification->url,
                    'is_read' => $notification->read_at !== null,
                    'time_ago' => $notification->created_at->diffForHumans(),
                ]),
        ]);
    }

    public function markAsRead(AdminNotification $notification): JsonResponse
    {
        $this->authorizeNotification($notification);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(): JsonResponse
    {
        Auth::guard('admin')
            ->user()
            ->adminNotifications()
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    private function authorizeNotification(AdminNotification $notification): void
    {
        abort_unless($notification->admin_id === Auth::guard('admin')->id(), 403);
    }
}
