<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureActiveMembership
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->hasActiveMembership()) {
            return redirect()
                ->route('dashboard.memberships.index')
                ->with('error', 'You need an active membership to access mentorship features.');
        }

        return $next($request);
    }
}
