<?php

use App\Http\Controllers\Admin\CourseManagementController;
use Illuminate\Support\Facades\Route;

// Course Management Routes (Protected - admin & super_admin)
Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'admin.role:admin,super_admin'])->group(function () {
    // Courses Index
    Route::get('/courses', [CourseManagementController::class, 'index'])
        ->name('courses.index');
    
    // Create Course
    Route::get('/courses/create', [CourseManagementController::class, 'create'])
        ->name('courses.create');
    
    Route::post('/courses', [CourseManagementController::class, 'store'])
        ->name('courses.store');
    
    // Show Course
    Route::get('/courses/{course}', [CourseManagementController::class, 'show'])
        ->name('courses.show')
        ->whereNumber('course');
    
    // Edit Course
    Route::get('/courses/{course}/edit', [CourseManagementController::class, 'edit'])
        ->name('courses.edit')
        ->whereNumber('course');
    
    Route::put('/courses/{course}', [CourseManagementController::class, 'update'])
        ->name('courses.update')
        ->whereNumber('course');
    
    // Delete Course
    Route::delete('/courses/{course}', [CourseManagementController::class, 'destroy'])
        ->name('courses.destroy')
        ->whereNumber('course');
    
    // Course Categories
    Route::get('/course-categories', [CourseManagementController::class, 'categories'])
        ->name('courses.categories.index');
    
    Route::post('/course-categories', [CourseManagementController::class, 'storeCategory'])
        ->name('courses.categories.store');
    
    Route::put('/course-categories/{category}', [CourseManagementController::class, 'updateCategory'])
        ->name('courses.categories.update');
    
    Route::delete('/course-categories/{category}', [CourseManagementController::class, 'destroyCategory'])
        ->name('courses.categories.destroy');
    
    // Course Enrollments
    Route::get('/courses/{course}/enrollments', [CourseManagementController::class, 'enrollments'])
        ->name('courses.enrollments')
        ->whereNumber('course');
    
    // Course Reviews/Feedback
    Route::get('/courses/{course}/reviews', [CourseManagementController::class, 'reviews'])
        ->name('courses.reviews')
        ->whereNumber('course');
    
    // Course Analytics
    Route::get('/courses/{course}/analytics', [CourseManagementController::class, 'analytics'])
        ->name('courses.analytics')
        ->whereNumber('course');
    
    // Course Publish/Unpublish
    Route::put('/courses/{course}/publish', [CourseManagementController::class, 'publish'])
        ->name('courses.publish')
        ->whereNumber('course');
});