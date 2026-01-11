<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
});
// routes/admin.php
use App\Http\Controllers\Admin\AdminDashboardController;

// Using the 'admin' middleware group
Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);
    
    // Super admin only
    Route::middleware('admin.role:super_admin')->group(function () {
        Route::get('/admins', [AdminDashboardController::class, 'admins']);
    });
    
    // Admin and super admin
    Route::middleware('admin.role:admin,super_admin')->group(function () {
        Route::get('/settings', [AdminDashboardController::class, 'settings']);
    });
});

// Protected routes
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users.index');
    
    // Super Admin only routes
    Route::middleware(['admin.role:super_admin'])->group(function () {
        Route::get('/admins', [AdminDashboardController::class, 'admins'])->name('admins.index');
    });
    
    // Admin and Super Admin routes
    Route::middleware(['admin.role:admin,super_admin'])->group(function () {
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
    });
});