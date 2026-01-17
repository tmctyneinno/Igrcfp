<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Use admin guard by default for admin routes
        if (empty($guards)) {
            $guards = ['admin'];
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $next($request);
            }
        }

        // For admin routes, redirect to admin login
        if ($request->is('admin/*')) {
            return redirect()->guest(route('admin.login'));
        }

        // Fallback for other routes
        return redirect()->guest(route('admin.login'));
    }
}