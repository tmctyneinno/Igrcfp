<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\OTPMail;
use App\Models\User;
use App\Services\TwilioSmsService;  
use App\Services\ActivityLoggerService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules;
use App\Mail\AccountLockedMail;
use App\Services\BrevoMailService;
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
                'g-recaptcha-response.captcha'  => 'Security verification failed. Please try again.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Login failed reCAPTCHA', ['ip' => $request->ip()]);
            
            // Log reCAPTCHA failure
            ActivityLoggerService::log(
                \App\Models\ActivityLog::EVENT_LOGIN_FAILED,
                'authentication',
                'reCAPTCHA verification failed',
                'reCAPTCHA verification failed for email: ' . ($request->email ?? 'not provided'),
                null,
                [
                    'ip' => $request->ip(),
                    'reason' => 'recaptcha_failed'
                ],
                \App\Models\ActivityLog::SEVERITY_WARNING
            );
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->except('g-recaptcha-response'));
        }

        // 2. Find user and check if account is locked BEFORE authenticating
        $user = User::where('email', $request->email)->first();

        if ($user && $user->isLocked()) {
            $minutesLeft = (int) ceil(now()->diffInSeconds($user->locked_until) / 60);
            
            // Log locked account attempt
            ActivityLoggerService::log(
                \App\Models\ActivityLog::EVENT_LOGIN_FAILED,
                'authentication',
                'Login attempted on locked account',
                "Login attempt on locked account: {$user->email}",
                $user,
                [
                    'locked_until' => $user->locked_until->toDateTimeString(),
                    'ip' => $request->ip(),
                    'reason' => 'account_locked'
                ],
                \App\Models\ActivityLog::SEVERITY_WARNING
            );
            
            return redirect()->back()
                ->withErrors([
                    'email' => "Your account is locked due to too many failed attempts. Try again in {$minutesLeft} minute(s)."
                ])
                ->withInput($request->except('password', 'g-recaptcha-response'));
        }

        // 3. Attempt authentication
        try {
            $request->authenticate();
        } catch (\Exception $e) {
            // Increment failed attempts if user exists
            if ($user) {
                $user->incrementFailedAttempts();
                
                $attemptsLeft = max(0, 5 - $user->failed_login_attempts);

                // Account just got locked
                if ($user->isLocked()) {
                    try {
                        Mail::to($user->email)->send(new AccountLockedMail($user));
                    } catch (\Exception $mailException) {
                        \Log::error('Failed to send account locked email: ' . $mailException->getMessage());
                    }

                    // Log account locked event
                    ActivityLoggerService::log(
                        \App\Models\ActivityLog::EVENT_LOGIN_FAILED,
                        'authentication',
                        'Account locked after multiple failed attempts',
                        "Account locked: {$user->email} after 5 failed login attempts",
                        $user,
                        [
                            'failed_attempts' => $user->failed_login_attempts,
                            'locked_until' => $user->locked_until->toDateTimeString(),
                            'ip' => $request->ip(),
                            'reason' => 'account_locked_max_attempts'
                        ],
                        \App\Models\ActivityLog::SEVERITY_CRITICAL
                    );

                    return redirect()->back()
                        ->withErrors([
                            'email' => 'Your account has been locked after 5 failed attempts. Please check your email or reset your password.'
                        ])
                        ->withInput($request->except('password', 'g-recaptcha-response'));
                }

                // Log failed login attempt
                ActivityLoggerService::log(
                    \App\Models\ActivityLog::EVENT_LOGIN_FAILED,
                    'authentication',
                    'Invalid credentials',
                    "Failed login attempt for: {$user->email} (Attempts remaining: {$attemptsLeft})",
                    $user,
                    [
                        'failed_attempts' => $user->failed_login_attempts,
                        'attempts_remaining' => $attemptsLeft,
                        'ip' => $request->ip(),
                        'reason' => 'invalid_credentials'
                    ],
                    \App\Models\ActivityLog::SEVERITY_WARNING
                );

                return redirect()->back()
                    ->withErrors([
                        'email' => "Invalid credentials. {$attemptsLeft} attempt(s) remaining before your account is locked."
                    ])
                    ->withInput($request->except('password', 'g-recaptcha-response'));
            }
            
            // Log failed attempt for non-existent user
            ActivityLoggerService::log(
                \App\Models\ActivityLog::EVENT_LOGIN_FAILED,
                'authentication',
                'Login attempt with non-existent email',
                "Failed login attempt for non-existent email: {$request->email}",
                null,
                [
                    'email' => $request->email,
                    'ip' => $request->ip(),
                    'reason' => 'user_not_found'
                ],
                \App\Models\ActivityLog::SEVERITY_WARNING
            );
            
            return redirect()->back()
                ->withErrors(['email' => 'Invalid credentials.'])
                ->withInput($request->except('password', 'g-recaptcha-response'));
        }

        // 4. Successful login — reset failed attempts
        $user = Auth::user();
        $user->resetFailedAttempts();

        // Log successful login
        ActivityLoggerService::log(
            \App\Models\ActivityLog::EVENT_LOGIN,
            'authentication',
            'User logged in successfully',
            "User {$user->email} logged in successfully",
            $user,
            [
                'ip' => $request->ip(),
                'browser' => $request->userAgent()
            ],
            \App\Models\ActivityLog::SEVERITY_INFO
        );
 
        // 5. Generate OTP and send email
        $otp = $user->generateOTP();

        try {
            // Mail::to($user->email)->send(new OTPMail($otp));
            $brevoMail = new BrevoMailService();
            $brevoMail->sendOTP($user->email, $otp);
            \Log::info('OTP email sent successfully', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);
            
            // Log OTP email failure
            ActivityLoggerService::log(
                \App\Models\ActivityLog::EVENT_LOGIN,
                'authentication',
                'OTP email failed to send',
                "Failed to send OTP email to {$user->email}",
                $user,
                [
                    'error' => $e->getMessage(),
                    'ip' => $request->ip()
                ],
                \App\Models\ActivityLog::SEVERITY_ERROR
            );
            
            session()->flash('warning', 'OTP email may be delayed. Please check your inbox.');
        }
 
        // 6. Logout and store OTP session
        Auth::logout();
        // Assign FIRST before using in session()
        $sessionToken = $user->generateSessionToken();

        session([
            'otp_user_id'    => $user->id,
            'otp_user_email' => $user->email,
            'otp_created_at' => now(),
            'login_ip'       => $request->ip(),
            'session_token'  => $sessionToken,
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
            'phone'        => $request->phone,
            'password'     => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Log user registration
        ActivityLoggerService::log(
            \App\Models\ActivityLog::EVENT_CREATED,
            'users',
            'New user registered',
            "New user registered: {$user->name} ({$user->email})",
            $user,
            [
                'name' => $user->name,
                'email' => $user->email,
                'ip' => $request->ip()
            ],
            \App\Models\ActivityLog::SEVERITY_INFO
        );

        // 4. Generate and send OTP
        $otp = $user->generateOTP();

        try {
            Mail::to($user->email)->send(new OTPMail($otp));
            \Log::info('OTP email sent after registration', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP after registration: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email'   => $user->email
            ]);
            
            // Log OTP email failure
            ActivityLoggerService::log(
                \App\Models\ActivityLog::EVENT_CREATED,
                'users',
                'OTP email failed to send after registration',
                "Failed to send OTP email to newly registered user: {$user->email}",
                $user,
                [
                    'error' => $e->getMessage(),
                    'ip' => $request->ip()
                ],
                \App\Models\ActivityLog::SEVERITY_ERROR
            );
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
        $user = Auth::user();
        
        // Log logout BEFORE actually logging out
        if ($user) {
            ActivityLoggerService::log(
                \App\Models\ActivityLog::EVENT_LOGOUT,
                'authentication',
                'User logged out',
                "User {$user->email} logged out",
                $user,
                [
                    'ip' => $request->ip(),
                    'session_duration' => now()->diffInMinutes($user->last_login_at ?? now())
                ],
                \App\Models\ActivityLog::SEVERITY_INFO
            );
        }
        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}