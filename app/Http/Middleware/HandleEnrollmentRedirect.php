<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleEnrollmentRedirect
{
    public function handle(Request $request, Closure $next)
    {
        // Check if there's a pending enrollment in session
        if (session()->has('intended_enrollment') && auth()->check()) {
            $courseSlug = session('intended_enrollment');
            session()->forget('intended_enrollment');
            
            return redirect()->route('courses.enroll', ['course' => $courseSlug]);
        }
        
        return $next($request);
    }
}