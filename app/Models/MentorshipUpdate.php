<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorshipUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentorship_id',
        'type',
        'title',
        'content',
        'scheduled_at',
        'rating',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'rating' => 'integer',
    ];

    public function mentorship()
    {
        return $this->belongsTo(Mentorship::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
