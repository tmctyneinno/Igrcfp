<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'membership_plan_id',
        'status',
        'purchased_at',
        'approved_at',
        'starts_at',
        'expires_at',
        'cancelled_at',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'approved_at' => 'datetime',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->whereNotNull('approved_at')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function isActive(): bool
    {
        if ($this->status !== 'active' || !$this->approved_at) {
            return false;
        }

        if (!$this->expires_at) {
            return true;
        }

        return Carbon::parse($this->expires_at)->isFuture();
    }

    public function statusLabel(): string
    {
        if ($this->status === 'active' && $this->isActive()) {
            return 'Active';
        }

        if ($this->status === 'pending_approval') {
            return 'Pending Approval';
        }

        if ($this->status === 'cancelled') {
            return 'Cancelled';
        }

        if ($this->status === 'expired') {
            return 'Expired';
        }

        return ucfirst(str_replace('_', ' ', $this->status));
    }
}
