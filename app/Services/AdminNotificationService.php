<?php

namespace App\Services;

use App\Models\Admin;

class AdminNotificationService
{
    public static function notifyAll(array $payload): void
    {
        Admin::query()
            ->where('is_active', true)
            ->each(function (Admin $admin) use ($payload) {
                $admin->adminNotifications()->create($payload);
            });
    }
}
