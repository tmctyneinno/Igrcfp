<?php
// app/Models/AssessmentQuestion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentQuestion extends Model
{
    use SoftDeletes;

    protected $table = 'assessment_questions';

    protected $fillable = [
        'assessment_id',
        'question_text',
        'question_type',
        'options',
        'correct_answer',
        'correct_answers',
        'points',
        'is_required',
        'image_url',
        'reference_text',
        'order',
        'difficulty_level',
        'tags',
        'explanation',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
        'is_required' => 'boolean',
        'tags' => 'array',
        'points' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Check if answer is correct
     */
    public function isAnswerCorrect($answer)
    {
        switch ($this->question_type) {
            case 'multiple_choice':
            case 'true_false':
                return $answer == $this->correct_answer;
                
            case 'multiple_answer':
                $correct = is_array($this->correct_answers) ? $this->correct_answers : json_decode($this->correct_answers, true);
                $submitted = is_array($answer) ? $answer : json_decode($answer, true);
                
                if (!is_array($correct) || !is_array($submitted)) return false;
                
                sort($correct);
                sort($submitted);
                return $correct == $submitted;
                
            case 'short_answer':
                // Case-insensitive comparison, trim whitespace
                return strtolower(trim($answer)) == strtolower(trim($this->correct_answer));
                
            case 'essay':
            case 'case_study':
                // Essays need manual marking
                return null;
                
            default:
                return false;
        }
    }

    /**
     * Calculate points earned for this answer
     */
    public function calculatePoints($answer)
    {
        $isCorrect = $this->isAnswerCorrect($answer);
        
        if ($isCorrect === null) {
            // Manual marking needed
            return null;
        }
        
        return $isCorrect ? $this->points : 0;
    }

    /**
     * Get shuffled options (for multiple choice)
     */
    public function getShuffledOptionsAttribute()
    {
        if (!is_array($this->options)) {
            return [];
        }
        
        $options = $this->options;
        shuffle($options);
        return $options;
    }
}