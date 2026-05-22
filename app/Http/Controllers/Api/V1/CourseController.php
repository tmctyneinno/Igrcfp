<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\CourseIndexRequest;
use App\Http\Resources\Api\V1\Course\CourseCollection;
use App\Http\Resources\Api\V1\Course\CourseResource;
use App\Models\Course;
use App\Services\Api\V1\Course\CourseService;

class CourseController extends ApiController
{
    public function __construct(private readonly CourseService $courseService)
    {
    }

    public function index(CourseIndexRequest $request)
    {
        $this->authorize('viewAny', Course::class);

        $paginator = $this->courseService->getCatalog($request->validated());
        $collection = CourseCollection::make($paginator);

        return $this->paginatedResponse($paginator, $collection->collection, 'Courses fetched successfully');
    }

    public function featured(CourseIndexRequest $request)
    {
        $this->authorize('viewAny', Course::class);

        $paginator = $this->courseService->getFeatured($request->validated());
        $collection = CourseCollection::make($paginator);

        return $this->paginatedResponse($paginator, $collection->collection, 'Featured courses fetched successfully');
    }

    public function popular(CourseIndexRequest $request)
    {
        $this->authorize('viewAny', Course::class);

        $paginator = $this->courseService->getPopular($request->validated());
        $collection = CourseCollection::make($paginator);

        return $this->paginatedResponse($paginator, $collection->collection, 'Popular courses fetched successfully');
    }

    public function show(Course $course)
    {
        $this->authorize('view', $course);

        $item = $this->courseService->getBySlug($course->slug);

        return $this->successResponse(new CourseResource($item), 'Course fetched successfully');
    }
}
