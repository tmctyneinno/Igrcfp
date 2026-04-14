<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tier_id',
        'name',
        'price',
        'currency',
        'billing_interval',
        'duration_months',
        'benefits',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'benefits' => 'array',
        'is_active' => 'boolean',
        'duration_months' => 'integer',
    ];

    public function tier()
    {
        return $this->belongsTo(MembershipTier::class, 'tier_id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
}
