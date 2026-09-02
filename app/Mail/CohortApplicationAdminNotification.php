<?php

namespace App\Mail;

use App\Models\CohortApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CohortApplicationAdminNotification extends Mailable
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
                new Address(config('mail.admissions_address', 'enquiries@igrcfp.org'), 'Admissions Team'),
            ],
            subject: 'New Cohort Application Received: ' . $this->application->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cohort-application-admin',
            with: [
                'application' => $this->application,
                'referenceId' => 'COHORT-' . str_pad((string) $this->application->id, 6, '0', STR_PAD_LEFT),
            ],
        );
    }
}
