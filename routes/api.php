<?php

use App\Http\Controllers\Api\V1\Admin\AnalyticsController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CertificateController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\EnrollmentController;
use App\Http\Controllers\Api\V1\LeaderboardController;
use App\Http\Controllers\Api\V1\LessonController;
use App\Http\Controllers\Api\V1\ModuleController;
use App\Http\Controllers\Api\V1\ProgressController;
use App\Http\Controllers\Api\V1\QuizAttemptController;
use App\Http\Controllers\Api\V1\QuizController;
use App\Http\Controllers\Api\V1\SyncController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/featured', [CourseController::class, 'featured']);
    Route::get('courses/popular', [CourseController::class, 'popular']);
    Route::get('courses/{course:slug}', [CourseController::class, 'show']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}/courses', [CategoryController::class, 'courses']);

    Route::get('certificates/verify/{code}', [CertificateController::class, 'verify'])->name('api.v1.certificates.verify');

    Route::middleware(['api.key', 'external.user', 'throttle:external-api', 'audit.api'])->group(function () {
        Route::get('courses/{course}/modules', [ModuleController::class, 'indexByCourse']);
        Route::get('modules/{module}', [ModuleController::class, 'show']);
        Route::get('modules/{module}/lessons', [LessonController::class, 'indexByModule']);
        Route::get('lessons/{lesson}', [LessonController::class, 'show']);

        Route::get('quizzes/{quiz}', [QuizController::class, 'show']);
        Route::post('quizzes/{quiz}/start', [QuizAttemptController::class, 'start'])->middleware('throttle:quiz-submit');
        Route::post('quizzes/{quiz}/submit', [QuizAttemptController::class, 'submit'])->middleware('throttle:quiz-submit');
        Route::get('quizzes/{quiz}/attempts', [QuizAttemptController::class, 'attempts']);
        Route::get('quizzes/{quiz}/results', [QuizAttemptController::class, 'results']);

        Route::post('courses/{course}/enroll', [EnrollmentController::class, 'store']);
        Route::get('users/{externalUserId}/enrollments', [EnrollmentController::class, 'userEnrollments']);
        Route::get('enrollments/{enrollment}', [EnrollmentController::class, 'show']);

        Route::post('lessons/{lesson}/complete', [ProgressController::class, 'completeLesson']);
        Route::post('lessons/{lesson}/track-time', [ProgressController::class, 'trackLessonTime']);
        Route::post('modules/{module}/complete', [ProgressController::class, 'completeModule']);
        Route::get('courses/{course}/progress', [ProgressController::class, 'courseProgress']);
        Route::get('users/{externalUserId}/progress', [ProgressController::class, 'userProgress']);
        Route::get('courses/{course}/resume', [ProgressController::class, 'resume']);

        Route::get('leaderboard', [LeaderboardController::class, 'global']);
        Route::get('courses/{course}/leaderboard', [LeaderboardController::class, 'course']);
        Route::get('users/{user}/ranking', [LeaderboardController::class, 'userRanking']);

        Route::get('certificates', [CertificateController::class, 'index']);
        Route::get('certificates/{certificate}', [CertificateController::class, 'show']);

        Route::prefix('sync')->middleware(['throttle:sync-api'])->group(function () {
            Route::get('courses', [SyncController::class, 'courses']);
            Route::get('courses/{id}', [SyncController::class, 'course']);
            Route::get('progress/{externalUserId}', [SyncController::class, 'progress']);
            Route::get('leaderboards', [SyncController::class, 'leaderboards']);
            Route::get('certificates/{externalUserId}', [SyncController::class, 'certificates']);
        });

        Route::prefix('admin')->group(function () {
            Route::get('analytics', [AnalyticsController::class, 'index']);
            Route::get('course-statistics', [AnalyticsController::class, 'courseStatistics']);
            Route::get('engagement-metrics', [AnalyticsController::class, 'engagementMetrics']);
            Route::get('dropoff-analysis', [AnalyticsController::class, 'dropoffAnalysis']);
        });
    });
});
