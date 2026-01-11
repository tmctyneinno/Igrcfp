<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

// Public admin routes (no authentication required)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.post');
});

// Protected admin routes (authentication required)
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // Logout
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management (accessible to all authenticated admins)
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users.index');
    
    // Super Admin only routes
    Route::middleware(['admin.role:super_admin'])->group(function () {
        Route::get('/admins', [AdminDashboardController::class, 'admins'])->name('admins.index');
        Route::get('/admins/create', [AdminDashboardController::class, 'createAdmin'])->name('admins.create');
        Route::post('/admins', [AdminDashboardController::class, 'storeAdmin'])->name('admins.store');
        Route::get('/admins/{id}/edit', [AdminDashboardController::class, 'editAdmin'])->name('admins.edit');
        Route::put('/admins/{id}', [AdminDashboardController::class, 'updateAdmin'])->name('admins.update');
        Route::delete('/admins/{id}', [AdminDashboardController::class, 'deleteAdmin'])->name('admins.destroy');
    });
    
    // Admin and Super Admin routes (both admin and super_admin roles)
    Route::middleware(['admin.role:admin,super_admin'])->group(function () {
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
        Route::put('/settings', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');
        
        // Course Management
        Route::get('/courses', [AdminDashboardController::class, 'courses'])->name('courses.index');
        Route::get('/courses/create', [AdminDashboardController::class, 'createCourse'])->name('courses.create');
        Route::post('/courses', [AdminDashboardController::class, 'storeCourse'])->name('courses.store');
        Route::get('/courses/{id}/edit', [AdminDashboardController::class, 'editCourse'])->name('courses.edit');
        Route::put('/courses/{id}', [AdminDashboardController::class, 'updateCourse'])->name('courses.update');
        Route::delete('/courses/{id}', [AdminDashboardController::class, 'deleteCourse'])->name('courses.destroy');
    });
    
    // Moderator, Admin, and Super Admin routes (all admin roles)
    Route::middleware(['admin.role:moderator,admin,super_admin'])->group(function () {
        Route::get('/content', [AdminDashboardController::class, 'content'])->name('content.index');
        Route::get('/content/create', [AdminDashboardController::class, 'createContent'])->name('content.create');
        Route::post('/content', [AdminDashboardController::class, 'storeContent'])->name('content.store');
        Route::get('/content/{id}/edit', [AdminDashboardController::class, 'editContent'])->name('content.edit');
        Route::put('/content/{id}', [AdminDashboardController::class, 'updateContent'])->name('content.update');
        Route::delete('/content/{id}', [AdminDashboardController::class, 'deleteContent'])->name('content.destroy');
    });
});