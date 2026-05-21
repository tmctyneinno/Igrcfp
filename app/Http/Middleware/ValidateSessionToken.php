<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateSessionToken
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user         = Auth::user();
            $sessionToken = session('session_token');

            // ✅ Token mismatch means another browser logged in — force logout
            if (!$sessionToken || $sessionToken !== $user->active_session_token) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Your session was terminated because your account was logged in from another browser.'
                    ]);
            }
        }

        return $next($request);
    }
}