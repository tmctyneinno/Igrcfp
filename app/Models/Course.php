<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    use SoftDeletes;
 
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'short_title',
        'short_description',
        'full_description',
        'image',
        'banner_image',
        'video_type',
        'video',
        'video_url',
        'level',
        'format',
        'duration',
        'total_modules',
        'total_hours',
        'certification_name',
        'certifying_body',
        'price',
        'discount_price',
        'target_audience',
        'learning_outcomes',
        'prerequisites',
        'career_pathways',
        'assessment_structure',
        'code_of_conduct',
        'programme_overview',
        'programme_architecture',
        'meta_description',
        'meta_keywords',
        'status',
        'is_featured',
        'is_popular',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'total_modules' => 'integer',
        'total_hours' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    /**
     * Relationships
     */
    public function materials()
    {
        return $this->hasMany(CourseMaterial::class)->orderBy('sort_order');
    }

    /**
     * Modules relationship - using CourseModule model
     */
    public function modules()
    {
        return $this->hasMany(CourseModule::class)->orderBy('module_number');
    }

    /**
     * Relationship: Course has many Lessons through CourseModules
     */
    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, CourseModule::class, 'course_id', 'module_id');
    }

    /**
     * Get lessons count attribute
     */
    public function getLessonsCountAttribute()
    {
        return $this->lessons()->count();
    }

    /**
     * Calculate total lessons count from all modules
     */
    public function getTotalLessonsAttribute()
    {
        return $this->modules()->withCount('lessons')->get()->sum('lessons_count');
    }

    /**
     * Relationship: Course has many Enrollments
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Relationship: Course belongs to many Users (instructors)
     */
    public function instructors()
    {
        return $this->belongsToMany(User::class, $this->resolveInstructorPivotTable(), 'course_id', 'user_id');
    }

    /**
     * Relationship: Course belongs to many Users (enrolled students)
     */
    public function enrolledUsers()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot(['progress', 'completed_at', 'status'])
            ->withTimestamps();
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeVisibleForCatalog(Builder $query): Builder
    {
        return $query->whereIn('status', ['published', 'active']);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopularFlag($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeFilterCategory(Builder $query, int|string|null $categoryId): Builder
    {
        if ($categoryId === null || $categoryId === '') {
            return $query;
        }

        return $query->where('category_id', $categoryId);
    }

    public function scopeFilterLevel(Builder $query, ?string $level): Builder
    {
        return $level ? $query->where('level', $level) : $query;
    }

    public function scopeFilterFeatured(Builder $query, ?bool $isFeatured): Builder
    {
        return $isFeatured === null ? $query : $query->where('is_featured', $isFeatured);
    }

    public function scopeFilterPopular(Builder $query, ?bool $isPopular): Builder
    {
        return $isPopular === null ? $query : $query->where('is_popular', $isPopular);
    }

    public function scopeFilterInstructor(Builder $query, int|string|null $instructorId): Builder
    {
        if ($instructorId === null || $instructorId === '') {
            return $query;
        }

        if (! self::hasInstructorPivotTable()) {
            return $query;
        }

        return $query->whereHas('instructors', fn (Builder $builder) => $builder->where('users.id', $instructorId));
    }

    public function scopeFilterPriceType(Builder $query, ?string $priceType): Builder
    {
        if ($priceType === null || $priceType === '') {
            return $query;
        }

        return match ($priceType) {
            'free' => $query->where(function (Builder $builder): void {
                $builder->whereNull('price')->orWhere('price', '<=', 0);
            }),
            'paid' => $query->where('price', '>', 0),
            default => $query,
        };
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if ($search === null || trim($search) === '') {
            return $query;
        }

        $search = trim($search);

        return $query->where(function (Builder $builder) use ($search): void {
            $builder->where('title', 'like', "%{$search}%")
                ->orWhere('short_title', 'like', "%{$search}%")
                ->orWhere('short_description', 'like', "%{$search}%")
                ->orWhere('full_description', 'like', "%{$search}%");
        });
    }
    
    public function scopeWithCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * Popular courses scope
     */
    public function scopePopular($query)
    {
        return $query->published()
            ->withCount(['enrollments'])
            ->orderByRaw('(
                (enrollments_count * 2) + 
                (SELECT COUNT(*) FROM enrollments WHERE course_id = courses.id AND created_at >= ?) * 1.5
            ) DESC', [now()->subDays(30)]);
    }

    /**
     * Calculate popularity score
     */
    public function calculatePopularityScore()
    {
        $enrollmentWeight = 2;
        $recentEnrollmentWeight = 1.5;
        
        // Get enrollments from last 30 days
        $recentEnrollments = $this->enrollments()
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
        
        return ($this->enrollments()->count() * $enrollmentWeight) 
            + ($recentEnrollments * $recentEnrollmentWeight);
    }

    /**
     * Get enrollments count attribute
     */
    public function getEnrollmentsCountAttribute()
    {
        return $this->enrollments()->count();
    }

    /**
     * Attributes
     */
    public function getTargetAudienceListAttribute()
    {
        return $this->target_audience ? implode("\n", $this->target_audience) : '';
    }

    public function getLearningOutcomesListAttribute()
    {
        if (!$this->learning_outcomes) return '';
        $lines = explode("\n", $this->learning_outcomes);
        return array_filter($lines, fn($line) => trim($line));
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->price > 0 && $this->discount_price > 0) {
            return round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getEstimatedLearningTimeMinutesAttribute(): int
    {
        if (! empty($this->duration) && is_numeric($this->duration)) {
            return (int) $this->duration;
        }

        if (! empty($this->total_hours) && is_numeric($this->total_hours)) {
            return (int) $this->total_hours * 60;
        }

        return 0;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ? Storage::url($this->banner_image) : null;
    }

    /**
     * Get video embed URL
     */
    public function getVideoEmbedUrlAttribute()
    {
        if ($this->video_type === 'youtube' && $this->video_url) {
            $videoId = $this->extractYouTubeId($this->video_url);
            return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
        }

        if ($this->video_type === 'vimeo' && $this->video_url) {
            $videoId = $this->extractVimeoId($this->video_url);
            return $videoId ? "https://player.vimeo.com/video/{$videoId}" : null;
        }

        return null;
    }

    private function extractYouTubeId($url)
    {
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&?\/]+)/',
            '/youtube\.com\/.*[?&]v=([^&]+)/',
            '/youtu\.be\/([^?]+)/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }

    private function extractVimeoId($url)
    {
        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    public function hasVideo(): bool
    {
        return !empty($this->video_type) && !empty($this->video_url);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class);
    } 

    /**
 * Assessment relationships for Course model
 */
public function assessments()
{
    return $this->hasMany(Assessment::class);
}

public function quizzes()
{
    return $this->hasMany(Assessment::class)->where('assessment_level', 'quiz');
}

public function moduleAssessments()
{
    return $this->hasMany(Assessment::class)->where('assessment_level', 'module_assessment');
}

public function finalExam()
{
    return $this->hasOne(Assessment::class)->where('assessment_level', 'final_exam');
}

public function diplomaAssessment()
{
    return $this->hasOne(Assessment::class)->where('assessment_level', 'diploma');
}

    public static function hasInstructorPivotTable(): bool
    {
        return Schema::hasTable('course_instructor') || Schema::hasTable('course_instructors');
    }

    private function resolveInstructorPivotTable(): string
    {
        if (Schema::hasTable('course_instructor')) {
            return 'course_instructor';
        }

        if (Schema::hasTable('course_instructors')) {
            return 'course_instructors';
        }

        return 'course_instructor';
    }
   
}
