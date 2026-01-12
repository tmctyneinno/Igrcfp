<?php

use App\Http\Controllers\Admin\AdminManagementController;
use Illuminate\Support\Facades\Route;

// Admin Management Routes (Protected - super_admin only)
Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'admin.role:super_admin'])->group(function () {
    // Admins List
    Route::get('/admins', [AdminManagementController::class, 'index'])
        ->name('admins.index');
    
    // Create Admin
    Route::get('/admins/create', [AdminManagementController::class, 'create'])
        ->name('admins.create');
    
    Route::post('/admins', [AdminManagementController::class, 'store'])
        ->name('admins.store');
    
    // Show Admin Profile
    Route::get('/admins/{admin}', [AdminManagementController::class, 'show'])
        ->name('admins.show')
        ->whereNumber('admin');
    
    // Edit Admin
    Route::get('/admins/{admin}/edit', [AdminManagementController::class, 'edit'])
        ->name('admins.edit')
        ->whereNumber('admin');
    
    Route::put('/admins/{admin}', [AdminManagementController::class, 'update'])
        ->name('admins.update')
        ->whereNumber('admin');
    
    // Delete Admin
    Route::delete('/admins/{admin}', [AdminManagementController::class, 'destroy'])
        ->name('admins.destroy')
        ->whereNumber('admin');
    
    // Admin Permissions/Roles
    Route::get('/admins/{admin}/permissions', [AdminManagementController::class, 'permissions'])
        ->name('admins.permissions')
        ->whereNumber('admin');
    
    Route::put('/admins/{admin}/permissions', [AdminManagementController::class, 'updatePermissions'])
        ->name('admins.permissions.update')
        ->whereNumber('admin');
    
    // Admin Activity Log
    Route::get('/admins/{admin}/activity', [AdminManagementController::class, 'activity'])
        ->name('admins.activity')
        ->whereNumber('admin');
    
    // Admin Status Management
    Route::put('/admins/{admin}/status', [AdminManagementController::class, 'updateStatus'])
        ->name('admins.status.update')
        ->whereNumber('admin');
    
    // Admin Profile (for current admin)
    Route::get('/profile', [AdminManagementController::class, 'profile'])
        ->name('profile');
    
    Route::put('/profile', [AdminManagementController::class, 'updateProfile'])
        ->name('profile.update');
    
    Route::put('/profile/password', [AdminManagementController::class, 'updatePassword'])
        ->name('profile.password.update');
});

// Include admin route files
require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/users.php';
require __DIR__.'/settings.php';