<?php

namespace App\Services\Api\V1\Course;

use App\Models\Course;
use App\Repositories\Api\V1\Course\CourseRepository;
use App\Services\Api\V1\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseService extends BaseService
{
    public function __construct(private readonly CourseRepository $courseRepository)
    {
    }

    public function getCatalog(array $filters): LengthAwarePaginator
    {
        return $this->courseRepository->paginateCatalog($filters);
    }

    public function getFeatured(array $filters): LengthAwarePaginator
    {
        return $this->courseRepository->paginateFeatured($filters);
    }

    public function getPopular(array $filters): LengthAwarePaginator
    {
        return $this->courseRepository->paginatePopular($filters);
    }

    public function getBySlug(string $slug): Course
    {
        return $this->courseRepository->findBySlug($slug);
    }
}
