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
        'external_user_id',
        'client',
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
        'progress_percentage',
        'last_activity_at',
    ];

    protected $casts = [
        'enrollment_date' => 'datetime',
        'completed_at' => 'datetime',
        'certificate_issued' => 'boolean',
        'certificate_generated' => 'boolean',  // ADD THIS
        'certificate_generated_date' => 'datetime',  // ADD THIS
        'amount' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'last_activity_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function externalUser()
    {
        return $this->belongsTo(ExternalUser::class, 'external_user_id', 'external_user_id');
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

}
