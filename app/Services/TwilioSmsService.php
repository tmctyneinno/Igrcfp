<?php
// app/Services/TwilioSmsService.php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioSmsService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendOtp(string $phone, string $otp): bool
    {
        $phone = $this->formatPhone($phone);

        try {
            $this->client->messages->create($phone, [
                'from' => config('services.twilio.from'),
                'body' => "Your OTP code is: {$otp}. Valid for 10 minutes. Do not share this with anyone.",
            ]);

            Log::info("Twilio OTP sent to {$phone}");
            return true;

        } catch (\Twilio\Exceptions\RestException $e) {
            Log::error("Twilio REST error for {$phone}: " . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            Log::error("Twilio general error for {$phone}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Ensure number is in E.164 format: +2348012345678
     * Handles: 08012345678, 2348012345678, +2348012345678, +447911123456, etc.
     */
    protected function formatPhone(string $phone): string
    {
        // Strip everything except digits and leading +
        $phone = preg_replace('/[^\d+]/', '', $phone);

        // Already in E.164 format
        if (str_starts_with($phone, '+')) {
            return $phone;
        }

        // Nigerian local format: 08012345678 → +2348012345678
        if (str_starts_with($phone, '0') && strlen($phone) === 11) {
            return '+234' . substr($phone, 1);
        }

        // Has country code but no +: 2348012345678 → +2348012345678
        return '+' . $phone;
    }
}