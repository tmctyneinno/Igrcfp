<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegisterController;
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
Route::get('/contact', [HomeController::class, 'blog'])->name('contact');


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

Route::middleware('guest')->group(function () {
    Route::get('/register', function () { return inertia('Auth/Register');})->name('register');
    Route::get('/login', function () { return inertia('Auth/Login');})->name('login');
    
    Route::post('/register', [LoginController::class, 'register'])->name('register.post');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

require __DIR__.'/admin/admins.php';
