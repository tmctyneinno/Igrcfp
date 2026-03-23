<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Mail\OTPMail;

class AuthenticatedSessionController extends Controller
{ 
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /** 
     * Display the registration view.
     */
    public function showRegister(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        // Authenticate the user
        $request->authenticate();

        // Regenerate session to prevent session fixation
        $request->session()->regenerate();
        
        // Get the authenticated user
        $user = Auth::user();
        
        // IMPORTANT: Check if user exists
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Authentication failed.']);
        }
        
        // Check if user needs OTP verification
        if (!$user->is_verified) {
            // Generate and send OTP
            $otp = $user->generateOTP();
            
            // Send OTP via email
            try {
                Mail::to($user->email)->send(new OTPMail($otp));
                Log::info('OTP sent to email: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Failed to send OTP email: ' . $e->getMessage());
                // You might want to handle this error appropriately
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
            
            // Store the intended URL before redirecting to OTP verification
            // This helps redirect back after verification if needed
            if ($request->has('redirect')) {
                session(['redirect_after_verification' => $request->input('redirect')]);
            }
            
            // Redirect to OTP verification page
            return redirect()->route('verify-otp');
        }
        
        // Check for redirect parameter (for enrollment or specific pages)
        $redirect = $request->input('redirect');
        
        if ($redirect) {
            return redirect($redirect);
        }
        
        // Check if there's an intended URL (from auth middleware)
        if (session()->has('url.intended')) {
            $intended = session('url.intended');
            session()->forget('url.intended');
            return redirect($intended);
        }

        // Default redirect to dashboard
        return redirect()->route('dashboard.index');
    }


    /**
     * Handle an incoming registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        

        Auth::login($user);
 
        // Redirect to dashboard after registration
        return redirect()->route('dashboard.index'); // or 'dashboard.index' depending on your route name
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}