<?php

// app/Models/Enrollment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ActivityLoggerService;
use App\Models\ActivityLog;

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
        'certificate_generated',
        'certificate_generated_date',
        'certificate_number',
        'certificate_status',
        'certificate_status_updated_at',
        'certificate_status_updated_by',
        'certificate_revocation_reason',
        'certificate_verified',
        'final_grade',
        'notes',
        'progress',
    ];

    protected $casts = [
        'enrollment_date'                => 'datetime',
        'completed_at'                   => 'datetime',
        'certificate_issued'             => 'boolean',
        'certificate_generated'          => 'boolean',
        'certificate_generated_date'     => 'datetime',
        'certificate_status_updated_at'  => 'datetime',
        'certificate_verified'           => 'boolean',
        'amount'                         => 'decimal:2',
    ];

    // ─── Certificate Status Constants ─────────────────────────────────
    const CERT_STATUS_ACTIVE    = 'active';
    const CERT_STATUS_REVOKED   = 'revoked';
    const CERT_STATUS_EXPIRED   = 'expired';
    const CERT_STATUS_SUSPENDED = 'suspended';
    const CERT_STATUS_PENDING   = 'pending';

    // ─── Relationships ────────────────────────────────────────────────
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

    /**
     * Get the admin who updated the certificate status
     */
    public function certificateStatusUpdatedBy()
    {
        return $this->belongsTo(Admin::class, 'certificate_status_updated_by');
    }

    /**
     * Get activity logs related to this enrollment's certificate
     */
    public function certificateActivityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject')
            ->where('module', 'certificates')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get all assessment submissions for this enrollment
     */
    public function assessmentSubmissions()
    {
        return $this->hasMany(AssessmentSubmission::class, 'enrollment_id');
    }

    /**
     * Get graded submissions only
     */
    public function gradedSubmissions()
    {
        return $this->assessmentSubmissions()->where('status', 'graded');
    }

    /**
     * Get passed submissions
     */
    public function passedSubmissions()
    {
        return $this->assessmentSubmissions()->where('passed', true);
    }

    // ─── Scopes ──────────────────────────────────────────────────────
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

    /**
     * Scope for certificates pending generation
     */
    public function scopePendingCertificate($query)
    {
        return $query->where('certificate_generated', false)
            ->where('status', 'completed');
    }

    /**
     * Scope for generated certificates
     */
    public function scopeHasCertificate($query)
    {
        return $query->where('certificate_generated', true);
    }

    /**
     * Scope for active certificates
     */
    public function scopeActiveCertificate($query)
    {
        return $query->where('certificate_generated', true)
            ->where('certificate_status', self::CERT_STATUS_ACTIVE);
    }

    /**
     * Scope for revoked certificates
     */
    public function scopeRevokedCertificate($query)
    {
        return $query->where('certificate_status', self::CERT_STATUS_REVOKED);
    }

    /**
     * Scope for verified certificates
     */
    public function scopeVerified($query)
    {
        return $query->where('certificate_verified', true);
    }

    /**
     * Scope for certificates by status
     */
    public function scopeByCertificateStatus($query, $status)
    {
        return $query->where('certificate_status', $status);
    }

    /**
     * Scope for search by student or certificate
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('certificate_number', 'like', "%{$search}%")
              ->orWhereHas('user', function($userQuery) use ($search) {
                  $userQuery->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
              })
              ->orWhereHas('course', function($courseQuery) use ($search) {
                  $courseQuery->where('title', 'like', "%{$search}%");
              });
        });
    }

    // ─── Certificate Helper Methods ──────────────────────────────────
    
    /**
     * Check if certificate is available (generated and active)
     */
    public function hasCertificate(): bool
    {
        return $this->certificate_generated 
            && $this->certificate_number 
            && $this->certificate_status === self::CERT_STATUS_ACTIVE;
    }

    /**
     * Check if certificate is active
     */
    public function isCertificateActive(): bool
    {
        return $this->certificate_generated 
            && $this->certificate_status === self::CERT_STATUS_ACTIVE;
    }

    /**
     * Check if certificate is revoked
     */
    public function isCertificateRevoked(): bool
    {
        return $this->certificate_status === self::CERT_STATUS_REVOKED;
    }

    /**
     * Check if certificate is expired
     */
    public function isCertificateExpired(): bool
    {
        return $this->certificate_status === self::CERT_STATUS_EXPIRED;
    }

    /**
     * Get certificate status badge class
     */
    public function getCertificateStatusBadgeClassAttribute(): string
    {
        return match($this->certificate_status) {
            self::CERT_STATUS_ACTIVE    => 'badge bg-success',
            self::CERT_STATUS_REVOKED   => 'badge bg-danger',
            self::CERT_STATUS_EXPIRED   => 'badge bg-warning text-dark',
            self::CERT_STATUS_SUSPENDED => 'badge bg-secondary',
            self::CERT_STATUS_PENDING   => 'badge bg-info',
            default                     => 'badge bg-light text-dark',
        };
    }

    /**
     * Get certificate status display name
     */
    public function getCertificateStatusDisplayAttribute(): string
    {
        return ucfirst($this->certificate_status ?? self::CERT_STATUS_PENDING);
    }

    /**
     * Get certificate status icon
     */
    public function getCertificateStatusIconAttribute(): string
    {
        return match($this->certificate_status) {
            self::CERT_STATUS_ACTIVE    => 'fa-check-circle text-success',
            self::CERT_STATUS_REVOKED   => 'fa-times-circle text-danger',
            self::CERT_STATUS_EXPIRED   => 'fa-clock text-warning',
            self::CERT_STATUS_SUSPENDED => 'fa-pause-circle text-secondary',
            self::CERT_STATUS_PENDING   => 'fa-hourglass-half text-info',
            default                     => 'fa-question-circle',
        };
    }

    /**
     * Generate a unique certificate number
     */
    public function generateCertificateNumber(): string
    {
        $number = 'IGRCFP-CERT-' . date('Y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        
        $this->update(['certificate_number' => $number]);
        
        return $number;
    }

    /**
     * Generate certificate for this enrollment
     */
    public function generateCertificate(?string $grade = null, ?string $adminNotes = null, ?int $adminId = null): self
    {
        if (!$this->certificate_number) {
            $this->generateCertificateNumber();
        }

        // Use calculated grade if not specified
        $finalGrade = $grade ?? $this->calculateFinalGrade();

        $this->update([
            'certificate_generated'      => true,
            'certificate_generated_date' => now(),
            'certificate_status'         => self::CERT_STATUS_ACTIVE,
            'final_grade'                => $finalGrade,
            'status'                     => 'completed',
            'completed_at'               => $this->completed_at ?? now(),
            'progress'                   => 100,
            'notes'                      => $adminNotes 
                ? $this->notes . "\n[Certificate Generated - " . now()->format('Y-m-d H:i') . "]: " . $adminNotes 
                : $this->notes,
        ]);

        return $this;
    }

    /**
     * Update certificate status
     */
    public function updateCertificateStatus(string $status, ?string $reason = null, ?int $adminId = null): self
    {
        $oldStatus = $this->certificate_status;

        $this->update([
            'certificate_status'             => $status,
            'certificate_status_updated_at'  => now(),
            'certificate_status_updated_by'  => $adminId,
            'certificate_revocation_reason'  => $status === self::CERT_STATUS_REVOKED ? $reason : null,
        ]);

        return $this;
    }

    /**
     * Revoke certificate
     */
    public function revokeCertificate(string $reason, ?int $adminId = null): self
    {
        return $this->updateCertificateStatus(self::CERT_STATUS_REVOKED, $reason, $adminId);
    }

    /**
     * Reactivate certificate
     */
    public function reactivateCertificate(?int $adminId = null): self
    {
        return $this->updateCertificateStatus(self::CERT_STATUS_ACTIVE, null, $adminId);
    }

    /**
     * Mark certificate as verified
     */
    public function markAsVerified(): self
    {
        $this->update(['certificate_verified' => true]);
        return $this;
    }

    /**
     * Get certificate verification URL
     */
    public function getVerificationUrlAttribute(): string
    {
        return route('certificate.verify.show', $this->certificate_number);
    }

   
    /**
     * Get all available certificate statuses
     */
    public static function getCertificateStatuses(): array
    {
        return [
            self::CERT_STATUS_ACTIVE    => 'Active',
            self::CERT_STATUS_REVOKED   => 'Revoked',
            self::CERT_STATUS_EXPIRED   => 'Expired',
            self::CERT_STATUS_SUSPENDED => 'Suspended',
            self::CERT_STATUS_PENDING   => 'Pending',
        ];
    }

    // ─── Grade Calculation Methods ───────────────────────────────────

    /**
     * Calculate final grade from assessment submissions
     */
    public function calculateFinalGrade(): string
    {
        $submissions = AssessmentSubmission::where('enrollment_id', $this->id)
            ->where('status', 'graded')
            ->get();

        if ($submissions->isEmpty()) {
            return 'Pass';
        }

        // Calculate average percentage across all submissions
        $averagePercentage = $submissions->avg('percentage');
        
        return $this->determineGrade($averagePercentage);
    }

    /**
     * Determine grade based on percentage
     */
    private function determineGrade(float $percentage): string
    {
        if ($percentage >= 90) {
            return 'Distinction';
        } elseif ($percentage >= 75) {
            return 'Merit';
        } elseif ($percentage >= 60) {
            return 'Pass';
        } elseif ($percentage >= 40) {
            return 'Referral';
        } else {
            return 'Fail';
        }
    }

    /**
     * Get detailed grade breakdown
     */
    public function getGradeBreakdownAttribute(): array
    {
        $submissions = AssessmentSubmission::where('enrollment_id', $this->id)
            ->where('status', 'graded')
            ->with('assessment')
            ->get();

        $breakdown = [
            'submissions'        => [],
            'overall_percentage' => 0,
            'overall_grade'      => 'N/A',
            'total_submissions'  => 0,
            'graded_submissions' => 0,
            'passed_submissions' => 0,
        ];

        if ($submissions->isEmpty()) {
            return $breakdown;
        }

        $totalPercentage = 0;
        $gradedCount = 0;
        $passedCount = 0;

        foreach ($submissions as $submission) {
            $breakdown['submissions'][] = [
                'assessment_name' => $submission->assessment->title ?? 'Unknown Assessment',
                'assessment_type' => $submission->assessment->type ?? 'N/A',
                'assessment_level' => $submission->assessment->assessment_level ?? 'N/A',
                'score'           => $submission->score,
                'percentage'      => $submission->percentage,
                'passed'          => $submission->passed,
                'graded_at'       => $submission->graded_at?->format('M d, Y'),
                'feedback'        => $submission->feedback,
            ];

            $totalPercentage += $submission->percentage;
            $gradedCount++;
            
            if ($submission->passed) {
                $passedCount++;
            }
        }

        $averagePercentage = $gradedCount > 0 ? $totalPercentage / $gradedCount : 0;

        $breakdown['overall_percentage'] = round($averagePercentage, 2);
        $breakdown['overall_grade'] = $this->determineGrade($averagePercentage);
        $breakdown['total_submissions'] = AssessmentSubmission::where('enrollment_id', $this->id)->count();
        $breakdown['graded_submissions'] = $gradedCount;
        $breakdown['passed_submissions'] = $passedCount;

        return $breakdown;
    }

    /**
     * Check if all assessments are completed and passed
     */
    public function hasCompletedAllAssessments(): bool
    {
        $totalAssessments = Assessment::where('course_id', $this->course_id)
            ->where('status', 'active')
            ->count();

        $passedAssessments = $this->passedSubmissions()->count();

        return $totalAssessments > 0 && $passedAssessments >= $totalAssessments;
    }

    /**
     * Auto-generate certificate when all assessments are passed
     */
    public function autoGenerateCertificateIfEligible(): bool
    {
        if ($this->hasCompletedAllAssessments() && !$this->certificate_generated) {
            $grade = $this->calculateFinalGrade();
            
            $this->update([
                'certificate_generated'      => true,
                'certificate_generated_date' => now(),
                'certificate_status'         => self::CERT_STATUS_ACTIVE,
                'final_grade'                => $grade,
                'status'                     => 'completed',
                'completed_at'               => $this->completed_at ?? now(),
                'progress'                   => 100,
            ]);

            if (!$this->certificate_number) {
                $this->generateCertificateNumber();
            }

            // Log auto-generation if ActivityLoggerService exists
            if (class_exists(ActivityLoggerService::class)) {
                ActivityLoggerService::log(
                    ActivityLog::EVENT_CREATED,
                    'certificates',
                    'Certificate auto-generated',
                    "Certificate auto-generated for {$this->user->name} - {$this->course->title}",
                    $this,
                    [
                        'final_grade' => $grade,
                        'average_percentage' => $this->gradedSubmissions()->avg('percentage'),
                        'total_submissions' => $this->gradedSubmissions()->count(),
                    ],
                    ActivityLog::SEVERITY_INFO
                );
            }

            return true;
        }

        return false;
    }

    /**
     * Get overall progress percentage
     */
    public function getOverallProgressAttribute(): float
    {
        return (float) $this->progress;
    }

    /**
     * Get grade color class
     */
    public function getGradeColorClassAttribute(): string
    {
        return match($this->final_grade) {
            'Distinction' => 'text-success',
            'Merit'       => 'text-primary',
            'Pass'        => 'text-info',
            'Referral'    => 'text-warning',
            'Fail'        => 'text-danger',
            default       => 'text-secondary',
        };
    }

    /**
     * Get grade badge class
     */
    public function getGradeBadgeClassAttribute(): string
    {
        return match($this->final_grade) {
            'Distinction' => 'badge bg-success',
            'Merit'       => 'badge bg-primary',
            'Pass'        => 'badge bg-info',
            'Referral'    => 'badge bg-warning text-dark',
            'Fail'        => 'badge bg-danger',
            default       => 'badge bg-secondary',
        };
    }

    // ─── Existing Methods ────────────────────────────────────────────

    public function completeLesson(Lesson $lesson, int $timeSpent = null, array $metadata = []): LessonUser
    {
        $lessonUser = LessonUser::updateOrCreate(
            [
                'user_id' => $this->user_id,
                'lesson_id' => $lesson->id,
                'enrollment_id' => $this->id
            ],
            [
                'completed' => true,
                'completed_at' => now(),
                'time_spent' => $timeSpent,
                'metadata' => $metadata
            ]
        );
        
        // Update progress
        $this->updateProgress();
        
        return $lessonUser;
    }

    public function lessonCompletions()
    {
        return $this->hasMany(LessonUser::class);
    }

    public function moduleReadingProgress()
    {
        return $this->hasMany(CourseModuleUser::class);
    }

    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user')
            ->wherePivot('completed', true)
            ->withPivot('completed_at', 'time_spent', 'attempts', 'metadata')
            ->withTimestamps();
    }

    public function isCompleted(): bool
    {
        return $this->progress === 100;
    }

    public function updateProgress(): int
    {
        // Get total lessons in this course through modules
        $totalLessons = 0;
        foreach ($this->course->modules as $module) {
            $totalLessons += $module->lessons()->count();
        }
        
        if ($totalLessons === 0) {
            return 0;
        }
        
        // Get completed lessons count
        $completedLessons = LessonUser::where('enrollment_id', $this->id)
            ->where('completed', true)
            ->count();
        
        // Calculate percentage
        $progress = (int) round(($completedLessons / $totalLessons) * 100);
        
        // Update enrollment
        $this->update(['progress' => $progress]);
        
        // Auto-complete enrollment if progress is 100%
        if ($progress === 100 && $this->status !== 'completed') {
            $this->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
        }
        
        return $progress;
    }

    /**
     * Boot the model
     */
    protected static function booted()
    {
        // Set default certificate status when creating
        static::creating(function ($enrollment) {
            if (empty($enrollment->certificate_status)) {
                $enrollment->certificate_status = self::CERT_STATUS_PENDING;
            }
        });

        // Auto-update certificate status when enrollment is cancelled
        static::updating(function ($enrollment) {
            if ($enrollment->isDirty('status') && $enrollment->status === 'cancelled') {
                if ($enrollment->certificate_generated && $enrollment->certificate_status === self::CERT_STATUS_ACTIVE) {
                    $enrollment->certificate_status = self::CERT_STATUS_REVOKED;
                    $enrollment->certificate_revocation_reason = 'Enrollment cancelled';
                    $enrollment->certificate_status_updated_at = now();
                }
            }
        });
    }

    public function getDisplayGradeAttribute(): string
    {
        // First check if final_grade is stored
        if (!empty($this->final_grade)) {
            return $this->final_grade;
        }

        // Otherwise calculate from submissions
        return $this->calculateFinalGrade();
    }

   

    
}