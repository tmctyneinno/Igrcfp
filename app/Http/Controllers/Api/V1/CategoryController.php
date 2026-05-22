<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function index(Request $request)
    {
        $categories = CourseCategory::query()->active()->ordered()->withCount('courses')->paginate((int) $request->integer('per_page', 15));
        return $this->paginatedResponse($categories, $categories->items());
    }

    public function courses(CourseCategory $category, Request $request)
    {
        $courses = $category->courses()->published()->paginate((int) $request->integer('per_page', 15));
        return $this->paginatedResponse($courses, $courses->items());
    }
}
