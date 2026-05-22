<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\ActivityLoggerService;
use App\Models\ActivityLog;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers; 
    
    /**
     * Where to redirect users after login.
     */
    protected $redirectTo = '/admin/dashboard';
    
    /**
     * Maximum login attempts before lockout.
     */
    protected $maxAttempts = 5;
    
    /**
     * Lockout duration in minutes.
     */
    protected $decayMinutes = 30;
    
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Apply middleware in routes file instead
    }
    
    protected function guard()
    {
        return Auth::guard('admin');
    } 

    public function showLoginForm() 
    {
        // If user is already authenticated as admin, redirect to dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Redirect if accessing /login instead of /admin/login
        if (request()->is('login') && !request()->is('admin/*')) {
            return redirect()->route('admin.login');
        }
        
        return view('admin.auth.login');
    }
    
    public function login(Request $request)
    {
        // If already authenticated, redirect to dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if admin exists and is active
        $admin = Admin::where('email', $request->email)->first();
        
        if ($admin && !$admin->is_active) {
            // Log inactive account login attempt
            ActivityLoggerService::log(
                ActivityLog::EVENT_LOGIN_FAILED,
                'authentication',
                'Inactive admin account login attempt',
                "Login attempt on inactive admin account: {$admin->email}",
                $admin,  // ✅ Pass the admin as subject (Model)
                [
                    'ip' => $request->ip(),
                    'reason' => 'account_inactive',
                    'admin_role' => $admin->role
                ],
                ActivityLog::SEVERITY_WARNING
            );
            
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact the super administrator.',
            ])->withInput($request->except('password'));
        }

        // Attempt authentication
        if (Auth::guard('admin')->attempt(
            $request->only('email', 'password'), 
            $request->filled('remember')
        )) {
            $admin = Auth::guard('admin')->user();
            
            // Update last login info
            $admin->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Log successful admin login
            ActivityLoggerService::log(
                ActivityLog::EVENT_LOGIN,
                'authentication',
                'Admin logged in successfully',
                "Admin {$admin->name} ({$admin->email}) logged in successfully",
                $admin,  // ✅ Pass the admin as subject (Model)
                [
                    'ip' => $request->ip(),
                    'role' => $admin->role,
                    'remember' => $request->filled('remember')
                ],
                ActivityLog::SEVERITY_INFO
            );
            
            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'));
        }

        // Failed login attempt
        if ($admin) {
            // Log failed attempt for existing admin
            ActivityLoggerService::log(
                ActivityLog::EVENT_LOGIN_FAILED,
                'authentication',
                'Invalid admin credentials',
                "Failed login attempt for admin: {$admin->email}",
                $admin,  // ✅ Pass the admin as subject (Model)
                [
                    'ip' => $request->ip(),
                    'reason' => 'invalid_credentials',
                    'admin_role' => $admin->role
                ],
                ActivityLog::SEVERITY_WARNING
            );
        } else {
            // Log attempt with non-existent admin email
            ActivityLoggerService::log(
                ActivityLog::EVENT_LOGIN_FAILED,
                'authentication',
                'Admin login attempt with non-existent email',
                "Failed admin login attempt with non-existent email: {$request->email}",
                null,  // ✅ Pass null since admin doesn't exist
                [
                    'email' => $request->email,
                    'ip' => $request->ip(),
                    'reason' => 'admin_not_found'
                ],
                ActivityLog::SEVERITY_WARNING
            );
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Log admin logout BEFORE actually logging out
        if ($admin) {
            $sessionDuration = $admin->last_login_at 
                ? now()->diffInMinutes($admin->last_login_at) 
                : null;
            
            ActivityLoggerService::log(
                ActivityLog::EVENT_LOGOUT,
                'authentication',
                'Admin logged out',
                "Admin {$admin->name} ({$admin->email}) logged out",
                $admin,  // ✅ Pass the admin as subject (Model)
                [
                    'ip' => $request->ip(),
                    'role' => $admin->role,
                    'session_duration_minutes' => $sessionDuration
                ],
                ActivityLog::SEVERITY_INFO
            );
        }
        
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}