<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentQuestion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assessment_id',
        'question_text',
        'question_type',
        'options',
        'marks',
        'correct_answer',
        'explanation',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'marks' => 'integer',
        'sort_order' => 'integer',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}