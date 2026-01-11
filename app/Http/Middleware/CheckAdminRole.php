<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return redirect()->route('admin.login');
        }

        foreach ($roles as $role) {
            if ($role === 'super_admin' && $admin->isSuperAdmin()) {
                return $next($request);
            }
            if ($role === 'admin' && $admin->isAdmin()) {
                return $next($request);
            }
            if ($role === 'moderator' && $admin->isModerator()) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}