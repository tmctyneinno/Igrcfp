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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

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
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'phone' => 'nullable|string|max:20|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'password' => Hash::make($request->password),
            'is_verified' => false, // User is not verified until OTP is confirmed
        ]);

        // Fire the registered event
        event(new Registered($user));

        // Generate OTP
        $otp = $user->generateOTP();
        
        // Send OTP via Email
        try {
            Mail::to($user->email)->send(new OTPMail($otp));
            Log::info('OTP sent to email: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email to ' . $user->email . ': ' . $e->getMessage());
            // Continue with registration even if email fails
        }
        
        // Send OTP via SMS if phone number exists
        if ($user->phone_number) {
            try {
                $this->sendSMS($user->phone_number, $otp);
                Log::info('OTP sent to phone: ' . $user->phone_number);
            } catch (\Exception $e) {
                Log::error('Failed to send OTP SMS to ' . $user->phone_number . ': ' . $e->getMessage());
                // Continue with registration even if SMS fails
            }
        }
        
        // Log the user in
        Auth::login($user);
        
        // Redirect to OTP verification page
        return redirect()->route('verify-otp');
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

    /**
     * Send OTP via SMS
     * 
     * @param string $phoneNumber
     * @param string $otp
     * @return void
     */
    private function sendSMS($phoneNumber, $otp)
    {
        // Implement your SMS service here
        // For testing, we'll just log it
        Log::info("SMS to {$phoneNumber}: Your verification code is {$otp}");
        
        // Example with Twilio (uncomment and configure to use):
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
        
        // Example with Vonage (Nexmo):
        /*
        use Vonage\Client;
        use Vonage\Client\Credentials\Basic;
        use Vonage\SMS\Message\SMS;
        
        $basic = new Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
        $client = new Client($basic);
        
        $response = $client->sms()->send(
            new SMS($phoneNumber, env('VONAGE_BRAND'), "Your verification code is: {$otp}")
        );
        */
        
        // Example with Africa's Talking:
        /*
        $username = env('AFRICASTALKING_USERNAME');
        $apiKey = env('AFRICASTALKING_API_KEY');
        
        $at = new \AfricaStalking\AfricaStalking($username, $apiKey);
        $at->sendMessage([
            'to' => $phoneNumber,
            'message' => "Your verification code is: {$otp}"
        ]);
        */
    }
}