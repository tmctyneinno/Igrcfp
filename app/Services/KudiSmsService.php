<?php
// app/Services/KudiSmsService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KudiSmsService
{
    protected string $username;
    protected string $password;
    protected string $senderId;
    protected string $apiUrl = 'https://account.kudisms.net/api/';

    public function __construct()
    {
        $this->username = config('services.kudisms.username');
        $this->password = config('services.kudisms.password');
        $this->senderId = config('services.kudisms.sender_id');
    }

    public function sendOtp(string $phone, string $otp): bool
    {
        $phone = $this->formatPhone($phone);
        $message = "Your OTP code is: {$otp}. It expires in 10 minutes. Do not share this with anyone.";

        try {
            $response = Http::get($this->apiUrl, [
                'username' => $this->username,
                'password' => $this->password,
                'message'  => $message,
                'sender'   => $this->senderId,
                'mobiles'  => $phone,
            ]);

            $result = $response->json();

            if (isset($result['status']) && $result['status'] === 'OK') {
                Log::info("KudiSMS OTP sent to {$phone}");
                return true;
            }

            Log::error('KudiSMS error: ' . json_encode($result));
            return false;

        } catch (\Exception $e) {
            Log::error('KudiSMS exception: ' . $e->getMessage());
            return false; 
        }
    }

    /**
     * Normalize phone to international format (234XXXXXXXXXX)
     * Handles: 08012345678, +2348012345678, 2348012345678
     */
    protected function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // strip non-digits

        if (str_starts_with($phone, '0')) {
            $phone = '234' . substr($phone, 1);
        } elseif (str_starts_with($phone, '+')) {
            $phone = ltrim($phone, '+');
        }

        return $phone;
    }
}