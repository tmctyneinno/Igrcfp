<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ImpersonationController extends Controller
{
    public function impersonate(User $user)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin || !$admin->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent impersonating suspended/deleted users
        if ($user->status !== 'active' || $user->trashed()) {
            return back()->with('error', 'Cannot impersonate this user. Account is inactive or deleted.');
        }

        // Store the original admin ID
        session(['impersonator_id' => $admin->id]);

        // Log in as the target user on the WEB guard
        Auth::guard('web')->login($user);

        // ✅ FIX: Set the session token so ValidateSessionToken middleware passes
        // Generate a new session token for this impersonation session
        $sessionToken = \Illuminate\Support\Str::random(60);
        $user->active_session_token = $sessionToken;
        $user->save();
        
        // Set it in the current session
        session(['session_token' => $sessionToken]);

        Log::info('Admin impersonating user', [
            'admin_id' => $admin->id,
            'user_id' => $user->id,
            'user_email' => $user->email,
        ]);

        return redirect()->to(route('dashboard.index'));
    }

    public function stopImpersonate()
    {
        $impersonatorId = session('impersonator_id');

        if ($impersonatorId) {
            $admin = User::find($impersonatorId);
            
            if ($admin) {
                // ✅ Restore admin's session token when returning
                $adminToken = \Illuminate\Support\Str::random(60);
                $admin->active_session_token = $adminToken;
                $admin->save();

                Auth::guard('admin')->login($admin);
                
                // Set admin session token (if admin routes also use validate.session)
                session(['session_token' => $adminToken]);
                
                session()->forget('impersonator_id');
                
                return redirect()->route('admin.users.index')
                    ->with('success', 'Returned to admin account.');
            }
        }

        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')
            ->with('error', 'Session expired or invalid.');
    }
}