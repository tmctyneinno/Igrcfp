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
        $request->authenticate();

        $request->session()->regenerate();
        
        // Log for debugging
        \Log::info('User logged in successfully', [
            'user_id' => Auth::id(),
            'redirect' => $request->input('redirect'),
            'intended' => session('url.intended')
        ]);
        
        // Check for redirect parameter (for enrollment or specific pages)
        $redirect = $request->input('redirect');
        
        if ($redirect) {
            // Check if this is an enrollment redirect
            if (str_contains($redirect, '/enroll')) {
                // Store in session that we came from enrollment
                session(['from_enrollment' => true]);
            }
            return redirect($redirect);
        }
        
        // Check if there's an intended URL (from auth middleware)
        if (session()->has('url.intended')) {
            $intendedUrl = session('url.intended');
            
            // If intended URL is an enrollment page, redirect there
            if (str_contains($intendedUrl, '/enroll')) {
                session(['from_enrollment' => true]);
            }
            
            return redirect()->intended();
        }

        // Default redirect to dashboard - use the correct route name
        return redirect()->route('dashboard.index'); // or 'dashboard.index' depending on your route name
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

         // Check for redirect parameter in the request
        $redirect = $request->input('redirect');

        if ($redirect) {
            // Check if this is an enrollment redirect
            if (str_contains($redirect, '/enroll')) {
                session(['from_enrollment' => true]);
            }
            return redirect($redirect);
        }
 
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