<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'item_type',
        'course_id',
        'membership_plan_id',
        'price',
        'quantity'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    // Relationships
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function membershipPlan()
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }

    // Accessors
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
