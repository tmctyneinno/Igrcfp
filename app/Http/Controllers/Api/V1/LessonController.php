<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\CourseModule;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends ApiController
{
    public function indexByModule(CourseModule $module, Request $request)
    {
        $lessons = $module->lessons()->ordered()->paginate((int) $request->integer('per_page', 15));
        return $this->paginatedResponse($lessons, $lessons->items());
    }

    public function show(Lesson $lesson)
    {
        return $this->successResponse($lesson);
    }
}
