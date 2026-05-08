<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'code',
        'module_number',
        'course_outline',
        'full_content',
        'learning_objectives',
        'key_concepts',
        'topics_covered', 
        'case_study',
        'exercise', 
        'additional_notes',
        'estimated_hours', 
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'estimated_hours' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relationships
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function sections()
    {
        return $this->hasMany(ModuleSection::class)->orderBy('sort_order');
    }

    public function materials()
    {
        return $this->hasMany(CourseMaterial::class, 'module_id')->orderBy('sort_order');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('module_number')->orderBy('sort_order');
    }

    /**
     * Attributes
     */
    public function getModuleCodeAttribute()
    {
        return $this->code ?: "MOD{$this->module_number}";
    }

    public function getFormattedNumberAttribute()
    {
        return "Module {$this->module_number}";
    }

    public function getDurationAttribute()
    {
        return $this->estimated_hours . ' hour' . ($this->estimated_hours > 1 ? 's' : '');
    }

    /**
     * Get next module number for the course
     */
    public static function getNextModuleNumber($courseId)
    {
        $lastModule = self::where('course_id', $courseId)
            ->orderBy('module_number', 'desc')
            ->first();
        
        return $lastModule ? $lastModule->module_number + 1 : 1;
    }

    /**
     * Get the lessons for this module
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'module_id');
    }

    public function readingProgress()
    {
        return $this->hasMany(CourseModuleUser::class, 'course_module_id');
    }
    
}
