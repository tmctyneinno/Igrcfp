<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;

// Admin Dashboard Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');
    
    // Dashboard Statistics
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats'])
        ->name('dashboard.stats');
    
    // Recent Activities
    Route::get('/dashboard/activities', [AdminDashboardController::class, 'activities'])
        ->name('dashboard.activities');
    
    // Quick Actions
    Route::post('/dashboard/quick-action', [AdminDashboardController::class, 'quickAction'])
        ->name('dashboard.quick-action');
});