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

    /**
     * Relationship: Lesson belongs to a Module
     */
    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    /**
     * Relationship: Lesson belongs to many Users (through lesson_user pivot)
     * This is the SINGLE users relationship - keep only this one
     */ 
    public function users()
    {
        return $this->belongsToMany(User::class, 'lesson_user')
            ->withPivot([
                'enrollment_id',
                'completed',
                'completed_at',
                'time_spent',
                'auto_completed',
                'scroll_progress',
                'attempts',
                'last_viewed_at',
                'metadata'
            ])
            ->withTimestamps();
    }

    /**
     * Relationship: Lesson has many LessonUser records
     */
    public function completions()
    {
        return $this->hasMany(LessonUser::class);
    }

    /**
     * Relationship: Lesson progress (legacy - consider using completions instead)
     */
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

    /**
     * Scope to add completion status for a specific user and enrollment
     */
    public function scopeWithCompletionStatus($query, $userId, $enrollmentId)
    {
        if (!$userId || !$enrollmentId) {
            return $query;
        }
        
        return $query->addSelect([
            'completed' => \DB::table('lesson_user')
                ->select('completed')
                ->whereColumn('lesson_id', 'lessons.id')
                ->where('user_id', $userId)
                ->where('enrollment_id', $enrollmentId)
                ->limit(1)
        ]);
    }

    /**
     * Get formatted duration (e.g., "1h 30m" or "45 min")
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return 'N/A';
        
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0) {
            return $hours . 'h ' . ($minutes > 0 ? $minutes . 'm' : '');
        }
        return $minutes . ' min';
    }

    /**
     * Check if lesson is completed by a specific user for a specific enrollment
     */
    public function isCompletedBy(User $user, Enrollment $enrollment): bool
    {
        return $this->users()
            ->wherePivot('user_id', $user->id)
            ->wherePivot('enrollment_id', $enrollment->id)
            ->wherePivot('completed', true)
            ->exists();
    }

    /**
     * Get completion record for a specific user and enrollment
     */
    public function getCompletionForUser(User $user, Enrollment $enrollment)
    {
        return $this->completions()
            ->where('user_id', $user->id)
            ->where('enrollment_id', $enrollment->id)
            ->first();
    }

    /**
     * Get all completions for a specific enrollment
     */
    public function completionsForEnrollment(Enrollment $enrollment)
    {
        return $this->users()
            ->wherePivot('enrollment_id', $enrollment->id)
            ->get();
    }

    /**
     * Mark lesson as complete for a user
     */
    public function markComplete(User $user, Enrollment $enrollment, array $metadata = []): void
    {
        $pivotData = array_merge([
            'completed' => true,
            'completed_at' => now(),
            'last_viewed_at' => now(),
        ], $metadata);

        $this->users()->syncWithoutDetaching([
            $user->id => array_merge($pivotData, [
                'enrollment_id' => $enrollment->id,
            ])
        ]);
    }

    /**
     * Mark lesson as incomplete for a user
     */
    public function markIncomplete(User $user, Enrollment $enrollment): void
    {
        $this->users()->wherePivot('enrollment_id', $enrollment->id)->detach($user->id);
    }

    /**
     * Get the next lesson in the module
     */
    public function getNextLessonAttribute()
    {
        return self::where('module_id', $this->module_id)
            ->where('sort_order', '>', $this->sort_order)
            ->orderBy('sort_order')
            ->first();
    }

    /**
     * Get the previous lesson in the module
     */
    public function getPreviousLessonAttribute()
    {
        return self::where('module_id', $this->module_id)
            ->where('sort_order', '<', $this->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();
    }

    /**
     * Check if lesson has video content
     */
    public function hasVideo(): bool
    {
        return !empty($this->video_url) || !empty($this->video_embed_code);
    }

    /**
     * Check if lesson has attachments
     */
    public function hasAttachments(): bool
    {
        return !empty($this->attachments) && count($this->attachments) > 0;
    }

    /**
     * Check if lesson has resources
     */
    public function hasResources(): bool
    {
        return !empty($this->resources) && count($this->resources) > 0;
    }
}