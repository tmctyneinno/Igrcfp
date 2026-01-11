<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\AdminRegisterController;
use App\Http\Controllers\Admin\Auth\AdminForgotPasswordController;
use App\Http\Controllers\Admin\Auth\AdminResetPasswordController;
use Illuminate\Support\Facades\Route;

// Admin Authentication Routes (Public)
Route::prefix('admin')->name('admin.')->group(function () {
    // Public routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.post');

        Route::get('/password/reset', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/password/reset/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/password/reset', [AdminResetPasswordController::class, 'reset'])->name('password.update');
    });

    // Protected routes
    Route::middleware(['auth:admin', 'check.admin.role:super_admin,admin'])->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/register', [AdminRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [AdminRegisterController::class, 'register'])->name('register.post');
    });
});
