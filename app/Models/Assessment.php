<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Assessment extends Model
{
    use SoftDeletes;

    protected $table = 'assessments';

    protected $fillable = [
        'course_id', 'module_id', 'title', 'description', 'type',
        'assessment_level', 'status', 'release_date', 'due_date',
        'total_marks', 'passing_score', 'weight', 'duration',
        'is_timed', 'requires_identity_verification', 'needs_manual_marking',
        'allow_late_submissions', 'question_count', 'question_bank_settings',
        'file_path', 'file_name', 'file_size', 'file_extension',
        'project_brief', 'rubric', 'deliverables', 'settings',
        'has_essay', 'essay_instructions', 'essay_settings',
        'attempts_count', 'submissions_count', 'pending_grading_count',
        'average_score', 'highest_score', 'lowest_score',
        'instructions', 'max_file_size', 'allowed_file_types',
    ];

    protected $casts = [
        'release_date'                   => 'datetime',
        'due_date'                       => 'datetime',
        'is_timed'                       => 'boolean',
        'requires_identity_verification' => 'boolean',
        'needs_manual_marking'           => 'boolean',
        'allow_late_submissions'         => 'boolean',
        'question_bank_settings'         => 'array',
        'rubric'                         => 'array',
        'deliverables'                   => 'array',
        'settings'                       => 'array',
        'has_essay'                      => 'boolean',
        'essay_settings'                 => 'array',
        'average_score'                  => 'decimal:2',
        'highest_score'                  => 'decimal:2',
        'lowest_score'                   => 'decimal:2',
        'allowed_file_types'             => 'array',
        'max_file_size'                  => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function questions()
    {
        return $this->hasMany(AssessmentQuestion::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssessmentSubmission::class);
    }

    public function attempts()
    {
        return $this->hasMany(AssessmentAttempt::class);
    }

    public function scopeQuizzes($query)
    {
        return $query->where('assessment_level', 'quiz');
    }

    public function scopeModuleAssessments($query)
    {
        return $query->where('assessment_level', 'module_assessment');
    }

    public function scopeFinalExams($query)
    {
        return $query->where('assessment_level', 'final_exam');
    }

    public function scopeDiplomaAssessments($query)
    {
        return $query->where('assessment_level', 'diploma');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'active')
            ->where('release_date', '<=', now())
            ->where(function($q) {
                $q->whereNull('due_date')->orWhere('due_date', '>', now());
            });
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
            ->where('release_date', '<=', now());
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now());
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeByModule($query, $moduleId)
    {
        return $query->where('module_id', $moduleId);
    }

    public function scopeProjects($query)
    {
        return $query->whereIn('assessment_level', ['diploma', 'project']);
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        if (!$bytes) return 'N/A';
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsAvailableAttribute()
    {
        if ($this->status !== 'active') return false;
        if ($this->release_date && $this->release_date > now()) return false;
        return true;
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date && $this->due_date < now();
    }

    public function getTimeRemainingAttribute()
    {
        if (!$this->due_date) return null;
        $seconds = now()->diffInSeconds($this->due_date, false);
        if ($seconds <= 0) return 'Expired';
        $days    = floor($seconds / 86400);
        $hours   = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        if ($days > 0)    return "{$days} days";
        if ($hours > 0)   return "{$hours} hours";
        if ($minutes > 0) return "{$minutes} minutes";
        return "Less than a minute";
    }

    public function getPassingScoreFormattedAttribute()
    {
        return $this->passing_score ? "{$this->passing_score}%" : 'N/A';
    }

    public function getSuccessRateAttribute()
    {
        if ($this->submissions_count === 0) return 0;
        $passed = $this->submissions()->where('passed', true)->count();
        return round(($passed / $this->submissions_count) * 100, 2);
    }

    public function isProject(): bool
    {
        return in_array($this->assessment_level, ['diploma', 'project']);
    }

    public function isQuiz(): bool
    {
        return $this->assessment_level === 'quiz';
    }

    public function userHasAccess($userId)
    {
        $enrollment = Enrollment::where('user_id', $userId)
            ->where('course_id', $this->course_id)
            ->where('status', 'enrolled')
            ->first();
        if (!$enrollment) return false;
        if (!$this->is_available) return false;
        if ($this->assessment_level !== 'quiz') {
            $submission = $this->submissions()
                ->where('user_id', $userId)
                ->where('status', 'graded')
                ->first();
            if ($submission) return false;
        }
        return true;
    }

    public function getUserSubmission($userId)
    {
        return $this->submissions()->where('user_id', $userId)->latest()->first();
    }

    public function getUserAttempt($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'in_progress')
            ->first();
    }

    public function calculateStatistics()
    {
        $submissions = $this->submissions()->where('status', 'graded')->get();
        $this->submissions_count     = $submissions->count();
        $this->attempts_count        = $this->attempts()->count();
        $this->pending_grading_count = $this->submissions()
            ->where('status', 'submitted')->count();
        if ($submissions->isNotEmpty()) {
            $this->average_score = $submissions->avg('score');
            $this->highest_score = $submissions->max('score');
            $this->lowest_score  = $submissions->min('score');
        }
        $this->save();
    }
}