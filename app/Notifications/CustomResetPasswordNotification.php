<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Get the logo URL - use full URL for emails
        $logoUrl = config('app.url') . '/assets/images/logo-main.png';
        
        // If using localhost, you might want to use a different approach
        if (config('app.env') === 'local') {
            $logoUrl = 'https://via.placeholder.com/200x60/667eea/ffffff?text=IGRCFP+Logo';
        }

        return (new MailMessage)
            ->subject('IGRCFP - Password Reset Request')
            ->view('emails.password-reset', [
                'resetUrl' => $resetUrl,
                'logoUrl' => $logoUrl,
                'user' => $notifiable,
            ]);
    }
}