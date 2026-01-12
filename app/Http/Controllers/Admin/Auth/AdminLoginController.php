<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
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
     * Create a new controller instance.
     */
    public function __construct()
    {
        
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
    
    // Debug: Check if view exists
    $viewPath = 'admin.auth.login';
    
    if (!view()->exists($viewPath)) {
        // Log or dump to see what's happening
        \Log::error("View not found: {$viewPath}");
        
        // Check available views
        $allViews = scandir(resource_path('views/admin/auth/'));
        \Log::info('Available views in auth directory:', $allViews);
        
        // Return error for debugging
        return response()->json([
            'error' => 'View not found',
            'expected_path' => resource_path('views/admin/auth/login.blade.php'),
            'available_files' => $allViews,
            'base_path' => base_path(),
            'view_paths' => config('view.paths'),
        ]);
    }
    
    return view($viewPath);
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

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}