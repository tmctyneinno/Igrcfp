<?php
// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'enrollment_id',
        'membership_id',
        'transaction_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'payment_details',
        'reference',
        'session_id',
        'paid_at',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'payment_details' => 'array',
    ];

    /**
     * Get the user that owns the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the enrollment associated with the transaction
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * Scope a query to only include completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to only include refunded transactions
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }
}
