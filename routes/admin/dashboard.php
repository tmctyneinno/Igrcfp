<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\NewsController;
use Illuminate\Support\Facades\Route;

// Admin Dashboard Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard'); 
    
    // Dashboard Statistics
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats'])
        ->name('dashboard.stats');
     
    // Recent Activities
    Route::get('/dashboard/activities', [AdminDashboardController::class, 'activities'])->name('dashboard.activities');
    // Quick Actions
    Route::post('/dashboard/quick-action', [AdminDashboardController::class, 'quickAction']) ->name('dashboard.quick-action');

    // Event Management
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{event}', [EventController::class, 'show'])->name('show');  // Changed from {id}
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');  // Changed from {id}
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [EventController::class, 'bulkAction'])->name('bulk-action');
        Route::patch('/{event}/toggle-featured', [EventController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::patch('/{event}/toggle-status', [EventController::class, 'toggleStatus'])->name('toggle-status');
    });
    // Blog Management
    Route::prefix('blogs')->name('blogs.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/', [BlogController::class, 'store'])->name('store');
        Route::get('/{blog}', [BlogController::class, 'show'])->name('show');
        Route::get('/{blog}/edit', [BlogController::class, 'edit'])->name('edit');
        Route::put('/{blog}', [BlogController::class, 'update'])->name('update');
        Route::delete('/{blog}', [BlogController::class, 'destroy'])->name('destroy');
        Route::patch('/{blog}/toggle-status', [BlogController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [BlogController::class, 'bulkAction'])->name('bulk-action');
    });
     // News Management
    Route::prefix('news')->name('articles.')->group(function () {
        Route::get('/', [NewsController::class, 'index'])->name('index');
        Route::get('/create', [NewsController::class, 'create'])->name('create');
        Route::post('/', [NewsController::class, 'store'])->name('store');
        Route::get('/{article}', [NewsController::class, 'show'])->name('show');
        Route::get('/{article}/edit', [NewsController::class, 'edit'])->name('edit');
        Route::put('/{article}/update', [NewsController::class, 'update'])->name('update');
        Route::put('/{article}', [NewsController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{article}', [NewsController::class, 'destroy'])->name('destroy');
        Route::patch('/{article}/toggle-status', [NewsController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [NewsController::class, 'bulkAction'])->name('bulk-action');
        Route::patch('/{event}/toggle-featured', [NewsController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/categories', [NewsController::class, 'storeCategory'])->name('articleCategories.store');
    });

    // Enrollments Routes
    Route::prefix('enrollments')->name('enrollments.')->group(function () {
        Route::get('/', [EnrollmentController::class, 'index'])->name('index');
        Route::get('/pending', [EnrollmentController::class, 'pending'])->name('pending');
        Route::get('/completed', [EnrollmentController::class, 'completed'])->name('completed');
        Route::get('/cancelled', [EnrollmentController::class, 'cancelled'])->name('cancelled');
        Route::get('/{enrollment}', [EnrollmentController::class, 'show'])->name('show');
        Route::put('/{enrollment}/status', [EnrollmentController::class, 'updateStatus'])->name('status');
        Route::get('/export', [EnrollmentController::class, 'export'])->name('export'); 
        Route::patch('/{enrollment}/status', [EnrollmentController::class, 'updateStatus'])->name('update-status');
        Route::post('/bulk-action', [EnrollmentController::class, 'bulkAction'])->name('bulk-action');
        Route::delete('/{enrollment}', [EnrollmentController::class, 'destroy'])->name('destroy');
    });

     // Transactions Routes
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/pending', [TransactionController::class, 'pending'])->name('pending');
        Route::get('/completed', [TransactionController::class, 'completed'])->name('completed');
        Route::get('/failed', [TransactionController::class, 'failed'])->name('failed');
        Route::get('/refunded', [TransactionController::class, 'refunded'])->name('refunded');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
        Route::put('/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('status');
    });

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
    });

    // Lesson routes (nested under modules)
    Route::prefix('admin/courses/{course}/modules/{module}/lessons')->name('courses.modules.lessons.')->group(function () {
        Route::get('/', [LessonController::class, 'index'])->name('index');
        Route::get('/create', [LessonController::class, 'create'])->name('create');
        Route::post('/', [LessonController::class, 'store'])->name('store');
        Route::get('/{lesson}/edit', [LessonController::class, 'edit'])->name('edit');
        Route::put('/{lesson}', [LessonController::class, 'update'])->name('update');
        Route::delete('/{lesson}', [LessonController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [LessonController::class, 'reorder'])->name('reorder');
    });
  
});