<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OTPVerificationController extends Controller
{
    public function showVerificationForm()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // If user is already verified, redirect to dashboard
        if ($user->is_verified) {
            Log::info('User already verified, redirecting to dashboard', ['user_id' => $user->id]);
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
        
        // Check if user is already verified
        if ($user->is_verified) {
            return response()->json(['error' => 'User already verified'], 400);
        }
        
        // Generate OTP
        $otp = $user->generateOTP();
        
        // Send OTP via Email
        try {
            Mail::to($user->email)->send(new OTPMail($otp));
            Log::info('OTP sent to email: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send verification code'], 500);
        }
        
        // Send OTP via SMS if phone exists
        if ($user->phone_number) {
            try {
                $this->sendSMS($user->phone_number, $otp);
                Log::info('OTP sent to phone: ' . $user->phone_number);
            } catch (\Exception $e) {
                Log::error('Failed to send OTP SMS: ' . $e->getMessage());
            }
        }
        
        return response()->json([
            'message' => 'Verification code sent successfully',
            'expires_in' => 10
        ]);
    }
    
    public function verifyOTP(Request $request)
    {
        \Log::info('Verifying OTP', [
        'user_id' => $this->id,
        'provided_code' => $code,
        'stored_code' => $this->otp_code,
        'expires_at' => $this->otp_expires_at,
        'current_time' => now()
    ]);
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);
        
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        // Check if already verified
        if ($user->is_verified) {
            return response()->json([
                'success' => true,
                'message' => 'User already verified',
                'redirect' => session('redirect_after_verification', route('dashboard.index'))
            ]);
        }
        
        // Verify OTP
        if ($user->verifyOTP($request->otp_code)) {
            // CRITICAL: Make sure is_verified is set to true
            $user->is_verified = true;
            $user->save();
            
            // Mark email as verified if not already
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
            
            // Mark phone as verified if phone exists
            if ($user->phone_number && !$user->hasVerifiedPhone()) {
                $user->markPhoneAsVerified();
            }
            
            // Log successful verification
            Log::info('User successfully verified OTP', [
                'user_id' => $user->id,
                'email' => $user->email,
                'is_verified' => $user->is_verified
            ]);
            
            // Check if there's a redirect URL stored
            $redirect = session('redirect_after_verification', route('dashboard.index'));
            session()->forget('redirect_after_verification');
            
            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully',
                'redirect' => $redirect
            ]);
        }
        
        return response()->json([
            'error' => 'Invalid or expired verification code'
        ], 422);
    }
    
    public function resendOTP(Request $request)
    {
        return $this->sendOTP($request);
    }
    
    private function sendSMS($phoneNumber, $otp)
    {
        Log::info("SMS to {$phoneNumber}: Your verification code is {$otp}");
    }
}