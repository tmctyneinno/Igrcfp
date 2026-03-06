<?php
// app/Http/Middleware/CheckAdminRole.php

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

        // If no specific roles are required, just check if it's any admin
        if (empty($roles)) {
            return $next($request);
        }

        foreach ($roles as $role) {
            if ($role === 'super_admin' && method_exists($admin, 'isSuperAdmin') && $admin->isSuperAdmin()) {
                return $next($request);
            }
            if ($role === 'admin' && method_exists($admin, 'isAdmin') && $admin->isAdmin()) {
                return $next($request);
            }
            if ($role === 'moderator' && method_exists($admin, 'isModerator') && $admin->isModerator()) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}