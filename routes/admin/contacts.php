<?php

use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\ResearchController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth.admin'])->group(function () {
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [ContactMessageController::class, 'index'])->name('index');
        Route::get('/{contactMessage}', [ContactMessageController::class, 'show'])->name('show');
    });

    // Research & White Papers Routes
    Route::prefix('research')->name('research.')->group(function () {
        Route::get('/', [ResearchController::class, 'index'])->name('index');
        Route::get('/create', [ResearchController::class, 'create'])->name('create');
        Route::post('/', [ResearchController::class, 'store'])->name('store');
        Route::get('/{research}/edit', [ResearchController::class, 'edit'])->name('edit');
        Route::put('/{research}', [ResearchController::class, 'update'])->name('update');
        Route::delete('/{research}', [ResearchController::class, 'destroy'])->name('destroy');
        Route::get('/categories', [ResearchController::class, 'categories'])->name('categories');
    });

});
 