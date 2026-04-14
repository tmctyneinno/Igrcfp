<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorshipApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_profile_id',
        'mentee_id',
        'goals',
        'preferred_duration',
        'availability',
        'communication_method',
        'notes',
        'status',
        'mentor_feedback',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function mentorProfile()
    {
        return $this->belongsTo(MentorProfile::class);
    }

    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }
}
