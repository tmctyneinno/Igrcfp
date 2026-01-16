<?php

use App\Http\Controllers\Admin\CourseController;
use Illuminate\Support\Facades\Route;

// Course Management Routes (Protected - admin & super_admin)
Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'admin.role:admin,super_admin'])->group(function () {
    // Courses Index
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    
    // Create Course
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    
    // Show Course
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show')->whereNumber('course');
    Route::get('/courses/module', [CourseController::class, 'showModules'])->name('courses.modules.create');
    Route::get('/courses/status', [CourseController::class, 'showStatus'])->name('courses.status');
    Route::get('/courses/toggle-featured', [CourseController::class, 'toggleFeatured'])->name('courses.toggle-featured');
    Route::get('/courses/toggle-popular', [CourseController::class, 'togglePopular'])->name('courses.toggle-popular');
    Route::get('/courses/materialsuploadr', [CourseController::class, 'materialsUpload'])->name('courses.materials.upload');
    Route::get('/courses/bulk-action', [CourseController::class, 'materialsUpload'])->name('courses.bulk-action');
    
    // Edit Course
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])
        ->name('courses.edit')
        ->whereNumber('course');
    
    Route::put('/courses/{course}', [CourseController::class, 'update'])
        ->name('courses.update')
        ->whereNumber('course');
    
    // Delete Course
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])
        ->name('courses.destroy')
        ->whereNumber('course');
    
    // Course Categories
    Route::get('/course-categories', [CourseController::class, 'categories'])
        ->name('courses.categories.index');
    
    Route::post('/course-categories', [CourseController::class, 'storeCategory'])
        ->name('courses.categories.store');
    
    Route::put('/course-categories/{category}', [CourseController::class, 'updateCategory'])
        ->name('courses.categories.update');
    
    Route::delete('/course-categories/{category}', [CourseController::class, 'destroyCategory'])
        ->name('courses.categories.destroy');
    
    // Course Enrollments
    Route::get('/courses/{course}/enrollments', [CourseController::class, 'enrollments'])
        ->name('courses.enrollments')
        ->whereNumber('course');
    
    // Course Reviews/Feedback
    Route::get('/courses/{course}/reviews', [CourseController::class, 'reviews'])
        ->name('courses.reviews')
        ->whereNumber('course');
    
    // Course Analytics
    Route::get('/courses/{course}/analytics', [CourseController::class, 'analytics'])
        ->name('courses.analytics')
        ->whereNumber('course');
    
    // Course Publish/Unpublish
    Route::put('/courses/{course}/publish', [CourseController::class, 'publish'])
        ->name('courses.publish')
        ->whereNumber('course');
});