<?php
// app/Http/Controllers/Admin/AssessmentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\CourseModule; 
use App\Models\AssessmentQuestion;
use App\Models\AssessmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProjectAssessmentController extends Controller
{
    
   

    public function index()
    {
        $courses = Course::where('status', 'published')->orderBy('title')->get();
        $modules = CourseModule::orderBy('module_number')->get();
        $type = 'module_assessment';
        
        return view('admin.courses.assessments.project.create', compact('courses', 'modules', 'type'));
    }


  


    }