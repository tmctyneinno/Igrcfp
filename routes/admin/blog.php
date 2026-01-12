<?php

use App\Http\Controllers\Admin\BlogController;
use Illuminate\Support\Facades\Route;

// Blog Management Routes
Route::prefix('blog')->name('blog.')->group(function () {
    
    // Blog Posts - List all posts
    Route::get('/posts', [BlogController::class, 'index'])->name('.index');
    
    // Create new post
    Route::get('/posts/create', [BlogController::class, 'create'])->name('posts.create');
    Route::post('/posts', [BlogController::class, 'store'])->name('posts.store');
    
    // Edit post
    Route::get('/posts/{post}/edit', [BlogController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [BlogController::class, 'update'])->name('posts.update');
    
    // Delete post
    Route::delete('/posts/{post}', [BlogController::class, 'destroy'])->name('posts.destroy');
    
    // Show post (preview)
    Route::get('/posts/{post}', [BlogController::class, 'show'])->name('posts.show');
    
    // Blog Categories
    Route::get('/categories', [BlogController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [BlogController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [BlogController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [BlogController::class, 'destroyCategory'])->name('categories.destroy');
    
    // Blog Tags
    Route::get('/tags', [BlogController::class, 'tags'])->name('tags.index');
    Route::post('/tags', [BlogController::class, 'storeTag'])->name('tags.store');
    Route::put('/tags/{tag}', [BlogController::class, 'updateTag'])->name('tags.update');
    Route::delete('/tags/{tag}', [BlogController::class, 'destroyTag'])->name('tags.destroy');
    
    // Blog Comments
    Route::get('/comments', [BlogController::class, 'comments'])->name('comments.index');
    Route::put('/comments/{comment}/approve', [BlogController::class, 'approveComment'])->name('comments.approve');
    Route::delete('/comments/{comment}', [BlogController::class, 'destroyComment'])->name('comments.destroy');
    
    // Bulk Actions
    Route::post('/posts/bulk-actions', [BlogController::class, 'bulkActions'])->name('posts.bulk');
    Route::post('/comments/bulk-actions', [BlogController::class, 'bulkCommentActions'])->name('comments.bulk');
});