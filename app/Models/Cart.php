<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'status',
        'total_amount',
        'item_count'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'item_count' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Methods
    public function updateTotals()
    {
        $this->load('items');
        $this->update([
            'total_amount' => $this->items->sum('price'),
            'item_count' => $this->items->count(),
        ]);
    }

    public function addItem($courseId, $price, $quantity = 1)
    {
        $item = $this->items()->where('course_id', $courseId)->first();
        
        if ($item) {
            $item->update([
                'quantity' => $item->quantity + $quantity
            ]);
        } else {
            $this->items()->create([
                'course_id' => $courseId,
                'price' => $price,
                'quantity' => $quantity
            ]);
        }
        
        $this->updateTotals();
        return $this;
    }

    public function removeItem($itemId)
    {
        $this->items()->where('id', $itemId)->delete();
        $this->updateTotals();
        return $this;
    }

    public function clear()
    {
        $this->items()->delete();
        $this->update([
            'total_amount' => 0,
            'item_count' => 0
        ]);
        return $this;
    }

    // Add relationship to carts
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function activeCart()
    {
        return $this->hasOne(Cart::class)->where('status', 'active');
    }
    
}