<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\AssessmentController;
use App\Http\Controllers\Api\ApplicantProgressController;

Route::prefix('v1')->group(function () {

    // --- PUBLIC ENDPOINTS ---
    Route::get('/courses', [CourseController::class, 'index']); 
    
    // Make sure this uses :slug since you are passing "enterprise-risk-management"
    Route::get('/courses/{course:slug}', [CourseController::class, 'show']); 
 
 
    // --- PROTECTED ENDPOINTS (Requires Authentication) ---
    Route::middleware(['auth:sanctum'])->group(function () {
        
        // REMOVED the duplicate GET /courses/{course} from here!
        
        // Applicant specific data
        Route::get('/my-progress', [ApplicantProgressController::class, 'myProgress']);

        // --- EXTERNAL SYSTEM ENDPOINTS ---
        Route::get('/external/courses', [CourseController::class, 'index']);
        Route::get('/external/courses/{course:slug}', [CourseController::class, 'show']); // Added :slug here too
        Route::get('/external/courses/{course:slug}/modules', [ModuleController::class, 'index']);
        Route::get('/external/modules/{module:slug}', [ModuleController::class, 'show']);
        Route::get('/external/quizzes/{quiz:slug}', [QuizController::class, 'show']);
        Route::get('/external/assessments/{assessment:slug}', [AssessmentController::class, 'show']);
   
    });
});