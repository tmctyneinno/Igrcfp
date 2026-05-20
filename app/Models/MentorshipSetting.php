<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorshipSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'max_mentees',
    ];

    protected $casts = [
        'max_mentees' => 'integer',
    ];
}
