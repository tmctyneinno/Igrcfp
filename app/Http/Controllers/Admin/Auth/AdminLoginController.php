<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
        $this->middleware('guest:admin')->except('logout');
    }
    
    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function showLoginForm() 
    {
        // return Inertia::render('Admin/Auth/Login');
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
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