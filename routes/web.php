<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/membership', [FrontendController::class, 'membership'])->name('membership');
Route::get('/certification', [FrontendController::class, 'certification'])->name('certification');
Route::get('/about-us', [FrontendController::class, 'aboutus'])->name('about-us');
Route::get('/event', [FrontendController::class, 'event'])->name('event');
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/services', [FrontendController::class, 'services'])->name('services');


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
