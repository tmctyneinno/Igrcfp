<?php

namespace App\Mail;

use App\Models\CohortApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CohortApplicationStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CohortApplication $application,
        public string $status,
        public ?string $reason = null,
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->status) {
            'new' => 'Your Cohort Application Has Been Received',
            'reviewing' => 'Update on Your Cohort Application',
            'admitted' => 'Admission Update: Your Cohort Application',
            'rejected' => 'Update on Your Cohort Application',
            'withdrawn' => 'Application Status Update',
            default => 'Cohort Application Status Update',
        };

        return new Envelope(
            from: new Address(config('mail.from.address', 'enquiries@igrcfp.org'), config('mail.from.name', 'IGRCFP')),
            to: [
                new Address($this->application->email, $this->application->full_name),
            ],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cohort-application-status-updated',
            with: [
                'application' => $this->application,
                'status' => $this->status,
                'statusLabel' => $this->statusLabel(),
                'reason' => $this->reason,
                'supportEmail' => config('mail.support.address', config('mail.from.address', 'enquiries@igrcfp.org')),
            ],
        );
    }

    protected function statusLabel(): string
    {
        return match ($this->status) {
            'new' => 'Received',
            'reviewing' => 'Under Review',
            'admitted' => 'Admitted',
            'rejected' => 'Rejected',
            'withdrawn' => 'Withdrawn',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
