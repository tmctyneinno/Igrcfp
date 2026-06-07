<?php

namespace App\Mail;

use App\Models\ScholarshipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScholarshipUnderReview extends Mailable
{
    use Queueable, SerializesModels;

    public ScholarshipApplication $application;

    public function __construct(ScholarshipApplication $application)
    {
        $this->application = $application;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Scholarship Application Under Review - IGRCFP',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.scholarship-under-review',
        );
    }
}