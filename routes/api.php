<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\AssessmentController;
use App\Http\Controllers\Api\ApplicantProgressController;

Route::prefix('v1')->group(function () {

    // --- PUBLIC ENDPOINT (No Authentication Required) ---
    // Anyone can access this list without a token or login
    Route::get('/courses', [CourseController::class, 'index']); 
 
    // --- PROTECTED ENDPOINTS (Requires Authentication) ---
    Route::middleware(['auth:sanctum'])->group(function () {
        
        // Detailed course view (might contain sensitive info or progress data)
        Route::get('/courses/{course}', [CourseController::class, 'show']);
        
        // Applicant specific data
        Route::get('/my-progress', [ApplicantProgressController::class, 'myProgress']);

        // --- EXTERNAL SYSTEM ENDPOINTS (Token-Based) ---
        Route::get('/external/courses', [CourseController::class, 'index']);
        Route::get('/external/courses/{course}', [CourseController::class, 'show']);
        Route::get('/external/courses/{course}/modules', [ModuleController::class, 'index']);
        Route::get('/external/modules/{module}', [ModuleController::class, 'show']);
        Route::get('/external/quizzes/{quiz}', [QuizController::class, 'show']);
        Route::get('/external/assessments/{assessment}', [AssessmentController::class, 'show']);
    });
});