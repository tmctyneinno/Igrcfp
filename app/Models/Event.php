<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'image',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'address',
        'venue',
        'capacity',
        'available_seats',
        'status',
        'is_featured',
        'meta_description',
        'meta_keywords',
        'user_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
        'capacity' => 'integer',
        'available_seats' => 'integer',
    ];

    protected $appends = [
        'excerpt',
        'image_url',
        'event_date',
        'event_time',
        'status_badge',
        'is_upcoming',
        'is_ongoing',
        'is_past',
        'registration_status'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'user_id');
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 150);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/default-event.jpg');
        }
        
        return asset('storage/' . $this->image);
    }

    public function getEventDateAttribute()
    {
        // Check if start_date is null
        if (!$this->start_date) {
            return 'Date not set';
        }

        // Convert to Carbon if not already
        $startDate = $this->start_date instanceof Carbon 
            ? $this->start_date 
            : Carbon::parse($this->start_date);
        
        // Check if end_date is null or same as start_date
        if (!$this->end_date) {
            return $startDate->format('M d, Y');
        }
        
        $endDate = $this->end_date instanceof Carbon 
            ? $this->end_date 
            : Carbon::parse($this->end_date);
        
        if ($startDate->eq($endDate)) {
            return $startDate->format('M d, Y');
        }
        
        // If same month, show "M d - d, Y"
        if ($startDate->format('M Y') === $endDate->format('M Y')) {
            return $startDate->format('M d') . ' - ' . $endDate->format('d, Y');
        }
        
        // Different months
        return $startDate->format('M d') . ' - ' . $endDate->format('M d, Y');
    }

    public function getEventTimeAttribute()
    {
        if (!$this->start_time && !$this->end_time) {
            return 'Time not set';
        }
        
        if (!$this->end_time) {
            return $this->start_time ?? 'Time not set';
        }
        
        return ($this->start_time ?? 'TBD') . ' - ' . ($this->end_time ?? 'TBD');
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'published' => ['class' => 'bg-success', 'text' => 'Published'],
            'draft' => ['class' => 'bg-warning', 'text' => 'Draft'],
            'cancelled' => ['class' => 'bg-danger', 'text' => 'Cancelled']
        ];

        $status = $statuses[$this->status] ?? $statuses['draft'];
        
        return '<span class="badge ' . $status['class'] . '">' . $status['text'] . '</span>';
    }

    public function getIsUpcomingAttribute()
    {
        if (!$this->start_date) {
            return false;
        }
        
        $startDate = $this->start_date instanceof Carbon 
            ? $this->start_date 
            : Carbon::parse($this->start_date);
            
        return $startDate->gt(Carbon::now());
    }

    public function getIsOngoingAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return false;
        }
        
        $now = Carbon::now();
        $startDate = $this->start_date instanceof Carbon 
            ? $this->start_date 
            : Carbon::parse($this->start_date);
        $endDate = $this->end_date instanceof Carbon 
            ? $this->end_date 
            : Carbon::parse($this->end_date);
            
        return $startDate->lte($now) && $endDate->gte($now);
    }

    public function getIsPastAttribute()
    {
        if (!$this->end_date) {
            return false;
        }
        
        $endDate = $this->end_date instanceof Carbon 
            ? $this->end_date 
            : Carbon::parse($this->end_date);
            
        return $endDate->lt(Carbon::now());
    }

    public function getRegistrationStatusAttribute()
    {
        // Check if available_seats is null
        if ($this->available_seats === null) {
            return 'not_set';
        }
        
        if ($this->available_seats <= 0) {
            return 'sold_out';
        } elseif ($this->available_seats < 10) {
            return 'few_seats';
        } else {
            return 'available';
        }
    }

    // Optional: Add a method to format date safely
    public function getFormattedDate($dateField, $format = 'M d, Y')
    {
        if (!$this->$dateField) {
            return 'Not set';
        }
        
        $date = $this->$dateField instanceof Carbon 
            ? $this->$dateField 
            : Carbon::parse($this->$dateField);
            
        return $date->format($format);
    }

    // Optional: Add a method to get dates as Carbon instances
    public function getStartDateCarbon()
    {
        return $this->start_date ? 
            ($this->start_date instanceof Carbon ? $this->start_date : Carbon::parse($this->start_date)) : 
            null;
    }

    public function getEndDateCarbon()
    {
        return $this->end_date ? 
            ($this->end_date instanceof Carbon ? $this->end_date : Carbon::parse($this->end_date)) : 
            null;
    }
}