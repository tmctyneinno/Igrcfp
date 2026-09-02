<?php

namespace App\Notifications;

use App\Models\CohortApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CohortNewApplicationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Application $application)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $app = $this->application;

        return (new MailMessage)
            ->subject("New Cohort Application — {$app->cohort} ({$app->full_name})")
            ->greeting('New application received')
            ->line("**Name:** {$app->full_name}")
            ->line("**Email:** {$app->email}")
            ->line("**Phone:** " . ($app->phone ?: '—'))
            ->line("**Country:** {$app->country}")
            ->line("**Cohort:** {$app->cohort}")
            ->line("**Level:** {$app->level}")
            ->line("**Discipline:** " . ($app->discipline ?: '—'))
            ->when($app->message, fn ($mail) => $mail->line("**Message:** {$app->message}"))
            ->line('Submitted at: ' . $app->created_at->format('d M Y, H:i'));
    }
}