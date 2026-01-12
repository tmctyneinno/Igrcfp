<?php
// app/Mail/ContactFormConfirmation.php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormConfirmation extends Mailable
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
            to: [
                new Address($this->contactMessage->email, $this->contactMessage->full_name),
            ],
            subject: 'Thank You for Contacting ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.confirmation',
            with: [
                'message' => $this->contactMessage,
                'supportEmail' => config('mail.support.address', config('mail.from.address')),
                'supportPhone' => config('app.support_phone'),
                'responseTime' => config('app.response_time', '24-48 hours'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}