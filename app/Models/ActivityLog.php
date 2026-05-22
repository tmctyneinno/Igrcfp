<?php
// app/Models/ActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'loggable_type',
        'loggable_id',
        'subject_type',
        'subject_id',
        'event',
        'module',
        'action',
        'description',
        'properties',
        'ip_address',
        'user_agent',
        'severity',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    // ─── Event Constants ──────────────────────────────────────────────────
    const EVENT_CREATED       = 'created';
    const EVENT_UPDATED       = 'updated';
    const EVENT_DELETED       = 'deleted';
    const EVENT_RESTORED      = 'restored';
    const EVENT_LOGIN         = 'logged_in';
    const EVENT_LOGIN_FAILED  = 'login_failed';
    const EVENT_LOGOUT        = 'logged_out';
    const EVENT_PASSWORD_CHANGE = 'password_changed';
    const EVENT_EXPORT        = 'exported';
    const EVENT_IMPORT        = 'imported';
    const EVENT_STATUS_CHANGE = 'status_changed';
    const EVENT_SETTINGS_CHANGE = 'settings_changed';

    // ─── Severity Constants ───────────────────────────────────────────────
    const SEVERITY_INFO     = 'info';
    const SEVERITY_WARNING  = 'warning';
    const SEVERITY_ERROR    = 'error';
    const SEVERITY_CRITICAL = 'critical';

    // ─── Relationships ───────────────────────────────────────────────────
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    // ─── Scopes ──────────────────────────────────────────────────────────
    public function scopeByAdmin($query, $adminId = null)
    {
        return $query->where('loggable_type', Admin::class)
            ->when($adminId, fn($q) => $q->where('loggable_id', $adminId));
    }

    public function scopeByUser($query, $userId = null)
    {
        return $query->where('loggable_type', User::class)
            ->when($userId, fn($q) => $q->where('loggable_id', $userId));
    }

    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeByEvent($query, $event)
    {
        return $query->where('event', $event);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    public function scopeSecurity($query)
    {
        $securityEvents = [
            self::EVENT_LOGIN,
            self::EVENT_LOGIN_FAILED,
            self::EVENT_LOGOUT,
            self::EVENT_PASSWORD_CHANGE,
        ];
        return $query->whereIn('event', $securityEvents);
    }

    // ─── Static Logging Helper ───────────────────────────────────────────
    public static function log(
        $loggable,
        string $event,
        ?string $module = null,
        ?string $action = null,
        ?string $description = null,
        $subject = null,
        ?array $properties = null,
        string $severity = self::SEVERITY_INFO
    ): self {
        return self::create([
            'loggable_type' => get_class($loggable),
            'loggable_id'   => $loggable->id,
            'subject_type'  => $subject ? get_class($subject) : null,
            'subject_id'    => $subject ? $subject->id : null,
            'event'         => $event,
            'module'        => $module,
            'action'        => $action,
            'description'   => $description,
            'properties'    => $properties,
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
            'severity'      => $severity,
        ]);
    }

    // ─── Accessors ───────────────────────────────────────────────────────
    public function getPerformerNameAttribute(): string
    {
        if ($this->loggable) {
            return $this->loggable->name ?? $this->loggable->email ?? 'System';
        }
        return 'System';
    }

    public function getPerformerRoleAttribute(): ?string
    {
        if ($this->loggable_type === Admin::class && $this->loggable) {
            return $this->loggable->role;
        }
        if ($this->loggable_type === User::class) {
            return 'User';
        }
        return null;
    }

    public function getSubjectNameAttribute(): ?string
    {
        if (!$this->subject) return null;
        
        if (method_exists($this->subject, 'title')) return $this->subject->title;
        if (method_exists($this->subject, 'name')) return $this->subject->name;
        
        return $this->subject_type . ' #' . $this->subject_id;
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->format('M d, Y H:i:s');
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getEventBadgeClassAttribute(): string
    {
        return match($this->event) {
            self::EVENT_CREATED         => 'badge bg-success',
            self::EVENT_UPDATED         => 'badge bg-info',
            self::EVENT_DELETED         => 'badge bg-danger',
            self::EVENT_LOGIN           => 'badge bg-primary',
            self::EVENT_LOGIN_FAILED    => 'badge bg-warning',
            self::EVENT_LOGOUT          => 'badge bg-secondary',
            self::EVENT_PASSWORD_CHANGE => 'badge bg-dark',
            default                     => 'badge bg-light text-dark',
        };
    }

    public function getEventDisplayNameAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->event));
    }

    public function getSeverityBadgeClassAttribute(): string
    {
        return match($this->severity) {
            self::SEVERITY_INFO     => 'badge bg-info',
            self::SEVERITY_WARNING  => 'badge bg-warning',
            self::SEVERITY_ERROR    => 'badge bg-danger',
            self::SEVERITY_CRITICAL => 'badge bg-dark',
            default                 => 'badge bg-secondary',
        };
    }
}