<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdmin
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // If no specific guard is provided, use 'admin' guard
        $guards = empty($guards) ? ['admin'] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $next($request);
            }
        }

        // Redirect to admin login page
        return redirect()->guest(route('admin.login'));
    }
}