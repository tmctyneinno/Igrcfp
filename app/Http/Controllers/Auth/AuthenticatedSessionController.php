<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\OTPMail;
use App\Models\User;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
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
        \Log::info('reCAPTCHA Response:', [
            'has_response' => $request->has('g-recaptcha-response'),
            'response_value' => $request->input('g-recaptcha-response')
        ]);
        try {
            $request->validate([
                'g-recaptcha-response' => 'required|captcha'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('reCAPTCHA Validation Failed:', $e->errors());
            throw $e;
        }
        // Authenticate the user
        $request->authenticate();

        // Regenerate session
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
        
        // IMPORTANT: Logout the user immediately after generating OTP
        Auth::logout();
        
        // Store user ID and email in session for OTP verification
        session([
            'otp_user_id' => $user->id,
            'otp_user_email' => $user->email,
            'otp_created_at' => now()
        ]);
        
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
        
        // Store user info in session
        session([
            'otp_user_id' => $user->id,
            'otp_user_email' => $user->email
        ]);
        
        // Redirect to OTP verification page
        return redirect()->route('verify-otp');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    } 
}