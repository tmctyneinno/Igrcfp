<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
 
class OTPVerificationController extends Controller
{
    public function showVerificationForm()
    { 
        $userId = session('otp_user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        return Inertia::render('Auth/VerifyOTP', [
            'email' => $user->email,
            'phone' => $user->phone_number,
        ]);
    }
    
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return response()->json(['error' => 'Session expired. Please login again.'], 401);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // ✅ Block if another browser has taken over the session
        $sessionToken = session('session_token');
        if (!$sessionToken || $sessionToken !== $user->active_session_token) {
            return response()->json([
                'error' => 'Your account has been logged in from another browser. Please login again.'
            ], 401);
        }

        if ($user->verifyOTP($request->otp_code)) {
            Auth::login($user);

            // ✅ Persist session token after login so middleware can validate it
            session([
                'session_token' => $user->active_session_token,
            ]);

            session()->forget('otp_user_id');

            return response()->json([
                'success'  => true,
                'redirect' => route('dashboard.index')
            ]);
        }

        return response()->json([
            'error' => 'Invalid or expired OTP code'
        ], 422);
    }
    
    public function resendOTP(Request $request)
    {
        $userId = session('otp_user_id');
        
        if (!$userId) {
            return response()->json(['error' => 'Session expired'], 401);
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        // Generate new OTP
        $otp = $user->generateOTP();
        
        // Send OTP via email
        try {
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            \Log::error('Failed to resend OTP: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send OTP'], 500);
        }
        
        return response()->json([
            'message' => 'OTP resent successfully'
        ]);
    }
}