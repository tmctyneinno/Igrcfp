<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Notification types
     */
    public const TYPE_CERTIFICATE_GENERATED = 'certificate_generated';
    public const TYPE_QUIZ_PASSED = 'quiz_passed';
    public const TYPE_QUIZ_FAILED = 'quiz_failed';
    public const TYPE_PROJECT_SUBMITTED = 'project_submitted';
    public const TYPE_PROJECT_GRADED = 'project_graded';
    public const TYPE_COURSE_COMPLETED = 'course_completed';
    public const TYPE_MODULE_COMPLETED = 'module_completed';
    public const TYPE_ASSESSMENT_DUE = 'assessment_due';
    public const TYPE_ENROLLMENT_CONFIRMED = 'enrollment_confirmed';

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope by notification type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
        return $this;
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
        return $this;
    }

    /**
     * Check if notification is read
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if notification is unread
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Get formatted time ago
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get notification icon based on type
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            self::TYPE_CERTIFICATE_GENERATED => '🎓',
            self::TYPE_QUIZ_PASSED => '✅',
            self::TYPE_QUIZ_FAILED => '❌',
            self::TYPE_PROJECT_SUBMITTED => '📤',
            self::TYPE_PROJECT_GRADED => '📊',
            self::TYPE_COURSE_COMPLETED => '🏆',
            self::TYPE_MODULE_COMPLETED => '📚',
            self::TYPE_ASSESSMENT_DUE => '⏰',
            self::TYPE_ENROLLMENT_CONFIRMED => '🎉',
            default => '📌',
        };
    }

    /**
     * Get notification color based on type
     */
    public function getColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_CERTIFICATE_GENERATED => 'purple',
            self::TYPE_QUIZ_PASSED => 'green',
            self::TYPE_QUIZ_FAILED => 'red',
            self::TYPE_PROJECT_SUBMITTED => 'blue',
            self::TYPE_PROJECT_GRADED => 'indigo',
            self::TYPE_COURSE_COMPLETED => 'emerald',
            self::TYPE_MODULE_COMPLETED => 'teal',
            self::TYPE_ASSESSMENT_DUE => 'orange',
            self::TYPE_ENROLLMENT_CONFIRMED => 'pink',
            default => 'gray',
        };
    }

    /**
     * Get notification link
     */
    public function getLinkAttribute(): ?string
    {
        $data = $this->data ?? [];
        
        return match($this->type) {
            self::TYPE_CERTIFICATE_GENERATED => route('dashboard.certificates.download', $data['enrollment_id'] ?? null),
            self::TYPE_QUIZ_PASSED, self::TYPE_QUIZ_FAILED => route('dashboard.quiz.results', [
                'course' => $data['course_slug'] ?? null,
                'assessment' => $data['assessment_id'] ?? null
            ]),
            self::TYPE_PROJECT_SUBMITTED, self::TYPE_PROJECT_GRADED => route('dashboard.quiz.project-assessment', [
                'course' => $data['course_slug'] ?? null
            ]),
            self::TYPE_COURSE_COMPLETED => route('dashboard.courses.show', $data['course_slug'] ?? null),
            default => null,
        };
    }
}