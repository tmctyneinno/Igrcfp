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
        'short_description',
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
        return $this->hasMany(CourseMaterial::class)->orderBy('sort_order');
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
}