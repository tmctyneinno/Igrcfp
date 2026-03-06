<?php

use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController;
use Illuminate\Support\Facades\Route;

// Course Management Routes (Protected - admin & super_admin)
Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'admin.role:admin,super_admin'])->group(function () {
    // Courses Index
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    
    // Create Course
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    
    // Show Course
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/status', [CourseController::class, 'showStatus'])->name('courses.status');
    Route::get('/courses/toggle-featured', [CourseController::class, 'toggleFeatured'])->name('courses.toggle-featured');
    Route::get('/courses/toggle-popular', [CourseController::class, 'togglePopular'])->name('courses.toggle-popular');
    Route::post('/courses/{course}/materials/upload/', [CourseController::class, 'materialsUpload'])->name('courses.materials.upload');
    Route::post('/courses/bulk-action', [CourseController::class, 'bulkAction'])->name('courses.bulk-action');
    
    // Edit Course
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])
        ->name('courses.edit'); 
    
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    
    // Delete Course
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])
        ->name('courses.destroy');
    
    // Course Categories Routes
    Route::get('/courses/categories/index', [CourseCategoryController::class, 'index'])
        ->name('course-categories.index');
    Route::get('/courses/categories/create', [CourseCategoryController::class, 'create'])
        ->name('course-categories.create'); 
    Route::get('/courses/categories/store', [CourseCategoryController::class, 'create'])
        ->name('course-categories.create'); 
    // Route::resource('course-categories', CourseCategoryController::class);
    
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

// Module Management Routes
Route::prefix('admin/courses/{course}/modules')->name('admin.courses.modules.')->group(function () {
    Route::get('create', [ModuleController::class, 'create'])->name('create');
    Route::post('/', [ModuleController::class, 'store'])->name('store');
    Route::get('{module}/edit', [ModuleController::class, 'edit'])->name('edit');
    Route::put('{module}', [ModuleController::class, 'update'])->name('update');
    Route::delete('{module}', [ModuleController::class, 'destroy'])->name('destroy');
    Route::post('{module}/toggle-active', [ModuleController::class, 'toggleActive'])->name('toggle-active');
    Route::post('{module}/duplicate', [ModuleController::class, 'duplicate'])->name('duplicate');
    Route::post('reorder', [ModuleController::class, 'reorder'])->name('reorder');
});
   