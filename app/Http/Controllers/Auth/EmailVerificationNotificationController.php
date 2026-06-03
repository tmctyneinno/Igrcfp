<?php

namespace App\Http\Controllers\Auth;

use App\Mail\VerificationEmail;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard.index', absolute: false));
        }

        Mail::to($request->user()->email)->send(new VerificationEmail($request->user()));

        return back()->with('status', 'verification-link-sent');
    }
}
