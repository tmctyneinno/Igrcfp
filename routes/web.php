<?php

use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\CourseCatalogController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgrammesController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\PublicCertificateController;
use App\Http\Controllers\Admin\EventController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/welcome-to-igrcfp', [HomeController::class, 'welcomeToIGRCFP'])->name('welcome-to-igrcfp');
Route::get('/our-structure', [HomeController::class, 'OurStructure'])->name('our-structure');
Route::get('/why-igrcfp', [HomeController::class, 'whyIgrcfp'])->name('why-igrcfp');
Route::get('/membership', [HomeController::class, 'membership'])->name('membership');
Route::get('/certifications', [HomeController::class, 'certifications'])->name('certifications');
Route::get('/certifications/overview', [HomeController::class, 'certificationsOverview'])->name('certifications.overview');
Route::get('/certifications/pathway', [HomeController::class, 'certificationsPathway'])->name('certifications.pathway');
Route::get('/cgfcs/specialist', [HomeController::class, 'cgfcsSpecialist'])->name('cgfcs.specialist');

Route::get('/events', [HomeController::class, 'eventsIndex'])->name('events.index');
Route::get('/events/{slug}', [HomeController::class, 'eventShow'])->name('events.show');
Route::get('/events/{slug}/register', [HomeController::class, 'eventRegister'])->name('events.register');
Route::post('/events/{slug}/register', [EventController::class, 'storeEventRegistration'])->name('events.register.store');

Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [HomeController::class, 'blogShow'])->name('blog.show');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-conditions', [HomeController::class, 'termsCondition'])->name('terms.condition');
Route::get('/privacy-preference-center', [HomeController::class, 'privacyPreferenceCenter'])->name('privacy.preference.center');

Route::get('/training-calendar', [HomeController::class, 'trainingCalendar'])->name('training.calendar');

Route::prefix('news')->name('news.')->group(function () {
    Route::get('/', [NewsController::class, 'news'])->name('index');
    Route::get('/category/{slug}', [NewsController::class, 'category'])->name('category');
    Route::get('/{slug}/show', [NewsController::class, 'showNews'])->name('show');
}); 

 
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/course-catalogue', [CourseCatalogController::class, 'index'])->name('course.catalog.index');
Route::redirect('/course-catelog', '/course-catalog');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show'); 
Route::get('/courses/category/{slug}', [CourseController::class, 'byCategory'])->name('courses.by-category');
// Course enrollment routes
Route::get('/courses/{course:slug}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
Route::post('/courses/{course:slug}/enroll', [CourseController::class, 'processEnrollment'])->name('courses.enroll.process');

 

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Auth routes (login, register, password reset, etc.)
// Auth::routes();
require __DIR__.'/auth.php';
require __DIR__.'/admin/admins.php';
require __DIR__.'/dashboard.php';


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('programmes')->group(function () { 
    Route::get('/', [ProgrammesController::class, 'index'])->name('programmes');
    Route::get('/grc', [ProgrammesController::class, 'grc'])->name('programmes.grc');
    Route::get('/financial-crime', [ProgrammesController::class, 'financialCrime'])->name('programmes.financial-crime');
    Route::get('/crypto', [ProgrammesController::class, 'crypto'])->name('programmes.crypto');
    Route::get('/cybersecurity', [ProgrammesController::class, 'cybersecurity'])->name('programmes.cybersecurity');
    Route::get('/ai', [ProgrammesController::class, 'ai'])->name('programmes.ai');
}); 
Route::get('/igrcfp-certificates-courses', [HomeController::class, 'showIgrcfpProgramme'])
    ->defaults('programme', 'certificates')
    ->name('igrcfp.certificates.index');
Route::get('/igrcfp-diploma', [HomeController::class, 'showIgrcfpProgramme'])
    ->defaults('programme', 'diploma')
    ->name('igrcfp.diploma.index');
Route::get('/igrcfp-advanced-diploma', [HomeController::class, 'showIgrcfpProgramme'])
    ->defaults('programme', 'advanced-diploma')
    ->name('igrcfp.advanced-diploma.index');
Route::get('/igrcfp-certified-grc-financial-crime-specialist', [HomeController::class, 'showIgrcfpProgramme'])
    ->defaults('programme', 'certified-grc-financial-crime-specialist')
    ->name('igrcfp.certified-grc-financial-crime-specialist.index');
Route::get('/igrcfp-postgraduate-diploma', [HomeController::class, 'showIgrcfpProgramme'])
    ->defaults('programme', 'postgraduate-diploma')
    ->name('igrcfp.postgraduate-diploma.index');
Route::get('/igrcfp-fellowship', [HomeController::class, 'showIgrcfpProgramme'])
    ->defaults('programme', 'fellowship')
    ->name('igrcfp.fellowship.index');

Route::get('/qualifications-pack', [HomeController::class, 'qualificationsPack'])
    ->name('qualifications.pack');

Route::get('/course-equivalency', [HomeController::class, 'courseEquivalency'])
    ->name('course-equivalency.index');

Route::get('/partnerships', [HomeController::class, 'partnerships'])
    ->name('partnerships.index');
Route::get('/scholarship/{slug}', [HomeController::class, 'showNews'])->name('scholarship.show');
Route::post('/scholarship/apply', [ScholarshipController::class, 'store'])->name('scholarship.apply');
 
Route::prefix('verify-certificate')->name('certificate.verify')->group(function () {
    Route::get('/', [PublicCertificateController::class, 'index'])->name('index');
    Route::post('/check', [PublicCertificateController::class, 'verify'])->name('check');
    Route::get('/{number}', [PublicCertificateController::class, 'verifyByNumber'])->name('show');
});
