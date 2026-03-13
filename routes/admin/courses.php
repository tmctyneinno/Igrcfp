<?php

use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\AssessmentController;
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
    Route::resource('course-categories', CourseCategoryController::class);
    
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
   
// Admin assessment routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Main assessments page (NO parameter required)
    Route::get('/assessments', [AssessmentController::class, 'all'])->name('assessments.all');
    
    // Assessments filtered by course (WITH course parameter)
    Route::get('/assessments/course/{course}', [AssessmentController::class, 'index'])->name('assessments.course');
    
    // Create assessment (NO parameter required)
    Route::get('/assessments/create', [AssessmentController::class, 'create'])->name('assessments.create');
    Route::post('/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
    
    // Individual assessment routes (WITH assessment parameter)
    Route::get('/assessments/{assessment}', [AssessmentController::class, 'show'])->name('assessments.show');
    Route::get('/assessments/{assessment}/edit', [AssessmentController::class, 'edit'])->name('assessments.edit');
    Route::put('/assessments/{assessment}', [AssessmentController::class, 'update'])->name('assessments.update');
    Route::delete('/assessments/{assessment}', [AssessmentController::class, 'destroy'])->name('assessments.destroy');
    
    // Upload assessment for a specific course (WITH course parameter)
    Route::post('/assessments/course/{course}/upload', [AssessmentController::class, 'upload'])->name('assessments.upload');
    
    // Submissions routes
    Route::get('/assessments/{assessment}/submissions', [AssessmentController::class, 'submissions'])->name('assessments.submissions');
    Route::get('/assessments/submission/{submission}', [AssessmentController::class, 'viewSubmission'])->name('assessments.submission.view');
    Route::post('/assessments/submission/{submission}/grade', [AssessmentController::class, 'gradeSubmission'])->name('assessments.submission.grade');admin.
    Route::post('/assessments/submission/{submission}/grade', [AssessmentController::class, 'gradeSubmission'])->name('assessments.bulk-delete');admin.assessments.bulk-delete
});