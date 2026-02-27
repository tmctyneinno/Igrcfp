<?php

// app/Models/Enrollment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'name',
        'email',
        'phone',
        'payment_method',
        'amount',
        'status',
        'enrollment_date',
        'completed_at',
        'certificate_issued',
        'certificate_url',
        'certificate_generated',      // ADD THIS
        'certificate_generated_date',  // ADD THIS
        'certificate_number',          // ADD THIS
        'final_grade',                  // ADD THIS
        'notes',
        'progress',
    ];

    protected $casts = [
        'enrollment_date' => 'datetime',
        'completed_at' => 'datetime',
        'certificate_issued' => 'boolean',
        'certificate_generated' => 'boolean',  // ADD THIS
        'certificate_generated_date' => 'datetime',  // ADD THIS
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending_payment');
    }

    public function scopeEnrolled($query)
    {
        return $query->where('status', 'enrolled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Helper method to check if certificate is available
    public function hasCertificate()
    {
        return $this->certificate_generated && $this->certificate_number;
    }
}