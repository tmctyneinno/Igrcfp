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
        'rejection_reason',      // Add this
        'rejected_at',           // Add this
        'accepted_at',           // Add this
        'user_accepted',         // Add this
    ];

    protected $casts = [
        'preferred_programmes' => 'array',
        'declaration' => 'boolean',
        'user_accepted' => 'boolean',
        'rejected_at' => 'datetime',
        'accepted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Helper methods
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isUnderReview()
    {
        return $this->status === 'under_review';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function hasBeenAcceptedByUser()
    {
        return $this->user_accepted;
    }

    public function post()
    {
        return $this->belongsTo(Article::class, 'post_id');
    }
}