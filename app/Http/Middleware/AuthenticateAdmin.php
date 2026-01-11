<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdmin
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $guard = $guard ?? 'web';

        if (Auth::guard($guard)->check()) {
            if ($guard === 'admin') {
                return redirect()->route('admin.dashboard'); // admin users
            }

            return redirect()->route('home'); // normal users
        }

        return $next($request);
    }
}