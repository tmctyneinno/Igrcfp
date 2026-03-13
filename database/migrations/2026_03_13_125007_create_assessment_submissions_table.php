<?php
// database/migrations/2024_01_01_000002_create_assessment_submissions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessment_submissions', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_id')->nullable()->constrained()->onDelete('cascade');
            
            // Submission Details
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->enum('status', [
                'not_started',
                'in_progress',
                'submitted',
                'graded',
                'late',
                'expired'
            ])->default('not_started');
            
            // Answers
            $table->json('answers')->nullable();
            $table->json('question_responses')->nullable(); // Track per-question responses
            
            // Scoring
            $table->decimal('score', 5, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->boolean('passed')->nullable();
            
            // File Uploads (for assignments/projects)
            $table->string('submission_file_path')->nullable();
            $table->string('submission_file_name')->nullable();
            $table->bigInteger('submission_file_size')->nullable();
            
            // Security & Verification
            $table->json('identity_verification_data')->nullable();
            $table->json('verification_images')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            // Plagiarism Check
            $table->decimal('plagiarism_score', 5, 2)->nullable();
            $table->json('plagiarism_report')->nullable();
            $table->boolean('flagged_for_review')->default(false);
            
            // Grading (for manual marking)
            $table->text('feedback')->nullable();
            $table->json('grader_comments')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users');
            
            // Time Tracking
            $table->integer('time_spent')->nullable()->comment('In seconds');
            $table->integer('remaining_time')->nullable();
            
            // Attempt Tracking
            $table->integer('attempt_number')->default(1);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['assessment_id', 'user_id', 'status']);
            $table->index(['status', 'submitted_at']);
            $table->index('flagged_for_review');
            $table->index('passed');
            
            // Ensure one active attempt per user per assessment
            $table->unique(['assessment_id', 'user_id', 'attempt_number'], 'unique_assessment_attempt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_submissions');
    }
};