<?php
// app/Models/AssessmentSubmission.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AssessmentSubmission extends Model
{
    protected $table = 'assessment_submissions';
 
    protected $fillable = [
        'assessment_id',
        'user_id',
        'enrollment_id',
        'started_at',
        'submitted_at',
        'status',
        'answers', 
        'question_responses',
        'score',
        'percentage',
        'passed',
        'submission_file_path',
        'submission_file_name',
        'submission_file_size',
        'identity_verification_data',
        'verification_images',
        'ip_address',
        'user_agent',
        'plagiarism_score',
        'plagiarism_report',
        'flagged_for_review',
        'feedback',
        'grader_comments',
        'graded_at',
        'graded_by',
        'time_spent',
        'remaining_time',
        'attempt_number',
        'locked_until', 
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'answers' => 'array',
        'question_responses' => 'array',
        'identity_verification_data' => 'array',
        'verification_images' => 'array',
        'plagiarism_report' => 'array',
        'grader_comments' => 'array',
        'score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'passed' => 'boolean',
        'flagged_for_review' => 'boolean',
        'plagiarism_score' => 'decimal:2',
        'time_spent' => 'integer',
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

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function attempt()
    {
        return $this->hasOne(AssessmentAttempt::class, 'submission_id');
    }

    /**
     * Accessors
     */
    public function getSubmissionFileUrlAttribute()
    {
        return $this->submission_file_path ? Storage::url($this->submission_file_path) : null;
    }

    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->submission_file_size;
        if (!$bytes) return 'N/A';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFormattedTimeSpentAttribute()
    {
        $seconds = $this->time_spent;
        if (!$seconds) return 'N/A';
        
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        if ($minutes > 0) {
            return "{$minutes}m {$secs}s";
        }
        return "{$secs}s";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'not_started' => 'bg-gray-100 text-gray-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'submitted' => 'bg-yellow-100 text-yellow-800',
            'graded' => 'bg-green-100 text-green-800',
            'late' => 'bg-red-100 text-red-800',
            'expired' => 'bg-red-100 text-red-800',
        ];
        
        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusTextAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Calculate score from answers
     */
    public function calculateScore()
    {
        $questions = $this->assessment->questions;
        $totalPoints = $questions->sum('points');
        $earnedPoints = 0;
        $responses = [];
        
        foreach ($questions as $question) {
            $answer = $this->answers[$question->id] ?? null;
            $points = $question->calculatePoints($answer);
            
            $responses[$question->id] = [
                'question_id' => $question->id,
                'answer' => $answer,
                'points_earned' => $points,
                'points_possible' => $question->points,
                'correct' => $points === $question->points,
            ];
            
            if ($points !== null) {
                $earnedPoints += $points;
            }
        }
        
        $this->question_responses = $responses;
        $this->score = $earnedPoints;
        $this->percentage = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $this->passed = $this->percentage >= $this->assessment->passing_score;
        
        return $this;
    }

    /**
     * Mark as graded
     */
    public function markAsGraded($score, $feedback = null, $graderId = null)
    {
        $this->score = $score;
        $this->percentage = $this->assessment->total_marks > 0 
            ? ($score / $this->assessment->total_marks) * 100 
            : 0;
        $this->passed = $this->percentage >= $this->assessment->passing_score;
        $this->feedback = $feedback;
        $this->graded_at = now();
        $this->graded_by = $graderId ?? auth()->id();
        $this->status = 'graded';
        
        $this->save();
        
        // Update assessment statistics
        $this->assessment->calculateStatistics();
        
        return $this;
    }

    /**
     * Check if submission is late
     */
    public function isLate()
    {
        if (!$this->assessment->due_date) return false;
        
        return $this->submitted_at && $this->submitted_at > $this->assessment->due_date;
    }

    /**
     * Scope queries
     */
    public function scopePendingGrading($query)
    {
        return $query->where('status', 'submitted')
            ->where('flagged_for_review', false);
    }

    public function scopeFlagged($query)
    {
        return $query->where('flagged_for_review', true);
    }

    public function scopeGraded($query)
    {
        return $query->where('status', 'graded');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAssessment($query, $assessmentId)
    {
        return $query->where('assessment_id', $assessmentId);
    }
}