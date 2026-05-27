<?php

use App\Http\Controllers\Admin\AdminManagementController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // Admin Management Routes
    Route::resource('admins', AdminManagementController::class);
    Route::post('admins/{admin}/toggle-status', [AdminManagementController::class, 'toggleStatus'])
        ->name('admins.toggle-status');
});


// Include admin route files
require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/contacts.php';
require __DIR__.'/users.php';
require __DIR__.'/courses.php';
require __DIR__.'/memberships.php';
