<?php

namespace App\Services;

use App\Mail\ContactFormConfirmation;
use App\Mail\ContactFormSubmitted;
use App\Mail\OTPMail;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;

class BrevoMailService
{
    protected $apiKey;
    protected $fromEmail;
    protected $fromName;

    public function __construct()
    {
        $this->apiKey = env('BREVO_API_KEY');
        $this->fromEmail = env('MAIL_FROM_ADDRESS', 'eshanokpe@gmail.com');
        $this->fromName = env('MAIL_FROM_NAME', config('app.name'));
    }

    public function sendOTP($to, $otp)
    {
        return $this->sendMailable($to, new OTPMail($otp), 'Your OTP Code');
    }

    public function sendContactFormSubmitted(string $to, ContactFormSubmitted $mailable): array
    {
        return $this->sendMailable($to, $mailable, 'New Contact Form Submission');
    }

    public function sendContactFormConfirmation(string $to, ContactFormConfirmation $mailable): array
    {
        return $this->sendMailable($to, $mailable, 'Thank You for Contacting ' . config('app.name'));
    } 

    public function sendMailable(string|array $to, Mailable $mailable, ?string $fallbackSubject = null): array
    {
        try {
            
            $envelope = method_exists($mailable, 'envelope') ? $mailable->envelope() : null;
            $subject = $envelope?->subject ?? $mailable->subject ?? $fallbackSubject ?? 'Notification';
            $htmlContent = $mailable->render();
            
            $payload = [
                'sender' => $this->formatAddress($envelope?->from ?? new Address($this->fromEmail, $this->fromName)),
                'to' => $this->formatRecipients($to),
                'subject' => $subject,
                'htmlContent' => $htmlContent,
            ];
            
            return $this->sendPayload($payload);
            
        } catch (\Exception $e) {
            
            throw $e;
        }
    }

    protected function sendPayload(array $data): array
    {
        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . $this->apiKey,
            'content-type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode === 201) {
            return ['success' => true, 'message_id' => json_decode($response)->messageId ?? null];
        }

        throw new \Exception("Failed to send email: HTTP {$httpCode} - " . ($error ?: $response));
    }

    protected function formatRecipients(string|array $recipients): array
    {
        $recipients = is_array($recipients) ? $recipients : [$recipients];

        return array_map(function ($recipient) {
            if ($recipient instanceof Address) {
                return $this->formatAddress($recipient);
            }

            if (is_array($recipient)) {
                return array_filter([
                    'email' => $recipient['email'] ?? null,
                    'name' => $recipient['name'] ?? null,
                ]);
            }

            return ['email' => $recipient];
        }, $recipients);
    }

    protected function formatAddress(Address $address): array
    {
        return array_filter([
            'email' => $address->address,
            'name' => $address->name,
        ]);
    }
}
