<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\AdminRegisterController;
use App\Http\Controllers\Admin\Auth\AdminForgotPasswordController;
use App\Http\Controllers\Admin\Auth\AdminResetPasswordController;
use Illuminate\Support\Facades\Route;

// Admin Authentication Routes (Public)
Route::prefix('admin')->name('admin.')->group(function () {
    // Login Routes
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])
        ->name('login')
        ->middleware('guest:admin');
     
    Route::post('/login', [AdminLoginController::class, 'login'])
        ->name('login.post')
        ->middleware('guest:admin');
    
    // Registration Routes (only for super_admin)
    Route::get('/register', [AdminRegisterController::class, 'showRegistrationForm'])
        ->name('register')
        ->middleware(['auth.admin', 'admin.role:super_admin']);
    
    Route::post('/register', [AdminRegisterController::class, 'register'])
        ->name('register.post')
        ->middleware(['auth.admin', 'admin.role:super_admin']);
    
    // Password Reset Routes
    Route::get('/password/reset', [AdminForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request')
        ->middleware('guest:admin');
    
    Route::post('/password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email')
        ->middleware('guest:admin');
    
    Route::get('/password/reset/{token}', [AdminResetPasswordController::class, 'showResetForm'])
        ->name('password.reset')
        ->middleware('guest:admin');
    
    Route::post('/password/reset', [AdminResetPasswordController::class, 'reset'])
        ->name('password.update')
        ->middleware('guest:admin');
    
    // Logout (Protected)
    Route::post('/logout', [AdminLoginController::class, 'logout'])
        ->name('logout')
        ->middleware('auth.admin');
});