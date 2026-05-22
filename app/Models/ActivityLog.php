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
    public static function logActivity(
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
    
    /**
     * Get the performer name with proper formatting
     */
    public function getPerformerNameAttribute(): string
    {
        if (!$this->loggable) {
            return 'System';
        }

        if ($this->loggable instanceof Admin) {
            return $this->loggable->name ?? 'Unknown Admin';
        }

        if ($this->loggable instanceof User) {
            return $this->loggable->name ?? 'Unknown User';
        }

        return 'System';
    }

    /**
     * Get performer email
     */
    public function getPerformerEmailAttribute(): ?string
    {
        if ($this->loggable && method_exists($this->loggable, 'email')) {
            return $this->loggable->email;
        }
        return null;
    }

    /**
     * Get performer role with proper formatting
     */
    public function getPerformerRoleAttribute(): ?string
    {
        if ($this->loggable_type === Admin::class && $this->loggable) {
            return $this->loggable->role ? str_replace('_', ' ', ucwords($this->loggable->role, '_')) : 'Admin';
        }
        if ($this->loggable_type === User::class) {
            return 'Student';
        }
        return null;
    }

    /**
     * Get performer type badge
     */
    public function getPerformerTypeBadgeAttribute(): string
    {
        if ($this->loggable_type === Admin::class) {
            return 'badge bg-primary';
        }
        if ($this->loggable_type === User::class) {
            return 'badge bg-success';
        }
        return 'badge bg-secondary';
    }

    /**
     * Get performer type label
     */
    public function getPerformerTypeLabelAttribute(): string
    {
        if ($this->loggable_type === Admin::class) {
            return 'Admin';
        }
        if ($this->loggable_type === User::class) {
            return 'Student';
        }
        return 'System';
    }

    /**
     * Get subject display name with proper fallback
     */
    public function getSubjectNameAttribute(): ?string
    {
        if (!$this->subject_type || !$this->subject_id) {
            return null;
        }

        // If subject relationship is loaded, use it
        if ($this->relationLoaded('subject') && $this->subject) {
            return $this->resolveModelName($this->subject);
        }

        // Fallback: Try to find the model
        try {
            $subjectModel = $this->subject_type::find($this->subject_id);
            if ($subjectModel) {
                return $this->resolveModelName($subjectModel);
            }
        } catch (\Exception $e) {
            // Model not found, return formatted reference
        }

        // Last resort: return formatted reference
        return $this->getSubjectTypeDisplayAttribute() . ' #' . $this->subject_id;
    }

    /**
     * Resolve model name from various possible attributes
     */
    private function resolveModelName($model): string
    {
        // Check common name/title attributes in order of preference
        $nameAttributes = ['title', 'name', 'full_name', 'email', 'subject', 'heading'];
        
        foreach ($nameAttributes as $attr) {
            if (isset($model->$attr) && !empty($model->$attr)) {
                return $model->$attr;
            }
        }

        // If model has getNameAttribute accessor
        if (method_exists($model, 'getNameAttribute')) {
            return $model->name;
        }

        // Fallback to class basename + ID
        return class_basename($model) . ' #' . ($model->id ?? 'Unknown');
    }

    /**
     * Get subject type display name
     */
    public function getSubjectTypeDisplayAttribute(): string
    {
        $typeMap = [
            'App\\Models\\Course'               => 'Course',
            'App\\Models\\User'                 => 'User',
            'App\\Models\\Admin'                => 'Admin',
            'App\\Models\\Article'              => 'Article',
            'App\\Models\\Blog'                 => 'Blog',
            'App\\Models\\Event'                => 'Event',
            'App\\Models\\Assessment'           => 'Assessment',
            'App\\Models\\AssessmentQuestion'   => 'Assessment Question',
            'App\\Models\\AssessmentSubmission' => 'Submission',
            'App\\Models\\Enrollment'           => 'Enrollment',
            'App\\Models\\Membership'           => 'Membership',
            'App\\Models\\Notification'         => 'Notification',
            'App\\Models\\ScholarshipApplication' => 'Scholarship Application',
            'App\\Models\\ContactMessage'       => 'Contact Message',
            'App\\Models\\Mentorship'           => 'Mentorship',
        ];

        return $typeMap[$this->subject_type] ?? class_basename($this->subject_type);
    }

    /**
     * Get subject link for navigation
     */
    public function getSubjectLinkAttribute(): ?string
    {
        if (!$this->subject_type || !$this->subject_id) {
            return null;
        }

        $routeMap = [
            'App\\Models\\Course'    => 'admin.courses.edit',
            'App\\Models\\User'      => 'admin.users.show',
            'App\\Models\\Admin'     => 'admin.admins.edit',
            'App\\Models\\Article'   => 'admin.articles.edit',
            'App\\Models\\Blog'      => 'admin.blogs.edit',
            'App\\Models\\Event'     => 'admin.events.edit',
            'App\\Models\\Assessment'=> 'admin.assessments.show',
            'App\\Models\\Enrollment'=> 'admin.enrollments.show',
        ];

        $route = $routeMap[$this->subject_type] ?? null;

        return $route ? route($route, $this->subject_id) : null;
    }

    /**
     * Get formatted time
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->format('M d, Y H:i:s');
    }

    /**
     * Get time ago
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get event badge class
     */
    public function getEventBadgeClassAttribute(): string
    {
        return match($this->event) {
            self::EVENT_CREATED         => 'badge bg-success',
            self::EVENT_UPDATED         => 'badge bg-info',
            self::EVENT_DELETED         => 'badge bg-danger',
            self::EVENT_LOGIN           => 'badge bg-primary',
            self::EVENT_LOGIN_FAILED    => 'badge bg-warning text-dark',
            self::EVENT_LOGOUT          => 'badge bg-secondary',
            self::EVENT_PASSWORD_CHANGE => 'badge bg-dark',
            self::EVENT_EXPORT          => 'badge bg-info',
            self::EVENT_IMPORT          => 'badge bg-info',
            self::EVENT_STATUS_CHANGE   => 'badge bg-warning text-dark',
            default                     => 'badge bg-light text-dark',
        };
    }

    /**
     * Get event display name
     */
    public function getEventDisplayNameAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->event));
    }

    /**
     * Get event icon
     */
    public function getEventIconAttribute(): string
    {
        return match($this->event) {
            self::EVENT_CREATED         => 'fa-plus-circle',
            self::EVENT_UPDATED         => 'fa-edit',
            self::EVENT_DELETED         => 'fa-trash',
            self::EVENT_LOGIN           => 'fa-sign-in-alt',
            self::EVENT_LOGIN_FAILED    => 'fa-exclamation-triangle',
            self::EVENT_LOGOUT          => 'fa-sign-out-alt',
            self::EVENT_PASSWORD_CHANGE => 'fa-key',
            self::EVENT_EXPORT          => 'fa-download',
            self::EVENT_IMPORT          => 'fa-upload',
            self::EVENT_STATUS_CHANGE   => 'fa-toggle-on',
            default                     => 'fa-circle',
        };
    }

    /**
     * Get severity badge class
     */
    public function getSeverityBadgeClassAttribute(): string
    {
        return match($this->severity) {
            self::SEVERITY_INFO     => 'badge bg-info',
            self::SEVERITY_WARNING  => 'badge bg-warning text-dark',
            self::SEVERITY_ERROR    => 'badge bg-danger',
            self::SEVERITY_CRITICAL => 'badge bg-dark',
            default                 => 'badge bg-secondary',
        };
    }

    /**
     * Get action display with fallback
     */
    public function getActionDisplayAttribute(): string
    {
        if (!empty($this->action)) {
            return $this->action;
        }

        // Generate action from event and module if action is empty
        $eventAction = $this->event_display_name;
        $module = $this->module ? ' in ' . ucfirst($this->module) : '';
        
        return $eventAction . $module;
    }
}