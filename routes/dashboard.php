<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LessonCompletionController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AssessmentAttemptController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware(['auth', 'verified'])->group(function () {        
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('dashboard/my-courses', [DashboardController::class, 'myCourse'])->name('dashboard.my-courses');
    Route::get('/memebership', [DashboardController::class, 'memebership'])->name('dashboard.memebership');
    Route::get('/notifications/index', [DashboardController::class, 'notifications'])->name('notifications.index');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');

    Route::get('dashboard/courses/', [DashboardController::class, 'courses'])->name('dashboard.courses.index');
    Route::get('dashboard/courses/most-popular', [DashboardController::class, 'mostPopular'])->name('courses.mostPopular');
    Route::get('dashboard/courses/{slug}', [DashboardController::class, 'courseSlug'])->name('dashboard.courses.show');
    Route::get('/dashboard/category/{slug}', [DashboardController::class, 'byCategory'])->name('dashboard.courses.by-category');
    // Cart routes
    Route::get('/cart/index', [CartController::class, 'index'])->name('dashboard.cart.index');
    // Route::post('/cart/add/{course}', [CartController::class, 'add'])->name('dashboard.cart.add');
    Route::post('/dashboard/cart/add/{course}', [CartController::class, 'add'])->name('dashboard.cart.add'); 
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove'); 
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout routes
    Route::get('dashboard/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('dashboard/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process'); 
    Route::get('dashboard/checkout/success/{enrollment?}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('dashboard/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

    // Stripe payment routes
    Route::post('dashboard/payment/stripe/create-intent', [CheckoutController::class, 'createPaymentIntent'])->name('payment.stripe.intent');
    Route::get('/payment/stripe/success', [CheckoutController::class, 'stripeSuccess'])->name('payment.stripe.success');
    // Route::get('/payment/stripe/success', [CheckoutController::class, 'stripeSuccess'])->name('stripe.success');
    Route::get('dashboard/payment/stripe/cancel', [CheckoutController::class, 'stripeCancel'])->name('payment.stripe.cancel');

    // Certificate routes
    Route::get('dashboard/certificates/generate/{enrollment}', [CertificateController::class, 'generate'])->name('dashboard.certificates.generate');
    Route::get('dashboard/certificates/preview/{enrollment}', [CertificateController::class, 'preview'])->name('dashboard.certificates.preview');
    Route::get('dashboard/certificates/download/{enrollment}', [CertificateController::class, 'download'])->name('dashboard.certificates.download');
    Route::get('dashboard/badge/{enrollment}', [CertificateController::class, 'badge'])->name('dashboard.certificates.badge');
    Route::get('dashboard/verify/{id}', [CertificateController::class, 'verify'])->name('dashboard.certificate.verify');
    Route::get('dashboard/registry', [CertificateController::class, 'registry'])->name('dashboard.certificate.registry');

    // Exam Routes
    // Route::middleware(['auth'])->prefix('exam')->name('exam.')->group(function () {
        Route::get('/verify/{enrollment}', [ExamController::class, 'showVerification'])->name('verify');
        Route::post('/verify-identity/{enrollment}', [ExamController::class, 'verifyIdentity'])->name('verify-identity');
        Route::post('/start/{enrollment}/{exam}', [ExamController::class, 'start'])->name('start');
        Route::get('/continue/{attempt}', [ExamController::class, 'continue'])->name('continue');
        Route::post('/submit/{enrollment}/{exam}', [ExamController::class, 'submit'])->name('submit');
        Route::get('/submit/show', [ExamController::class, 'show'])->name('dashboard.exam.show');
    // });
 
 
    Route::post('dashboard/lessons/{lesson}/complete', [LessonCompletionController::class, 'markComplete'])
    ->name('lessons.complete')
    ->where('lesson', '[0-9]+'); 
    Route::delete('dashboard/lessons/{lesson}/complete', [LessonCompletionController::class, 'markIncomplete'])
        ->name('lessons.incomplete');

});

// In routes/web.php, add these routes:

Route::prefix('assessment')->name('assessment.')->group(function () {
    // Quiz routes
    Route::get('/quiz/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'takeQuiz'])->name('quiz.take');
    Route::get('/quiz/continue/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'continueQuiz'])->name('quiz.continue');
    Route::post('/quiz/submit/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'submitQuiz'])->name('quiz.submit');
    Route::get('/quiz/review/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'reviewQuiz'])->name('quiz.review');
    Route::post('/quiz/save/{attempt}', [AssessmentAttemptController::class, 'saveQuizProgress'])->name('quiz.save');
});


// routes/web.php



Route::prefix('assessment')->name('assessment.')->group(function () {
    
    // ============== QUIZ ROUTES ==============
    Route::get('/quiz/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'takeQuiz'])
        ->name('quiz.take');
    
    Route::get('/quiz/continue/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'continueQuiz'])
        ->name('quiz.continue');
    
    Route::post('/quiz/submit/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'submitQuiz'])
        ->name('quiz.submit');
    
    Route::get('/quiz/review/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'reviewQuiz'])
        ->name('quiz.review');
    
    Route::post('/quiz/save/{attempt}', [AssessmentAttemptController::class, 'saveQuizProgress'])
        ->name('quiz.save');

    // ============== MODULE ASSESSMENT ROUTES ==============
    Route::get('/module/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'takeModuleAssessment'])
        ->name('module.take');
    
    Route::get('/module/continue/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'continueModuleAssessment'])
        ->name('module.continue');
    
    Route::post('/module/submit/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'submitModuleAssessment'])
        ->name('module.submit');
    
    Route::get('/module/review/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'reviewModuleAssessment'])
        ->name('module.review');
    
    Route::post('/module/save/{attempt}', [AssessmentAttemptController::class, 'saveModuleProgress'])
        ->name('module.save');

    // ============== FINAL EXAM ROUTES ==============
    Route::get('/final/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'takeFinalExam'])
        ->name('final.take');
    
    Route::get('/final/continue/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'continueFinalExam'])
        ->name('final.continue');
    
    Route::post('/final/submit/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'submitFinalExam'])
        ->name('final.submit');
    
    Route::get('/final/review/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'reviewFinalExam'])
        ->name('final.review');
    
    Route::post('/final/save/{attempt}', [AssessmentAttemptController::class, 'saveFinalProgress'])
        ->name('final.save');

    // ============== DIPLOMA ASSESSMENT ROUTES ==============
    Route::get('/diploma/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'takeDiplomaAssessment'])
        ->name('diploma.take');
    
    Route::get('/diploma/continue/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'continueDiplomaAssessment'])
        ->name('diploma.continue');
    
    Route::post('/diploma/submit/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'submitDiplomaAssessment'])
        ->name('diploma.submit');
    
    Route::get('/diploma/review/{enrollment}/{assessment}', [AssessmentAttemptController::class, 'reviewDiplomaAssessment'])
        ->name('diploma.review');
    
    Route::post('/diploma/save/{attempt}', [AssessmentAttemptController::class, 'saveDiplomaProgress'])
        ->name('diploma.save');

});

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    
    // ✅ POST ROUTES FIRST
    Route::post('/courses/{course:slug}/quiz/{assessment}/submit', [QuizController::class, 'submit'])
        ->name('quiz.submit');
    
    Route::post('/quiz/{attempt}/save', [QuizController::class, 'saveProgress'])
        ->name('quiz.save');
    
    Route::post('/quiz/{attempt}/save-progress', [QuizController::class, 'saveProgress'])
        ->name('quiz.save-progress');
    
    // ✅ ALL SPECIFIC GET ROUTES - MUST COME BEFORE THE GENERAL ONE
    Route::get('/courses/{course:slug}/quiz/results', [QuizController::class, 'results'])
        ->name('quiz.results.all'); 

    Route::get('/courses/{course:slug}/quiz/{assessment}/results', [QuizController::class, 'results'])
        ->name('quiz.results');
    
    Route::get('/courses/{course:slug}/quiz/{assessment}/continue', [QuizController::class, 'continue'])
        ->name('quiz.continue');
    
    // ✅ GENERAL GET ROUTE - MUST BE LAST
    Route::get('/courses/{course:slug}/quiz/{assessment}', [QuizController::class, 'take'])
        ->name('quiz.take');
 
    Route::get('/courses/{course:slug}/project-assessment', [QuizController::class, 'projectAssessment'])
    ->name('quiz.project-assessment');
});