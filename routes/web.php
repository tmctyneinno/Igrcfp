<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\Auth\RegisteredUserController;
// use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\EventController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/welcome-to-igrcfp', [HomeController::class, 'welcomeToIGRCFP'])->name('welcome-to-igrcfp');
Route::get('/our-structure', [HomeController::class, 'OurStructure'])->name('our-structure');
Route::get('/membership', [HomeController::class, 'membership'])->name('membership');
Route::get('/certifications', [HomeController::class, 'certifications'])->name('certifications');

Route::get('/events', [HomeController::class, 'eventsIndex'])->name('events.index');
Route::get('/events/{slug}', [HomeController::class, 'eventShow'])->name('events.show');
Route::get('/events/{slug}/register', [HomeController::class, 'eventRegister'])->name('events.register');
Route::post('/events/{slug}/register', [EventController::class, 'storeEventRegistration'])->name('events.register.store');

Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');


Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Auth routes (login, register, password reset, etc.)
// Auth::routes();
require __DIR__.'/auth.php';
require __DIR__.'/admin/admins.php';


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
