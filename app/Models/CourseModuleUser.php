<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseModuleUser extends Model
{
    protected $table = 'course_module_user';

    protected $fillable = [
        'user_id',
        'enrollment_id',
        'course_module_id',
        'reading_progress',
        'read',
        'read_at',
        'last_viewed_at',
        'metadata',
    ];

    protected $casts = [
        'read' => 'boolean',
        'read_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }
}
