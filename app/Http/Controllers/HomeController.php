<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Foundation\Application;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $courses = Course::with('instructor')->get(); // fetch courses with instructor

        return Inertia::render('Welcome', [
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
            'courses' => $courses,
        ]);
    }
}
