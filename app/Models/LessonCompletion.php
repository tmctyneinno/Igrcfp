<?php
// app/Models/LessonCompletion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    /**
     * Get the user that completed the lesson
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson that was completed
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}