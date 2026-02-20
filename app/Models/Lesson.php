<?php
// app/Models/Lesson.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'description',
        'content',
        'video_url',
        'duration',
        'order',
        'is_free',
        'status'
    ];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    /**
     * Get the module that owns the lesson
     */
    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    /**
     * Get the completions for this lesson
     */
    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }
}