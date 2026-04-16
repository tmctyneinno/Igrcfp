<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorshipMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentorship_id',
        'user_id',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function mentorship()
    {
        return $this->belongsTo(Mentorship::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
