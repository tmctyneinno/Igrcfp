<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterLeadership extends Model
{
    use HasFactory;

    /**
     * Explicitly define table name (matches your migration)
     *
     * @var string
     */
    protected $table = 'chapter_leadership';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chapter_id',
        'name',
        'role',
        'email',
        'phone',
    ];

    /**
     * Get the chapter this leader belongs to.
     */
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}