<?php
// app/Models/Assessment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'type',
        'status',
        'duration',
        'total_marks',
        'weight',
        'due_date',
        'release_date',
        'is_timed',
        'needs_manual_marking',
        'allow_late_submissions',
        'file_path',
        'file_name',
        'file_size',
        'submissions_count',
        'pending_grading_count',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'release_date' => 'datetime',
        'is_timed' => 'boolean',
        'needs_manual_marking' => 'boolean',
        'allow_late_submissions' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssessmentSubmission::class);
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}