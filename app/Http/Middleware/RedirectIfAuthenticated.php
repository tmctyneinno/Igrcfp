<?php
// app/Http/Middleware/RedirectIfAuthenticated.php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // If it's an admin guard, redirect to admin dashboard
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                
                // If it's web guard (regular user), redirect to user dashboard
                if ($guard === 'web') {
                    return redirect()->route('dashboard.index');
                }
                
                // Default fallback
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}