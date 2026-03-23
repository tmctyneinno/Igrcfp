<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OTPMail;
use App\Models\User;
use Inertia\Inertia;

class OTPVerificationController extends Controller
{
    public function showVerificationForm()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Check if user is already verified
        if ($user->is_verified) {
            return redirect()->intended(route('dashboard.index'));
        }
        
        return Inertia::render('Auth/VerifyOTP', [
            'email' => $user->email,
            'phone' => $user->phone_number,
        ]);
    }
    
    public function sendOTP(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        // Generate OTP
        $otp = $user->generateOTP();
        
        // Send OTP via Email
        try {
            Mail::to($user->email)->send(new OTPMail($otp));
            Log::info('OTP sent to email: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
        }
        
        // Send OTP via SMS (using a service like Twilio, Vonage, etc.)
        if ($user->phone_number) {
            try {
                $this->sendSMS($user->phone_number, $otp);
                Log::info('OTP sent to phone: ' . $user->phone_number);
            } catch (\Exception $e) {
                Log::error('Failed to send OTP SMS: ' . $e->getMessage());
            }
        }
        
        return response()->json([
            'message' => 'OTP sent successfully',
            'expires_in' => 10 // minutes
        ]);
    }
    
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);
        
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        if ($user->verifyOTP($request->otp_code)) {
            // Mark email and phone as verified
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
            
            if (!$user->hasVerifiedPhone() && $user->phone_number) {
                $user->markPhoneAsVerified();
            }
            
            // Clear any existing intended URLs
            session()->forget('url.intended');
            
            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully',
                'redirect' => route('dashboard.index')
            ]);
        }
        
        return response()->json([
            'error' => 'Invalid or expired OTP code'
        ], 422);
    }
    
    public function resendOTP(Request $request)
    {
        return $this->sendOTP($request);
    }
    
    private function sendSMS($phoneNumber, $otp)
    {
        // Implement your SMS service here (Twilio, Vonage, etc.)
        // Example with Twilio:
        /*
        use Twilio\Rest\Client;
        
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        $twilio->messages->create(
            $phoneNumber,
            [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => "Your verification code is: {$otp}. This code expires in 10 minutes."
            ]
        );
        */
        
        // For testing purposes, you can log it
        Log::info("SMS to {$phoneNumber}: Your OTP is {$otp}");
    }
}