<?php
// app/Models/LessonUser.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonUser extends Pivot
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lesson_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'lesson_id',
        'enrollment_id',
        'completed',
        'completed_at',
        'time_spent',
        'auto_completed',
        'scroll_progress',
        'attempts',
        'last_viewed_at',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
        'metadata' => 'array',
        'attempts' => 'integer',
        'time_spent' => 'integer'
    ];

    /**
     * Get the user that owns the lesson completion.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson that was completed.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the enrollment associated with this completion.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Scope a query to only include completed lessons.
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    /**
     * Scope a query to only include incomplete lessons.
     */
    public function scopeIncomplete($query)
    {
        return $query->where('completed', false);
    }

    /**
     * Mark the lesson as completed.
     */
    public function markAsCompleted(int $timeSpent = null): self
    {
        $this->completed = true;
        $this->completed_at = now();
        
        if ($timeSpent) {
            $this->time_spent = $timeSpent;
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * Mark the lesson as incomplete.
     */
    public function markAsIncomplete(): self
    {
        $this->completed = false;
        $this->completed_at = null;
        $this->save();
        
        return $this;
    }
}