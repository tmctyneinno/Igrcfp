<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EventController;
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

    Route::get('/events/index', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/show/{id}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/edit/{id}', [EventController::class, 'edit'])->name('events.edit');
    Route::post('/events/update/{id}', [EventController::class, 'update'])->name('events.update');
    Route::post('/events/bulk-action', [EventController::class, 'bulkAction'])->name('events.bulk-action');
    Route::patch('/events/{event}/toggle-featured', [EventController::class, 'toggleFeatured'])->name('events.toggle-featured');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

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