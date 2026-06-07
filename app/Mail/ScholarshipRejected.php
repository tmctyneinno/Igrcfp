<?php

namespace App\Mail;

use App\Models\ScholarshipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScholarshipRejected extends Mailable
{
    use Queueable, SerializesModels;

    public ScholarshipApplication $application;
    public $reason;

    public function __construct(ScholarshipApplication $application, $reason = null)
    {
        $this->application = $application;
        $this->reason = $reason;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Update on Your IGRCFP Scholarship Application',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.scholarship-rejected',
        );
    }
}