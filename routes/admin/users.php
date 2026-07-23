<?php

use App\Http\Controllers\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;
 
// User Management Routes (Protected - accessible to all admins)
Route::prefix('admin')->name('admin.')->middleware(['auth.admin'])->group(function () {
    // Users Index
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
      
    // User Profile
    Route::get('/users/{user}', [UserManagementController::class, 'show'])
        ->name('users.show')
        ->whereNumber('user');
    Route::get('/users/{user}/enrollments', [UserManagementController::class, 'enrollments'])->name('users.enrollments');
    
    // Create User (modal or page)
    Route::get('/users/create', [UserManagementController::class, 'create'])
        ->name('users.create');
    
    Route::post('/users', [UserManagementController::class, 'store'])
        ->name('users.store');
    
    // Edit User
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])
        ->name('users.edit')
        ->whereNumber('user');
    
    Route::put('/users/{user}', [UserManagementController::class, 'update'])
        ->name('users.update')
        ->whereNumber('user');
    
    // Delete User
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])
        ->name('users.destroy')
        ->whereNumber('user');
    
    // User Status Management
    Route::put('/users/{user}/status', [UserManagementController::class, 'updateStatus'])
        ->name('users.status.update')
        ->whereNumber('user');
    
    // User Bulk Actions
    Route::post('/users/bulk-action', [UserManagementController::class, 'bulkAction'])
        ->name('users.bulk-action');
    
    // User Search/Filter
    Route::get('/users/search', [UserManagementController::class, 'search'])
        ->name('users.search');
    
    // User Export
    Route::get('/users/export', [UserManagementController::class, 'export'])
        ->name('users.export');
    // In your admin routes file
    Route::post('/users/{user}/toggle-scholarship', [UserManagementController::class, 'toggleScholarship'])
    ->name('users.toggle-scholarship');
});