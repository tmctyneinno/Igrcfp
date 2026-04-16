<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\OTPMail;
use App\Models\User;
use App\Services\TwilioSmsService;  
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
    public function __construct(protected TwilioSmsService $sms) {}

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
        try {
            // 1. Validate reCAPTCHA first with custom messages
            $validated = $request->validate([
                'g-recaptcha-response' => 'required|captcha'
            ], [
                'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification to continue.',
                'g-recaptcha-response.captcha' => 'Security verification failed. Please try again.'
            ]);
            
            // 2. Log successful validation (optional)
            \Log::info('reCAPTCHA validation passed', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation failures with context
            \Log::warning('Login attempt failed reCAPTCHA validation', [
                'ip' => $request->ip(),
                'errors' => $e->errors()
            ]);
            
            // Redirect back with input except the reCAPTCHA field
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->except('g-recaptcha-response'));
        }
        
        // 3. Attempt authentication
        try {
            $request->authenticate();
        } catch (\Exception $e) {
            \Log::error('Authentication failed after reCAPTCHA', [
                'email' => $request->email,
                'ip' => $request->ip()
            ]);
            
            return redirect()->back()
                ->withErrors(['email' => 'Invalid credentials.'])
                ->withInput($request->except('password', 'g-recaptcha-response'));
        }
        
        // 4. Generate and send OTP
        $user = Auth::user();
        $otp = $user->generateOTP();
        
        // try {
        //     Mail::to($user->email)->send(new OTPMail($otp));
        //     \Log::info('OTP sent successfully for email', ['user_id' => $user->id]);
        // } catch (\Exception $e) {
        //     \Log::error('Failed to send OTP: ' . $e->getMessage(), [
        //         'user_id' => $user->id,
        //         'email' => $user->email
        //     ]);
            
        //     // Optional: Still allow login but inform user about OTP delay
        //     session()->flash('warning', 'OTP email may be delayed. Please check your inbox.');
        // }

         // ── Send via SMS if phone number exists ─────────────
        // if ($user->phone) {
        //     try {
        //         $this->sms->sendOtp($user->phone, $otp);
        //         \Log::info('OTP sent successfully OTP', ['user_id' => $user->id]);
        //     } catch (\Exception $e) {
        //         \Log::error('OTP SMS failed: ' . $e->getMessage());
        //     }
        // }
        
        // 5. Logout and store OTP session
        Auth::logout();
        
        session([
            'otp_user_id' => $user->id,
            'otp_user_email' => $user->email,
            'otp_created_at' => now(),
            'login_ip' => $request->ip()  // Additional security context
        ]);
        
        return redirect()->route('verify-otp')
            ->with('success', 'Verification code sent to ' . $user->email);
    }

    public function register(Request $request): RedirectResponse
    {
        // ✅ Validate reCAPTCHA first
        try {
            // 1. Validate reCAPTCHA first with custom messages
            $validated = $request->validate([
                'g-recaptcha-response' => 'required|captcha'
            ], [
                'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification to continue.',
                'g-recaptcha-response.captcha' => 'Security verification failed. Please try again.'
            ]);
            
            // 2. Log successful validation (optional)
            \Log::info('reCAPTCHA validation passed', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation failures with context
            \Log::warning('Login attempt failed reCAPTCHA validation', [
                'ip' => $request->ip(),
                'errors' => $e->errors()
            ]);
            
            // Redirect back with input except the reCAPTCHA field
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->except('g-recaptcha-response'));
        }
        
        // 3. Attempt authentication
        try {
            $request->authenticate();
        } catch (\Exception $e) {
            \Log::error('Authentication failed after reCAPTCHA', [
                'email' => $request->email,
                'ip' => $request->ip()
            ]);
            
            return redirect()->back()
                ->withErrors(['email' => 'Invalid credentials.'])
                ->withInput($request->except('password', 'g-recaptcha-response'));
        }
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
        
        Auth::logout();
        
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