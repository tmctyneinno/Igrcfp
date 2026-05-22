<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\Request;

class ModuleController extends ApiController
{
    public function indexByCourse(Course $course, Request $request)
    {
        $modules = $course->modules()->withCount('lessons')->paginate((int) $request->integer('per_page', 15));
        return $this->paginatedResponse($modules, $modules->items());
    }

    public function show(CourseModule $module)
    {
        return $this->successResponse($module->load('lessons'));
    }
}
