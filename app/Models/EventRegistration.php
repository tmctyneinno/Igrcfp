<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EventRegistration extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'events_registrations';

    protected $fillable = [
        'event_id',
        'registration_number',
        'name',
        'email',
        'phone',
        'company',
        'position',
        'additional_attendees',
        'dietary_requirements',
        'special_requirements',
        'hear_about_event',
        'status',
        'notes',
        'payment_status',
        'payment_amount',
        'payment_method',
        'transaction_id',
    ];

    protected $casts = [
        'additional_attendees' => 'integer',
        'payment_amount' => 'decimal:2',
        'registered_at' => 'datetime',
    ];

    protected $appends = [
        'total_attendees',
        'status_badge',
        'payment_status_badge',
    ];

    // Automatically generate registration number when creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (!$registration->registration_number) {
                $registration->registration_number = 'REG-' . strtoupper(Str::random(8));
            }
        });
    }

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Accessors
    public function getTotalAttendeesAttribute()
    {
        return 1 + $this->additional_attendees;
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => ['class' => 'bg-warning', 'text' => 'Pending'],
            'confirmed' => ['class' => 'bg-success', 'text' => 'Confirmed'],
            'cancelled' => ['class' => 'bg-danger', 'text' => 'Cancelled'],
            'attended' => ['class' => 'bg-info', 'text' => 'Attended'],
        ];

        $status = $statuses[$this->status] ?? $statuses['pending'];
        
        return '<span class="badge ' . $status['class'] . '">' . $status['text'] . '</span>';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => ['class' => 'bg-warning', 'text' => 'Payment Pending'],
            'paid' => ['class' => 'bg-success', 'text' => 'Paid'],
            'partial' => ['class' => 'bg-info', 'text' => 'Partial Payment'],
            'failed' => ['class' => 'bg-danger', 'text' => 'Payment Failed'],
            'refunded' => ['class' => 'bg-secondary', 'text' => 'Refunded'],
        ];

        $status = $statuses[$this->payment_status] ?? null;
        
        if (!$status) {
            return '<span class="badge bg-light text-dark">No Payment Required</span>';
        }
        
        return '<span class="badge ' . $status['class'] . '">' . $status['text'] . '</span>';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    // Methods
    public function confirm()
    {
        $this->status = 'confirmed';
        $this->save();
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        $this->save();
        
        // Increment available seats if event has capacity
        if ($this->event && $this->event->available_seats !== null) {
            $this->event->incrementAvailableSeats($this->total_attendees);
        }
    }

    public function markAsAttended()
    {
        $this->status = 'attended';
        $this->save();
    }
}