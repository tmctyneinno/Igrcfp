<?php
// app/Services/ActivityLoggerService.php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Admin;
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

        // Ensure action is never empty
        if (empty($action)) {
            $action = self::generateAction($event, $module, $subject);
        }

        return ActivityLog::create([
            'loggable_type' => get_class($loggable),
            'loggable_id'   => $loggable->id ?? 0,
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

    /**
     * Generate a default action based on event and module
     */
    private static function generateAction(string $event, ?string $module, ?Model $subject): string
    {
        $eventLabel = ucwords(str_replace('_', ' ', $event));
        $moduleLabel = $module ? ' in ' . ucfirst($module) : '';
        $subjectLabel = '';

        if ($subject) {
            $subjectName = $subject->title 
                ?? $subject->name 
                ?? $subject->email 
                ?? class_basename($subject);
            $subjectLabel = ' - ' . $subjectName;
        }

        return $eventLabel . $moduleLabel . $subjectLabel;
    }

    /**
     * Log model creation
     */
    public static function created(Model $model, ?string $module = null): ActivityLog
    {
        $module = $module ?? self::resolveModule($model);
        $modelName = self::getModelDisplayName($model);
        
        return self::log(
            ActivityLog::EVENT_CREATED,
            $module,
            "Created {$modelName}",
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
        $modelName = self::getModelDisplayName($model);
        $changedFields = array_keys($changes);
        
        return self::log(
            ActivityLog::EVENT_UPDATED,
            $module,
            "Updated {$modelName}",
            self::generateDescription('updated', $model),
            $model,
            [
                'changed_fields' => $changedFields,
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
        $modelName = self::getModelDisplayName($model);
        
        return self::log(
            ActivityLog::EVENT_DELETED,
            $module,
            "Deleted {$modelName}",
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
        $userType = $user instanceof Admin ? 'Admin' : 'User';
        
        return self::log(
            ActivityLog::EVENT_LOGIN,
            'authentication',
            "{$userType} Login Successful",
            "{$userType} {$user->email} logged in successfully",
            null,
            ['email' => $user->email, 'user_type' => $userType]
        );
    }

    /**
     * Log failed login attempt
     */
    public static function loginFailed(?string $email = null, ?Model $user = null): ActivityLog
    {
        $loggable = $user ?? self::resolveLoggable();
        
        return ActivityLog::create([
            'loggable_type' => get_class($loggable),
            'loggable_id'   => $loggable->id ?? 0,
            'event'         => ActivityLog::EVENT_LOGIN_FAILED,
            'module'        => 'authentication',
            'action'        => 'Failed Login Attempt',
            'description'   => $email ? "Failed login attempt for: {$email}" : 'Failed login attempt',
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
        $userType = $user instanceof Admin ? 'Admin' : 'User';
        
        return self::log(
            ActivityLog::EVENT_LOGOUT,
            'authentication',
            "{$userType} Logout",
            "{$userType} {$user->email} logged out",
            null,
            ['email' => $user->email, 'user_type' => $userType]
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
        return new User([
            'id' => 0, 
            'name' => 'System', 
            'email' => 'system@igrcfp.com'
        ]);
    }

    /**
     * Resolve module name from model
     */
    private static function resolveModule(Model $model): string
    {
        $basename = class_basename($model);
        
        $map = [
            'Course'                => 'courses',
            'Article'               => 'articles',
            'Blog'                  => 'blogs',
            'Event'                 => 'events',
            'Assessment'            => 'assessments',
            'AssessmentQuestion'    => 'assessments',
            'AssessmentSubmission'  => 'assessments',
            'AssessmentAttempt'     => 'assessments',
            'User'                  => 'users',
            'Admin'                 => 'admins',
            'Enrollment'            => 'enrollments',
            'Membership'            => 'memberships',
            'Notification'          => 'notifications',
            'ScholarshipApplication'=> 'scholarships',
            'ContactMessage'        => 'contacts',
            'Mentorship'            => 'mentorships',
        ];

        return $map[$basename] ?? 'system';
    }

    /**
     * Get display name for model
     */
    private static function getModelDisplayName(Model $model): string
    {
        $basename = class_basename($model);
        $identifier = $model->title 
            ?? $model->name 
            ?? $model->email 
            ?? '#' . $model->id;
        
        return "{$basename} \"{$identifier}\"";
    }

    /**
     * Generate human-readable description
     */
    private static function generateDescription(string $action, Model $model): string
    {
        $basename = class_basename($model);
        $identifier = $model->title 
            ?? $model->name 
            ?? $model->email 
            ?? $model->id;
        
        return ucfirst($action) . " {$basename}: {$identifier}";
    }
}