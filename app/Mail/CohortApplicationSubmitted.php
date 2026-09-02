<?php

namespace App\Mail;

use App\Models\CohortApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CohortApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CohortApplication $application)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address', 'enquiries@igrcfp.org'), config('mail.from.name', 'IGRCFP')),
            to: [
                new Address($this->application->email, $this->application->full_name),
            ],
            subject: 'Your Cohort Application Has Been Received',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cohort-application-submitted',
            with: [
                'application' => $this->application,
                'supportEmail' => config('mail.support.address', config('mail.from.address', 'enquiries@igrcfp.org')),
                'supportPhone' => config('app.support_phone', '+234 700 000 0000'),
            ],
        );
    }
}
