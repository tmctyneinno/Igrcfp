<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Mail\VerificationEmail;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

   

    public function store(Request $request): RedirectResponse
    {
        try {
            $validator  = $request->validate([
                'name' => 'required|string|max:255',
                'role' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            if ($validator->fails()) {
                // Log validation errors
                \Log::warning('Registration Attempt Failed - Validation Errors', [
                    'errors' => $validator->errors()->all(),
                    'failed_fields' => $validator->failed(),
                    'input' => $request->except('password', 'password_confirmation'),
                    'ip' => $request->ip(),
                    'timestamp' => now()->toDateTimeString(),
                ]);
                return back()->withErrors([
                'message' => 'Registration failed due to a system error. Please try again.',
            ])->withInput();
                // Instead of redirect()->back(), use Inertia to preserve errors
                return Inertia::render('Auth/Register', [
                    'errors' => $validator->errors()->toArray(),
                    'old' => $request->all(),
                ])->with('status', 'validation-failed');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'phone' => $request->phone,
                'linkedin_url' => $request->linkedin_url,
                'password' => Hash::make($request->password),
                'email_verification_token' => Str::random(60),
                'email_verified_at' => null,
            ]);

            // Log successful registration
            \Log::info('User Registered Successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            event(new Registered($user));
            $this->sendVerificationEmail($user);
            Auth::login($user);
            
            return redirect()->route('verification.notice');

        } catch (ValidationException $e) {
            // Log validation errors
            \Log::warning('Registration Attempt Failed - Validation Errors', [
                'errors' => $e->errors(),
                'failed_fields' => $e->validator->failed(),
                'input' => $request->except('password', 'password_confirmation'),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString(),
            ]);
            
            // Let Inertia handle the error response
            throw $e;
            
        } catch (\Exception $e) {
            // Log registration failure
            \Log::error('Registration Failed - Database Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except('password', 'password_confirmation'),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString(),
            ]);
            
            return back()->withErrors([
                'message' => 'Registration failed due to a system error. Please try again.',
            ])->withInput();
        }
    }
    
     protected function sendVerificationEmail(User $user): void
    { 
        try {
            Mail::to($user->email)->send(new VerificationEmail($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
