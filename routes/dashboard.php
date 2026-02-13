<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware(['auth', 'verified'])->group(function () {        
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/my-courses', [DashboardController::class, 'myCourse'])->name('dashboard.my-courses');
    Route::get('/notifications/index', [DashboardController::class, 'notifications'])->name('notifications.index');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');

    Route::get('dashboard/courses/', [DashboardController::class, 'courses'])->name('dashboard.courses.index');
    Route::get('/courses/most-popular', [DashboardController::class, 'mostPopular'])->name('courses.mostPopular');
    Route::get('/courses/{course:slug}', [DashboardController::class, 'courseSlug'])->name('courses.show');
    // Cart routes
    Route::get('/cart/index', [CartController::class, 'index'])->name('dashboard.cart.index');
    Route::post('/cart/add/{course}', [CartController::class, 'add'])->name('dashboard.cart.add');
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{enrollment}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
});


