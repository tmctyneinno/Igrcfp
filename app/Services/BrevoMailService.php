<?php

namespace App\Services;

use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;

class BrevoMailService
{
    protected $apiKey;
    protected $fromEmail;

    public function __construct()
    {
        $this->apiKey = env('BREVO_API_KEY');
        $this->fromEmail = env('MAIL_FROM_ADDRESS', 'eshanokpe@gmail.com');
    }

    public function sendOTP($to, $otp)
    {
        // Render the OTPMail mailable to HTML
        $mailable = new OTPMail($otp);
        $htmlContent = $mailable->render();
        
        $data = [
            'sender' => ['email' => $this->fromEmail],
            'to' => [['email' => $to]],
            'subject' => $mailable->subject ?? 'Your OTP Code',
            'htmlContent' => $htmlContent
        ];

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
}