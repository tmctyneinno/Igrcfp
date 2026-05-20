<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MentorshipSetting;

class MentorProfile extends Model
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
        'rating',
        'completed_mentorships_count',
        'is_active',
    ];

    protected $casts = [
        'languages' => 'array',
        'skills' => 'array',
        'certifications' => 'array',
        'is_active' => 'boolean',
        'max_mentees' => 'integer',
        'rating' => 'decimal:2',
        'completed_mentorships_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(MentorshipApplication::class);
    }

    public function mentorships()
    {
        return $this->hasMany(Mentorship::class);
    }

    public function activeMentorships()
    {
        return $this->mentorships()->where('status', 'active');
    }

    public function remainingCapacity(): int
    {
        $limit = $this->max_mentees;
        if (!$limit) {
            $settings = MentorshipSetting::query()->first();
            $limit = $settings?->max_mentees ?? 5;
        }

        $activeCount = $this->activeMentorships()->count();
        return max(0, $limit - $activeCount);
    }
}
