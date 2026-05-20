<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// ← REMOVED: use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentQuestion extends Model
{
    // ← REMOVED: use SoftDeletes;

    protected $table = 'assessment_questions';
    protected $touches = [];

    protected $fillable = [
        'assessment_id',
        'module_id',
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
        'options'         => 'array',
        'correct_answers' => 'array',
        'is_required'     => 'boolean',
        'tags'            => 'array',
        'points'          => 'decimal:2',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function getOptionsAttribute($value): array
    {
        if (is_null($value)) return [];
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function setOptionsAttribute($value): void
    {
        $this->attributes['options'] = is_array($value) ? json_encode($value) : $value;
    }

    public function isAnswerCorrect($answer): ?bool
    {
        switch ($this->question_type) {
            case 'multiple_choice':
            case 'true_false':
            case 'short_answer':
                return strtolower(trim((string) $answer))
                    === strtolower(trim((string) $this->correct_answer));

            case 'multiple_answer':
                $correct   = is_array($this->correct_answers)
                    ? $this->correct_answers
                    : json_decode($this->correct_answers, true);
                $submitted = is_array($answer)
                    ? $answer
                    : json_decode($answer, true);
                if (!is_array($correct) || !is_array($submitted)) return false;
                sort($correct);
                sort($submitted);
                return $correct === $submitted;

            case 'essay':
            case 'case_study':
                return null;

            default:
                return false;
        }
    }

    public function calculatePoints($answer): ?float
    {
        $isCorrect = $this->isAnswerCorrect($answer);
        return $isCorrect === null ? null : ($isCorrect ? (float) $this->points : 0.0);
    }

    public function getShuffledOptionsAttribute(): array
    {
        $options = $this->options;
        if (!is_array($options) || empty($options)) return [];
        $shuffled = $options;
        shuffle($shuffled);
        return $shuffled;
    }

    public function getCorrectAnswerTextAttribute(): string
    {
        return $this->correct_answer ?? '';
    }
}