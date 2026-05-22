<?php

namespace App\Http\Resources\Api\V1\Course;

use App\Http\Resources\Api\V1\BaseApiResource;
use Illuminate\Http\Request;

class CourseResource extends BaseApiResource
{
    public function toArray(Request $request): array
    {
        $enrollmentsCount = (int) ($this->enrollments_count ?? 0);
        $completedEnrollmentsCount = (int) ($this->completed_enrollments_count ?? 0);
        $recentEnrollmentsCount = (int) ($this->recent_enrollments_count ?? 0);
        $avgProgress = round((float) ($this->avg_progress_percentage ?? 0), 2);
        $completionRate = $enrollmentsCount > 0 ? round(($completedEnrollmentsCount / $enrollmentsCount) * 100, 2) : 0.0;
        $popularityScore = round(($enrollmentsCount * 2) + ($recentEnrollmentsCount * 1.5), 2);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_title' => $this->short_title,
            'short_description' => $this->short_description,
            'full_description' => $this->full_description,
            'status' => $this->status,
            'level' => $this->level,
            'format' => $this->format,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'thumbnail_url' => $this->image_url,
            'banner_url' => $this->banner_image_url,
            'is_featured' => (bool) $this->is_featured,
            'is_popular' => (bool) $this->is_popular,
            'duration_minutes' => (int) ($this->duration ?? 0),
            'total_hours' => (int) ($this->total_hours ?? 0),
            'estimated_learning_time_minutes' => (int) ($this->estimated_learning_time_minutes ?? 0),
            'pass_threshold' => $this->pass_threshold ?? null,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ]),
            'instructors' => $this->relationLoaded('instructors')
                ? $this->instructors->map(fn ($instructor) => [
                    'id' => $instructor->id,
                    'name' => $instructor->name,
                    'email' => $instructor->email,
                    'profile_picture_url' => $instructor->profile_picture_url,
                ])->values()
                : [],
            'modules_count' => (int) ($this->modules_count ?? 0),
            'lessons_count' => (int) ($this->lessons_count ?? 0),
            'enrollments_count' => $enrollmentsCount,
            'completed_enrollments_count' => $completedEnrollmentsCount,
            'avg_progress_percentage' => $avgProgress,
            'completion_statistics' => [
                'completion_rate_percentage' => $completionRate,
                'completed_enrollments_count' => $completedEnrollmentsCount,
                'avg_progress_percentage' => $avgProgress,
            ],
            'popularity_score' => $popularityScore,
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
