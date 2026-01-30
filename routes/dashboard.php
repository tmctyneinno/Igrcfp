<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware(['auth', 'verified'])->group(function () {        
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/my-courses', [DashboardController::class, 'myCourse'])->name('my-courses');
    Route::get('/notifications/index', [DashboardController::class, 'notifications'])->name('notifications.index');
    Route::get('/cart/index', [DashboardController::class, 'cart'])->name('cart.index');
    Route::get('/settings/index', [DashboardController::class, 'settings'])->name('settings.index');


});


