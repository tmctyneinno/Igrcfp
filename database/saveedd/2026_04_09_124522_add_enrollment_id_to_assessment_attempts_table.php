<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table exists
        if (!Schema::hasTable('assessment_attempts')) {
            Schema::create('assessment_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
                $table->foreignId('enrollment_id')->constrained('enrollments')->onDelete('cascade');
                $table->string('status')->default('not_started');
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('last_activity_at')->nullable();
                $table->json('answers')->nullable();
                $table->integer('score')->default(0);
                $table->integer('earned_marks')->default(0);
                $table->integer('total_marks')->default(0);
                $table->integer('correct_answers')->default(0);
                $table->boolean('passed')->default(false);
                $table->timestamps();
                
                $table->index(['user_id', 'assessment_id', 'enrollment_id']);
                $table->index('status');
            });
        } else {
            // Table exists, add missing columns
            Schema::table('assessment_attempts', function (Blueprint $table) {
                $columns = Schema::getColumnListing('assessment_attempts');
                
                if (!in_array('enrollment_id', $columns)) {
                    $table->foreignId('enrollment_id')->nullable()->after('assessment_id');
                }
                
                if (!in_array('status', $columns)) {
                    $table->string('status')->default('not_started')->after('enrollment_id');
                }
                
                if (!in_array('started_at', $columns)) {
                    $table->timestamp('started_at')->nullable()->after('status');
                }
                
                if (!in_array('completed_at', $columns)) {
                    $table->timestamp('completed_at')->nullable()->after('started_at');
                }
                
                if (!in_array('last_activity_at', $columns)) {
                    $table->timestamp('last_activity_at')->nullable()->after('completed_at');
                }
                
                if (!in_array('answers', $columns)) {
                    $table->json('answers')->nullable()->after('last_activity_at');
                }
                
                if (!in_array('score', $columns)) {
                    $table->integer('score')->default(0)->after('answers');
                }
                
                if (!in_array('earned_marks', $columns)) {
                    $table->integer('earned_marks')->default(0)->after('score');
                }
                
                if (!in_array('total_marks', $columns)) {
                    $table->integer('total_marks')->default(0)->after('earned_marks');
                }
                
                if (!in_array('correct_answers', $columns)) {
                    $table->integer('correct_answers')->default(0)->after('total_marks');
                }
                
                if (!in_array('passed', $columns)) {
                    $table->boolean('passed')->default(false)->after('correct_answers');
                }
            });
            
            // Update enrollment_id for existing records
            $attempts = DB::table('assessment_attempts')
                ->whereNull('enrollment_id')
                ->get();
            
            foreach ($attempts as $attempt) {
                $assessment = DB::table('assessments')->find($attempt->assessment_id);
                
                if ($assessment) {
                    $enrollment = DB::table('enrollments')
                        ->where('user_id', $attempt->user_id)
                        ->where('course_id', $assessment->course_id)
                        ->first();
                    
                    if ($enrollment) {
                        DB::table('assessment_attempts')
                            ->where('id', $attempt->id)
                            ->update(['enrollment_id' => $enrollment->id]);
                    }
                }
            }
            
            // Add foreign key if it doesn't exist
            try {
                Schema::table('assessment_attempts', function (Blueprint $table) {
                    $table->foreign('enrollment_id')
                        ->references('id')
                        ->on('enrollments')
                        ->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_attempts');
    }
};