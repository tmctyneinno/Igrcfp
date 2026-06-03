<?php

use App\Http\Controllers\Admin\ContentManagementController;

use Illuminate\Support\Facades\Route;

// Content Management Routes (Protected - all admin roles)
Route::prefix('admin')->name('admin.')->middleware(['auth.admin', 'admin.role:moderator,admin,super_admin'])->group(function () {
    // Content Index (Blog/Articles/Pages)
    Route::get('/content', [ContentManagementController::class, 'index'])
        ->name('content.index');
    
    // Create Content
    Route::get('/content/create', [ContentManagementController::class, 'create'])
        ->name('content.create');
    
    Route::post('/content', [ContentManagementController::class, 'store'])
        ->name('content.store');
    
    // Show Content
    Route::get('/content/{content}', [ContentManagementController::class, 'show'])
        ->name('content.show')
        ->whereNumber('content');
    
    // Edit Content
    Route::get('/content/{content}/edit', [ContentManagementController::class, 'edit'])
        ->name('content.edit')
        ->whereNumber('content');
    
    Route::put('/content/{content}', [ContentManagementController::class, 'update'])
        ->name('content.update')
        ->whereNumber('content');
    
    // Delete Content
    Route::delete('/content/{content}', [ContentManagementController::class, 'destroy'])
        ->name('content.destroy')
        ->whereNumber('content');
    
    // Content Categories
    Route::get('/content-categories', [ContentManagementController::class, 'categories'])
        ->name('content.categories.index');
    
    Route::post('/content-categories', [ContentManagementController::class, 'storeCategory'])
        ->name('content.categories.store');
    
    Route::put('/content-categories/{category}', [ContentManagementController::class, 'updateCategory'])
        ->name('content.categories.update');
    
    Route::delete('/content-categories/{category}', [ContentManagementController::class, 'destroyCategory'])
        ->name('content.categories.destroy');
    
    // Content Tags
    Route::get('/content-tags', [ContentManagementController::class, 'tags'])
        ->name('content.tags.index');
    
    Route::post('/content-tags', [ContentManagementController::class, 'storeTag'])
        ->name('content.tags.store');
    
    // Media Library
    Route::get('/media', [ContentManagementController::class, 'media'])
        ->name('content.media.index');
    
    Route::post('/media', [ContentManagementController::class, 'uploadMedia'])
        ->name('content.media.upload');
    
    Route::delete('/media/{media}', [ContentManagementController::class, 'deleteMedia'])
        ->name('content.media.destroy');
    
    // Content Comments/Reviews
    Route::get('/comments', [ContentManagementController::class, 'comments'])
        ->name('content.comments.index');
    
    Route::put('/comments/{comment}/approve', [ContentManagementController::class, 'approveComment'])
        ->name('content.comments.approve');
    
    Route::delete('/comments/{comment}', [ContentManagementController::class, 'deleteComment'])
        ->name('content.comments.destroy');
    
    // Content Publish/Unpublish
    Route::put('/content/{content}/publish', [ContentManagementController::class, 'publish'])
        ->name('content.publish')
        ->whereNumber('content');
    
    // Content Bulk Actions
    Route::post('/content/bulk-action', [ContentManagementController::class, 'bulkAction'])
        ->name('content.bulk-action');

    
});