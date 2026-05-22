<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MembershipTier extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'name',
        'slug',
        'description',
        'benefits',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'benefits' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function plans()
    {
        return $this->hasMany(MembershipPlan::class, 'tier_id');
    }

    protected static function booted()
    {
        static::creating(function (self $tier) {
            if (empty($tier->slug)) {
                $tier->slug = Str::slug($tier->name);
            }
        });
    }
}
