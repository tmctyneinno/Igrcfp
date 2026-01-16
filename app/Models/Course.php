<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'code',
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
        'target_audience' => 'array',
        'total_modules' => 'integer',
        'total_hours' => 'integer',
    ];

    /**
     * Relationships
     */
    public function modules()
    {
        return $this->hasMany(CourseModule::class)->orderBy('module_number');
    }

    public function materials()
    {
        return $this->hasMany(CourseMaterial::class)->orderBy('sort_order');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeWithCode($query, $code)
    {
        return $query->where('code', $code);
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
}
}
