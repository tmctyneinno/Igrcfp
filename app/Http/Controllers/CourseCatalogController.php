<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseCatalogController extends Controller
{ 
    public function index(Request $request)
    {
       
        
        return Inertia::render('CourseCatalog/Index');
    }
}
