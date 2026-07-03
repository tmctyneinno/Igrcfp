<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'region',
        'slug',
        'name',
        'country_focus',
        'description',
        'annual_fee',
        'contact_email',
        'meeting_frequency',
        'benefits',
        'is_active'
    ];

    protected $casts = [
        'benefits' => 'array',
        'is_active' => 'boolean',
        'annual_fee' => 'decimal:2',
    ];

    // ✅ Relationship: Events
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // ✅ Relationship: Members — update this if your table/model is different
    public function members()
    {
        // If you use a pivot table:
        // return $this->belongsToMany(User::class, 'chapter_members');
        
        // If you have a direct foreign key:
        return $this->hasMany(User::class); // or Member::class
    }
}