<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'full_name',
        'nationality',
        'country_of_residence',
        'email',
        'phone_number',
        'academic_background',
        'highest_qualification',
        'institution',
        'year_completed',
        'current_role',
        'organisation',
        'preferred_programmes',
        'personal_statement',
        'declaration',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'preferred_programmes' => 'array',
        'declaration' => 'boolean',
    ];

    public function post()
    {
        return $this->belongsTo(Article::class, 'post_id');
    }
}