<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleSection extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'section_number',
        'content',
        'content_type',
        'attachments',
        'sort_order',
    ];

    protected $casts = [
        'attachments' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * Relationships
     */
    public function module()
    {
        return $this->belongsTo(CourseModule::class);
    }

    /**
     * Get next section number for the module
     */
    public static function getNextSectionNumber($moduleId)
    {
        $lastSection = self::where('module_id', $moduleId)
            ->orderByRaw('CAST(section_number AS DECIMAL(10,2)) DESC')
            ->first();
        
        if (!$lastSection) {
            return '1.1';
        }
        
        // Increment the last part of the section number
        $parts = explode('.', $lastSection->section_number);
        $lastPart = end($parts);
        $parts[count($parts) - 1] = $lastPart + 1;
        
        return implode('.', $parts);
    }
}