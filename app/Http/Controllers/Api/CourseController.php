<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    public function index()
    {
        // Get all courses with their related modules loaded
        $courses = Course::with('modules')->get();
        return CourseResource::collection($courses);
    }
 
    public function show(Course $course)
    {
        // Load relationships for detailed view
        $course->load(['modules.quizzes', 'modules.assessments']);
        return new CourseResource($course);
    }
}