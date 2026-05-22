<?php

namespace App\Repositories\Api\V1\Course;

use App\Models\Course;
use App\Repositories\Api\V1\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class CourseRepository extends BaseRepository
{
    private const CACHE_TTL_SECONDS = 300;

    public function paginateCatalog(array $filters): LengthAwarePaginator
    {
        $perPage = (int) ($filters['per_page'] ?? 15);
        $page = (int) ($filters['page'] ?? 1);

        $cacheKey = $this->cacheKey('catalog', $filters, $page);

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($filters, $perPage, $page) {
            return $this->baseQuery()
                ->visibleForCatalog()
                ->search($filters['search'] ?? null)
                ->filterCategory($filters['category_id'] ?? null)
                ->filterLevel($filters['level'] ?? null)
                ->filterFeatured($this->nullableBoolean($filters['featured'] ?? null))
                ->filterPopular($this->nullableBoolean($filters['popular'] ?? null))
                ->filterInstructor($filters['instructor_id'] ?? null)
                ->filterPriceType($filters['price'] ?? null)
                ->tap(fn (Builder $builder) => $this->applySort($builder, $filters['sort'] ?? null))
                ->paginate($perPage, ['*'], 'page', $page);
        });
    }

    public function paginateFeatured(array $filters): LengthAwarePaginator
    {
        $filters['featured'] = true;
        $filters['sort'] = $filters['sort'] ?? 'featured';

        return $this->paginateCatalog($filters);
    }

    public function paginatePopular(array $filters): LengthAwarePaginator
    {
        $filters['popular'] = true;
        $filters['sort'] = $filters['sort'] ?? 'popular';

        return $this->paginateCatalog($filters);
    }

    public function findBySlug(string $slug): Course
    {
        $cacheKey = $this->cacheKey('show', ['slug' => $slug], 1);

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($slug) {
            return $this->baseQuery()
                ->visibleForCatalog()
                ->where('slug', $slug)
                ->firstOrFail();
        });
    }

    private function baseQuery(): Builder
    {
        $query = Course::query()
            ->select([
                'courses.id',
                'courses.category_id',
                'courses.title',
                'courses.slug',
                'courses.short_title',
                'courses.short_description',
                'courses.full_description',
                'courses.image',
                'courses.banner_image',
                'courses.level',
                'courses.format',
                'courses.duration',
                'courses.total_hours',
                'courses.price',
                'courses.discount_price',
                'courses.status',
                'courses.is_featured',
                'courses.is_popular',
                'courses.created_at',
                'courses.updated_at',
            ])
            ->with([
                'category:id,name,slug',
            ])
            ->withCount([
                'modules',
                'lessons',
                'enrollments',
                'enrollments as completed_enrollments_count' => fn (Builder $builder) => $builder->where('status', 'completed'),
                'enrollments as recent_enrollments_count' => fn (Builder $builder) => $builder->where('created_at', '>=', now()->subDays(30)),
            ])
            ->withAvg('enrollments as avg_progress_percentage', 'progress');

        if (Course::hasInstructorPivotTable()) {
            $query->with(['instructors:id,name,email,profile_picture']);
        }

        return $query;
    }

    private function applySort(Builder $query, ?string $sort): void
    {
        match ($sort) {
            'oldest' => $query->orderBy('courses.created_at'),
            'title_asc' => $query->orderBy('courses.title'),
            'title_desc' => $query->orderByDesc('courses.title'),
            'popular' => $query->orderByDesc('popularity_score')->orderByDesc('courses.is_popular'),
            'featured' => $query->orderByDesc('courses.is_featured')->orderByDesc('courses.created_at'),
            'price_asc' => $query->orderBy('courses.price'),
            'price_desc' => $query->orderByDesc('courses.price'),
            default => $query->orderByDesc('courses.created_at'),
        };
    }

    private function cacheKey(string $bucket, array $filters, int $page): string
    {
        ksort($filters);

        return sprintf('api:v1:courses:%s:%s:%d', $bucket, sha1(json_encode($filters, JSON_THROW_ON_ERROR)), $page);
    }

    private function nullableBoolean(mixed $value): ?bool
    {
        if ($value === null || $value === '') {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
