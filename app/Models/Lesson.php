<?php
// app/Models/Lesson.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'module_id',
        'title',
        'slug',
        'short_description',
        'content',
        'video_url',
        'video_embed_code',
        'duration',
        'sort_order',
        'is_free',
        'is_published',
        'attachments',
        'resources',
    ];

    protected $casts = [
        'duration' => 'integer',
        'sort_order' => 'integer',
        'is_free' => 'boolean',
        'is_published' => 'boolean',
        'attachments' => 'array',
        'resources' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title) . '-' . uniqid();
            }
        });
    }

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Scope to order lessons by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Scope to get only published lessons
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return 'N/A';
        
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }
        return $minutes . ' min';
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('completed', 'completed_at', 'enrollment_id')
            ->withTimestamps();
    }

    public function completionsForEnrollment(Enrollment $enrollment)
    {
        return $this->users()
            ->wherePivot('enrollment_id', $enrollment->id)
            ->get();
    }
    
}