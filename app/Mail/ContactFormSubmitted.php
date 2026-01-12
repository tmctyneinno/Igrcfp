<?php
// app/Mail/ContactFormSubmitted.php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name')
            ),
            replyTo: [
                new Address($this->contactMessage->email, $this->contactMessage->full_name),
            ],
            subject: 'New Contact Form Submission: ' . $this->contactMessage->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.submitted',
            with: [
                'message' => $this->contactMessage,
                'adminName' => config('app.admin_name', 'Administrator'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}