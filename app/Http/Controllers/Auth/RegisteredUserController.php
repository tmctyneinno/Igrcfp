<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // 'linkedin_url' => [
            //     'required', 
            //     'url', 
            //     'max:500',
            //     'regex:/^https?:\/\/(www\.)?linkedin\.com\/.+/i'
            // ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            // 'linkedin_url' =>  $request->linkedin_url,
            'password' => Hash::make($request->password),
            'email_verification_token' => Str::random(60),
        ]);

        event(new Registered($user));
        $this->sendVerificationEmail($user);

        return redirect()->route('login')->with('success', 'Registration successful! Please check your email to verify your account.');
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
