<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    public function showRegister(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        // Authenticate the user
        $request->authenticate();

        // Regenerate session to prevent session fixation
        $request->session()->regenerate();
        
        $user = Auth::user();
        
        // Generate OTP
        $otp = $user->generateOTP();
        
        // Send OTP via email
        try {
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP: ' . $e->getMessage());
        }
        
        // Send OTP via SMS if phone exists
        if ($user->phone_number) {
            try {
                $this->sendSMS($user->phone_number, $otp);
            } catch (\Exception $e) {
                \Log::error('Failed to send SMS: ' . $e->getMessage());
            }
        }
        
        // Store user ID in session for OTP verification
        session(['otp_user_id' => $user->id]);
        
        // Redirect to OTP verification page
        return redirect()->route('verify-otp');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'phone' => 'nullable|string|max:20|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        
        // Generate OTP
        $otp = $user->generateOTP();
        
        // Send OTP via email
        try {
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP: ' . $e->getMessage());
        }
        
        // Send OTP via SMS if phone exists
        if ($user->phone_number) {
            try {
                $this->sendSMS($user->phone_number, $otp);
            } catch (\Exception $e) {
                \Log::error('Failed to send SMS: ' . $e->getMessage());
            }
        }
        
        Auth::login($user);
        
        return redirect()->route('verify-otp');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    
    private function sendSMS($phoneNumber, $otp)
    {
        // Implement your SMS service here
        \Log::info("SMS to {$phoneNumber}: Your OTP is {$otp}");
    }
}