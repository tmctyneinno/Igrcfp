<?php
// app/Models/AssessmentAttempt.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    protected $table = 'assessment_attempts';

    protected $fillable = [
        'assessment_id',
        'user_id',
        'submission_id',
        'attempt_number',
        'started_at',
        'completed_at',
        'status',
        'answers_snapshot',
        'last_question_index',
        'flagged_questions',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'answers_snapshot' => 'array',
        'flagged_questions' => 'array',
    ];

    /**
     * Relationships
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function submission()
    {
        return $this->belongsTo(AssessmentSubmission::class, 'submission_id');
    }

    /**
     * Check if attempt is expired
     */
    public function isExpired()
    {
        if (!$this->assessment->is_timed || !$this->assessment->duration) {
            return false;
        }
        
        $expiryTime = $this->started_at->addMinutes($this->assessment->duration);
        return now() > $expiryTime;
    }

    /**
     * Get time remaining in seconds
     */
    public function getTimeRemainingAttribute()
    {
        if (!$this->assessment->is_timed || !$this->assessment->duration) {
            return null;
        }
        
        $expiryTime = $this->started_at->addMinutes($this->assessment->duration);
        return now()->diffInSeconds($expiryTime, false);
    }

    /**
     * Get formatted time remaining
     */
    public function getFormattedTimeRemainingAttribute()
    {
        $seconds = $this->time_remaining;
        
        if ($seconds === null) return null;
        if ($seconds <= 0) return 'Expired';
        
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        if ($hours > 0) {
            return "{$hours}:{$minutes}:{$secs}";
        }
        return "{$minutes}:{$secs}";
    }

    /**
     * Save current progress
     */
    public function saveProgress($answers, $lastQuestionIndex, $flaggedQuestions = [])
    {
        $this->answers_snapshot = $answers;
        $this->last_question_index = $lastQuestionIndex;
        $this->flagged_questions = $flaggedQuestions;
        $this->save();
    }

    /**
     * Complete the attempt and create submission
     */
    public function complete($answers = null)
    {
        // Create submission
        $submission = AssessmentSubmission::create([
            'assessment_id' => $this->assessment_id,
            'user_id' => $this->user_id,
            'enrollment_id' => Enrollment::where('user_id', $this->user_id)
                ->where('course_id', $this->assessment->course_id)
                ->first()?->id,
            'started_at' => $this->started_at,
            'submitted_at' => now(),
            'status' => 'submitted',
            'answers' => $answers ?? $this->answers_snapshot,
            'attempt_number' => $this->attempt_number,
            'time_spent' => now()->diffInSeconds($this->started_at),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        // Calculate auto-graded score for non-manual assessments
        if (!$this->assessment->needs_manual_marking) {
            $submission->calculateScore()->save();
            
            // If all questions auto-graded, mark as completed
            if ($submission->question_responses && 
                !in_array(null, array_column($submission->question_responses, 'points_earned'))) {
                $submission->status = 'graded';
                $submission->save();
                
                // Update assessment statistics
                $this->assessment->calculateStatistics();
            }
        }
        
        // Update attempt
        $this->submission_id = $submission->id;
        $this->completed_at = now();
        $this->status = 'completed';
        $this->save();
        
        return $submission;
    }

    /**
     * Scope queries
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'in_progress')
            ->whereHas('assessment', function($q) {
                $q->where('is_timed', true);
            })
            ->get()
            ->filter(function($attempt) {
                return $attempt->isExpired();
            });
    }
}