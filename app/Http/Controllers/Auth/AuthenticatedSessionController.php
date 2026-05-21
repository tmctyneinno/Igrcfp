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
        // 1. Validate reCAPTCHA
        try {
            $request->validate([
                'g-recaptcha-response' => 'required|captcha'
            ], [
                'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification to continue.',
                'g-recaptcha-response.captcha' => 'Security verification failed. Please try again.'
            ]);

            \Log::info('reCAPTCHA validation passed', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Login attempt failed reCAPTCHA validation', [
                'ip' => $request->ip(),
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->except('g-recaptcha-response'));
        }

        // 2. Attempt authentication
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

        // 3. Generate and send OTP
        $user = Auth::user();
        $otp = $user->generateOTP(); // ✅ Generated once and saved to DB

        // ✅ Send via email
        try {
            Mail::to($user->email)->send(new OTPMail($otp));
            \Log::info('OTP email sent successfully', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            session()->flash('warning', 'OTP email may be delayed. Please check your inbox.');
        }

        // ✅ Optionally send via SMS if phone number exists
        // if ($user->phone_number) {
        //     try {
        //         $this->sms->sendOtp($user->phone_number, $otp);
        //         \Log::info('OTP SMS sent successfully', ['user_id' => $user->id]);
        //     } catch (\Exception $e) {
        //         \Log::error('OTP SMS failed: ' . $e->getMessage());
        //     }
        // }

        // 4. Logout and store session
        Auth::logout();

        session([
            'otp_user_id'    => $user->id,
            'otp_user_email' => $user->email,
            'otp_created_at' => now(),
            'login_ip'       => $request->ip()
        ]);

        return redirect()->route('verify-otp')
            ->with('success', 'Verification code sent to ' . $user->email);
    }

    public function register(Request $request): RedirectResponse
    {
        // 1. Validate reCAPTCHA
        try {
            $request->validate([
                'g-recaptcha-response' => 'required|captcha'
            ], [
                'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification to continue.',
                'g-recaptcha-response.captcha' => 'Security verification failed. Please try again.'
            ]);

            \Log::info('reCAPTCHA validation passed', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Register attempt failed reCAPTCHA validation', [
                'ip' => $request->ip(),
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->except('g-recaptcha-response'));
        }

        // 2. Validate registration fields
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'phone'    => 'nullable|string|max:20|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 3. Create user
        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone,
            'password'     => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // 4. Generate and send OTP
        $otp = $user->generateOTP(); // ✅ Generated once and saved to DB

        try {
            Mail::to($user->email)->send(new OTPMail($otp));
            \Log::info('OTP email sent after registration', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP after registration: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email'   => $user->email
            ]);
        }

        // 5. Store session and redirect to OTP verification
        session([
            'otp_user_id'    => $user->id,
            'otp_user_email' => $user->email,
            'otp_created_at' => now(),
        ]);

        return redirect()->route('verify-otp')
            ->with('success', 'Verification code sent to ' . $user->email);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}