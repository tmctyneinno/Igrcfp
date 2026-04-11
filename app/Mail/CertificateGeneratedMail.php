<?php

namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificateGeneratedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $enrollment;
    public $user;
    public $course;

    /**
     * Create a new message instance.
     */
    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
        $this->user = $enrollment->user;
        $this->course = $enrollment->course;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎓 Your Certificate is Ready - ' . $this->course->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.certificate-generated',
            with: [
                'userName' => $this->user->name,
                'courseTitle' => $this->course->title,
                'certificateNumber' => $this->enrollment->certificate_number,
                'completionDate' => $this->enrollment->certificate_generated_date->format('F d, Y'),
                'downloadUrl' => route('dashboard.certificates.download', $this->enrollment->id),
                'verifyUrl' => route('dashboard.certificate.verify', $this->enrollment->certificate_number),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}