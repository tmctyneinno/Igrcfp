<?php

namespace Tests\Feature\Api\V1;

use App\Models\Course;
use App\Services\Api\V1\Course\CourseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class CourseCatalogApiTest extends TestCase
{
    public function test_courses_index_returns_standardized_envelope_with_pagination(): void
    {
        $items = new Collection([
            Course::make([
                'id' => 1,
                'title' => 'AML Fundamentals',
                'slug' => 'aml-fundamentals',
                'short_title' => 'AML',
                'short_description' => 'Intro to AML',
                'full_description' => 'Detailed course content',
                'status' => 'published',
                'level' => 'beginner',
                'format' => 'self_paced',
                'price' => 0,
                'discount_price' => null,
                'image_url' => 'https://example.test/image.jpg',
                'banner_image_url' => 'https://example.test/banner.jpg',
                'is_featured' => true,
                'is_popular' => true,
                'duration' => 120,
                'total_hours' => 2,
                'estimated_learning_time_minutes' => 120,
                'pass_threshold' => 70,
                'modules_count' => 4,
                'lessons_count' => 18,
                'enrollments_count' => 200,
                'completed_enrollments_count' => 140,
                'avg_progress_percentage' => 82.4,
                'recent_enrollments_count' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
        ]);

        $paginator = new LengthAwarePaginator($items, 1, 15, 1, [
            'path' => '/api/v1/courses',
            'pageName' => 'page',
        ]);

        $serviceMock = Mockery::mock(CourseService::class);
        $serviceMock->shouldReceive('getCatalog')->once()->andReturn($paginator);
        $this->app->instance(CourseService::class, $serviceMock);

        $response = $this->getJson('/api/v1/courses?per_page=15&page=1');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Courses fetched successfully')
            ->assertJsonPath('data.0.slug', 'aml-fundamentals')
            ->assertJsonPath('meta.pagination.current_page', 1)
            ->assertJsonPath('meta.pagination.per_page', 15)
            ->assertJsonPath('meta.pagination.total', 1);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
