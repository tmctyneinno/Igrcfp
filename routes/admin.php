<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\AdminRegisterController;
use App\Http\Controllers\Admin\Auth\AdminForgotPasswordController;
use App\Http\Controllers\Admin\Auth\AdminResetPasswordController;
use Illuminate\Support\Facades\Route;

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Login Routes
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Registration Routes
    Route::get('/register', [AdminRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AdminRegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('/password/reset', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [AdminResetPasswordController::class, 'reset'])->name('password.update');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users.index');
    Route::get('/users/{id}', [AdminDashboardController::class, 'showUser'])->name('users.show');
    Route::put('/users/{id}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('users.destroy');
    
    // Course Management
    Route::get('/courses', [AdminDashboardController::class, 'courses'])->name('courses.index');
    Route::post('/courses', [AdminDashboardController::class, 'storeCourse'])->name('courses.store');
    Route::put('/courses/{id}', [AdminDashboardController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{id}', [AdminDashboardController::class, 'deleteCourse'])->name('courses.destroy');
    
    // Content Management
    Route::get('/content', [AdminDashboardController::class, 'content'])->name('content.index');
    Route::get('/content/create', [AdminDashboardController::class, 'createContent'])->name('content.create');
    Route::post('/content', [AdminDashboardController::class, 'storeContent'])->name('content.store');
    Route::get('/content/{id}/edit', [AdminDashboardController::class, 'editContent'])->name('content.edit');
    Route::put('/content/{id}', [AdminDashboardController::class, 'updateContent'])->name('content.update');
    Route::delete('/content/{id}', [AdminDashboardController::class, 'deleteContent'])->name('content.destroy');
    
    // Settings
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');
    
    // Admin Management (only for super_admin)
    Route::middleware(['admin.role:super_admin'])->group(function () {
        Route::get('/admins', [AdminDashboardController::class, 'admins'])->name('admins.index');
        Route::post('/admins', [AdminDashboardController::class, 'storeAdmin'])->name('admins.store');
        Route::put('/admins/{id}', [AdminDashboardController::class, 'updateAdmin'])->name('admins.update');
        Route::delete('/admins/{id}', [AdminDashboardController::class, 'deleteAdmin'])->name('admins.destroy');
    });
});