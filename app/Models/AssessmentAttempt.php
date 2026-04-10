<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{ 
    protected $fillable = [
        'user_id',
        'assessment_id',
        'enrollment_id',
        'status',
        'started_at',
        'completed_at',
        'last_activity_at',
        'answers',
        'score',
        'earned_marks',
        'total_marks',
        'correct_answers',
        'passed',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'answers' => 'array',
        'score' => 'integer',
        'earned_marks' => 'integer',
        'total_marks' => 'integer',
        'correct_answers' => 'integer',
        'passed' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Scopes
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopePassed($query)
    {
        return $query->where('passed', true);
    }

    /**
     * Check if attempt is expired
     */
    public function isExpired($timeLimitMinutes)
    {
        if (!$this->started_at) {
            return false;
        }
        
        $expiresAt = $this->started_at->addMinutes($timeLimitMinutes);
        return now()->greaterThan($expiresAt);
    }

    /**
     * Get time remaining in seconds
     */
    public function getTimeRemainingAttribute($timeLimitMinutes)
    {
        if (!$this->started_at) {
            return $timeLimitMinutes * 60;
        }
        
        $expiresAt = $this->started_at->addMinutes($timeLimitMinutes);
        $remaining = now()->diffInSeconds($expiresAt, false);
        
        return max(0, $remaining);
    }

    /**
     * Calculate percentage score
     */
    public function getPercentageScoreAttribute()
    {
        if ($this->total_marks <= 0) {
            return 0;
        }
        
        return round(($this->earned_marks / $this->total_marks) * 100);
    }
}