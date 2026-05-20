<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'domain',
        'region',
        'country',
        'bio',
        'expertise_summary',
        'availability_status',
        'languages',
        'skills',
        'certifications',
        'max_mentees',
        'status',
        'admin_feedback',
        'processed_by_admin_id',
        'processed_at',
    ];

    protected $casts = [
        'languages' => 'array',
        'skills' => 'array',
        'certifications' => 'array',
        'processed_at' => 'datetime',
        'max_mentees' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(Admin::class, 'processed_by_admin_id');
    }
}
