<?php
// app/Services/ActivityLoggerService.php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLoggerService
{ 
    /**
     * Log a general activity
     */
    public static function log( 
        string $event,
        ?string $module = null,
        ?string $action = null,
        ?string $description = null,
        ?Model $subject = null,
        ?array $properties = null,
        string $severity = ActivityLog::SEVERITY_INFO
    ): ActivityLog {
        $loggable = self::resolveLoggable();

        return ActivityLog::log(
            $loggable,
            $event,
            $module,
            $action,
            $description,
            $subject,
            $properties,
            $severity
        );
    }

    /**
     * Log model creation
     */
    public static function created(Model $model, ?string $module = null): ActivityLog
    {
        $module = $module ?? self::resolveModule($model);
        
        return self::log(
            ActivityLog::EVENT_CREATED,
            $module,
            'Created ' . class_basename($model),
            self::generateDescription('created', $model),
            $model,
            ['attributes' => $model->getAttributes()]
        );
    }

    /**
     * Log model update
     */
    public static function updated(Model $model, array $changes, ?string $module = null): ActivityLog
    {
        $module = $module ?? self::resolveModule($model);
        
        return self::log(
            ActivityLog::EVENT_UPDATED,
            $module,
            'Updated ' . class_basename($model),
            self::generateDescription('updated', $model),
            $model,
            [
                'changes' => $changes,
                'current' => $model->getAttributes()
            ]
        );
    }

    /**
     * Log model deletion
     */
    public static function deleted(Model $model, ?string $module = null): ActivityLog
    {
        $module = $module ?? self::resolveModule($model);
        
        return self::log(
            ActivityLog::EVENT_DELETED,
            $module,
            'Deleted ' . class_basename($model),
            self::generateDescription('deleted', $model),
            null,
            ['attributes' => $model->getAttributes()],
            ActivityLog::SEVERITY_WARNING
        );
    }

    /**
     * Log user login
     */
    public static function login(Model $user): ActivityLog
    {
        return self::log(
            ActivityLog::EVENT_LOGIN,
            'authentication',
            'User logged in',
            "{$user->email} logged in successfully",
            null,
            ['email' => $user->email]
        );
    }

    /**
     * Log failed login attempt
     */
    public static function loginFailed(?string $email = null): ActivityLog
    {
        $loggable = self::resolveLoggable();
        
        return ActivityLog::create([
            'loggable_type' => get_class($loggable),
            'loggable_id'   => $loggable->id ?? 0,
            'event'         => ActivityLog::EVENT_LOGIN_FAILED,
            'module'        => 'authentication',
            'action'        => 'Failed login attempt',
            'description'   => "Failed login attempt for: {$email}",
            'properties'    => ['email' => $email],
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
            'severity'      => ActivityLog::SEVERITY_WARNING,
        ]);
    }

    /**
     * Log user logout
     */
    public static function logout(Model $user): ActivityLog
    {
        return self::log(
            ActivityLog::EVENT_LOGOUT,
            'authentication',
            'User logged out',
            "{$user->email} logged out"
        );
    }

    /**
     * Resolve who is performing the action
     */
    private static function resolveLoggable(): Model
    {
        // Check admin guard first
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        }
        
        // Check web guard (regular user)
        if (Auth::check()) {
            return Auth::user();
        }
        
        // Fallback - system user
        return new User(['id' => 0, 'name' => 'System', 'email' => 'system@igrcfp.com']);
    }

    /**
     * Resolve module name from model
     */
    private static function resolveModule(Model $model): string
    {
        $map = [
            Course::class               => 'courses',
            Article::class              => 'articles',
            Blog::class                 => 'blogs',
            Event::class               => 'events',
            Assessment::class           => 'assessments',
            AssessmentQuestion::class   => 'assessments',
            AssessmentSubmission::class => 'assessments',
            User::class                 => 'users',
            Admin::class                => 'admins',
            Enrollment::class           => 'enrollments',
            Membership::class           => 'memberships',
            Notification::class         => 'notifications',
            ScholarshipApplication::class => 'scholarships',
            ContactMessage::class       => 'contacts',
        ];

        return $map[get_class($model)] ?? 'system';
    }

    /**
     * Generate human-readable description
     */
    private static function generateDescription(string $action, Model $model): string
    {
        $basename = class_basename($model);
        $identifier = $model->title ?? $model->name ?? $model->email ?? $model->id;
        
        return ucfirst($action) . " {$basename}: {$identifier}";
    }
}