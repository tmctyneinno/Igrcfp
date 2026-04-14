<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureMentorMembership
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->hasMentorMembership()) {
            return redirect()
                ->route('dashboard.memberships.index')
                ->with('error', 'Mentor applications require an active mentor membership.');
        }

        return $next($request);
    }
}
