<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPMail;
use App\Models\User;
use App\Services\ActivityLoggerService;
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
            // Log OTP verification failure due to expired session
            ActivityLoggerService::log(
                \App\Models\ActivityLog::EVENT_LOGIN,
                'authentication',
                'OTP verification failed - session expired',
                'OTP verification attempt with expired session',
                null,
                [
                    'ip' => $request->ip(),
                    'reason' => 'session_expired'
                ],
                \App\Models\ActivityLog::SEVERITY_WARNING
            );
            
            return response()->json(['error' => 'Session expired. Please login again.'], 401);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Block if another browser has taken over the session
        $sessionToken = session('session_token');
        if (!$sessionToken || $sessionToken !== $user->active_session_token) {
            // Log session takeover attempt
            ActivityLoggerService::log(
                \App\Models\ActivityLog::EVENT_LOGIN,
                'authentication',
                'OTP verification failed - session token mismatch',
                "Session token mismatch for user: {$user->email}",
                $user,
                [
                    'ip' => $request->ip(),
                    'reason' => 'session_token_mismatch'
                ],
                \App\Models\ActivityLog::SEVERITY_WARNING
            );
            
            return response()->json([
                'error' => 'Your account has been logged in from another browser. Please login again.'
            ], 401);
        }

        if ($user->verifyOTP($request->otp_code)) {
            // Log OTP verification success
            ActivityLoggerService::log(
                \App\Models\ActivityLog::EVENT_LOGIN,
                'authentication',
                'OTP verified successfully',
                "User {$user->email} successfully verified OTP",
                $user,
                [
                    'ip' => $request->ip(),
                    'verification_method' => 'otp'
                ],
                \App\Models\ActivityLog::SEVERITY_INFO
            );
            
            Auth::login($user);

            // Persist session token after login so middleware can validate it
            session([
                'session_token' => $user->active_session_token,
            ]);

            session()->forget('otp_user_id');

            return response()->json([
                'success'  => true,
                'redirect' => route('dashboard.index')
            ]);
        }

        // Log invalid OTP attempt
        ActivityLoggerService::log(
            \App\Models\ActivityLog::EVENT_LOGIN,
            'authentication',
            'Invalid OTP code entered',
            "User {$user->email} entered invalid OTP code",
            $user,
            [
                'ip' => $request->ip(),
                'reason' => 'invalid_otp'
            ],
            \App\Models\ActivityLog::SEVERITY_WARNING
        );

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
            
            // Log OTP resend
            ActivityLoggerService::log(
                \App\Models\ActivityLog::EVENT_LOGIN,
                'authentication',
                'OTP resent',
                "OTP resent to {$user->email}",
                $user,
                [
                    'ip' => $request->ip(),
                    'action' => 'resend_otp'
                ],
                \App\Models\ActivityLog::SEVERITY_INFO
            );
            
        } catch (\Exception $e) {
            \Log::error('Failed to resend OTP: ' . $e->getMessage());
            
            // Log OTP resend failure
            ActivityLoggerService::log(
                \App\Models\ActivityLog::EVENT_LOGIN,
                'authentication',
                'Failed to resend OTP',
                "Failed to resend OTP to {$user->email}",
                $user,
                [
                    'error' => $e->getMessage(),
                    'ip' => $request->ip()
                ],
                \App\Models\ActivityLog::SEVERITY_ERROR
            );
            
            return response()->json(['error' => 'Failed to send OTP'], 500);
        }
        
        return response()->json([
            'message' => 'OTP resent successfully'
        ]);
    }
}