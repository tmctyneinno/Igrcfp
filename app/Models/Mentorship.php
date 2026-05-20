<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentorship extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_profile_id',
        'mentee_id',
        'mentorship_application_id',
        'status',
        'started_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function mentorProfile()
    {
        return $this->belongsTo(MentorProfile::class);
    }

    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }

    public function application()
    {
        return $this->belongsTo(MentorshipApplication::class, 'mentorship_application_id');
    }

    public function updates()
    {
        return $this->hasMany(MentorshipUpdate::class);
    }

    public function messages()
    {
        return $this->hasMany(MentorshipMessage::class);
    }
}
