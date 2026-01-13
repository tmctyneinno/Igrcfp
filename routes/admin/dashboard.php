<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\BlogController;
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
    Route::get('/dashboard/activities', [AdminDashboardController::class, 'activities'])->name('dashboard.activities');
    // Quick Actions
    Route::post('/dashboard/quick-action', [AdminDashboardController::class, 'quickAction']) ->name('dashboard.quick-action');

    // Event Management
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{event}', [EventController::class, 'show'])->name('show');  // Changed from {id}
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');  // Changed from {id}
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [EventController::class, 'bulkAction'])->name('bulk-action');
        Route::patch('/{event}/toggle-featured', [EventController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::patch('/{blog}/toggle-status', [BlogController::class, 'toggleStatus'])->name('toggle-status');
    });
     // Blog Management
    Route::prefix('blogs')->name('blogs.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/', [BlogController::class, 'store'])->name('store');
        Route::get('/{blog}', [BlogController::class, 'show'])->name('show');
        Route::get('/{blog}/edit', [BlogController::class, 'edit'])->name('edit');
        Route::put('/{blog}', [BlogController::class, 'update'])->name('update');
        Route::delete('/{blog}', [BlogController::class, 'destroy'])->name('destroy');
        Route::patch('/{blog}/toggle-status', [BlogController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [BlogController::class, 'bulkAction'])->name('bulk-action');
    });

});