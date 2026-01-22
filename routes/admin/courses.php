<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController;
use Illuminate\Support\Facades\Route;

// Course Management Routes (Protected - admin & super_admin)
Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'admin.role:admin,super_admin'])->group(function () {
    
    // ==================== COURSE ROUTES ====================
      
    // -------- Non-parameter routes (come FIRST) --------
    Route::prefix('courses')->name('courses.')->group(function () {
        // Index (list all courses)
        Route::get('/', [CourseController::class, 'index'])->name('index');
        
        // Create form
        Route::get('/create', [CourseController::class, 'create'])->name('create');
        
        // Store new course
        Route::post('/', [CourseController::class, 'store'])->name('store');
        
        // Bulk actions
        Route::post('/bulk-action', [CourseController::class, 'bulkAction'])->name('bulk-action');
        
        // Status management
        Route::get('/status', [CourseController::class, 'showStatus'])->name('status');
        
        // Toggle features (use PATCH for updates)
        Route::patch('/toggle-featured', [CourseController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::patch('/toggle-popular', [CourseController::class, 'togglePopular'])->name('toggle-popular');
    });
    
    // -------- Course parameter routes (come AFTER) --------
    Route::prefix('courses/{course}')->name('courses.')->whereNumber('course')->group(function () {
        // Show single course
        Route::get('/', [CourseController::class, 'show'])->name('show');
        
        // Edit form
        Route::get('/edit', [CourseController::class, 'edit'])->name('edit');
        
        // Update course
        Route::put('/', [CourseController::class, 'update'])->name('update');
        
        // Delete course
        Route::delete('/', [CourseController::class, 'destroy'])->name('destroy');
        
        // Course-specific actions
        Route::post('/materials/upload', [CourseController::class, 'materialsUpload'])->name('materials.upload');
        Route::patch('/publish', [CourseController::class, 'publish'])->name('publish');
        
        // Course relationships
        Route::get('/enrollments', [CourseController::class, 'enrollments'])->name('enrollments');
        Route::get('/reviews', [CourseController::class, 'reviews'])->name('reviews');
        Route::get('/analytics', [CourseController::class, 'analytics'])->name('analytics');
        
        // ==================== MODULE ROUTES ====================
        Route::prefix('modules')->name('modules.')->group(function () {
            // Module index (list modules for a course)
            Route::get('/', [ModuleController::class, 'index'])->name('index');
            
            // Create module form
            Route::get('/create', [ModuleController::class, 'create'])->name('create');
            
            // Store new module
            Route::post('/', [ModuleController::class, 'store'])->name('store');
            
            // Module reordering
            Route::patch('/reorder', [ModuleController::class, 'reorder'])->name('reorder');
            
            // Module parameter routes
            Route::prefix('{module}')->whereNumber('module')->group(function () {
                // Edit module form
                Route::get('/edit', [ModuleController::class, 'edit'])->name('edit');
                
                // Update module
                Route::put('/', [ModuleController::class, 'update'])->name('update');
                
                // Delete module
                Route::delete('/', [ModuleController::class, 'destroy'])->name('destroy');
                
                // Module-specific actions
                Route::patch('/toggle-active', [ModuleController::class, 'toggleActive'])->name('toggle-active');
                Route::post('/duplicate', [ModuleController::class, 'duplicate'])->name('duplicate');
            });
        });
    });
    
    // ==================== COURSE CATEGORY ROUTES ====================
    Route::prefix('course-categories')->name('course-categories.')->group(function () {
        // Category index
        Route::get('/', [CourseController::class, 'categories'])->name('index');
        
        // Store new category
        Route::post('/', [CourseController::class, 'storeCategory'])->name('store');
        
        // Category parameter routes
        Route::prefix('{category}')->whereNumber('category')->group(function () {
            // Update category
            Route::put('/', [CourseController::class, 'updateCategory'])->name('update');
            
            // Delete category
            Route::delete('/', [CourseController::class, 'destroyCategory'])->name('destroy');
        });
    });
});