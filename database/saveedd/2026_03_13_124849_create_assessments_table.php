<?php
// database/migrations/2024_01_01_000000_create_assessments_table.php

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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->nullable()->constrained('course_modules')->onDelete('cascade');
            
            // Basic Information
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['exam', 'assignment', 'quiz', 'project'])->default('quiz');
            $table->enum('assessment_level', [
                'quiz',              // Quick checks after modules (5-10 questions)
                'module_assessment', // End-of-module tests (20-30 questions)
                'final_exam',        // Course culmination (50+ questions)
                'diploma'            // Case studies/projects for diploma
            ])->default('quiz');
            
            // Status and Availability
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->timestamp('release_date')->nullable();
            $table->timestamp('due_date')->nullable();
            
            // Scoring and Grading
            $table->integer('total_marks')->nullable();
            $table->integer('passing_score')->nullable()->comment('Minimum percentage required to pass');
            $table->integer('weight')->nullable()->comment('Percentage of final grade');
            
            // Timing Settings
            $table->integer('duration')->nullable()->comment('In minutes');
            $table->boolean('is_timed')->default(false);
            
            // Security Settings
            $table->boolean('requires_identity_verification')->default(false);
            $table->boolean('needs_manual_marking')->default(false);
            $table->boolean('allow_late_submissions')->default(false);
            
            // Question Bank (for quizzes and exams)
            $table->integer('question_count')->default(0);
            $table->json('question_bank_settings')->nullable()->comment('Randomization, question pool settings');
            
            // File Upload (for assignments, projects, briefs)
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('file_extension')->nullable();
            
            // Project/Case Study Specific (for diploma)
            $table->text('project_brief')->nullable();
            $table->json('rubric')->nullable()->comment('Grading criteria for manual marking');
            $table->json('deliverables')->nullable()->comment('Expected deliverables for projects');
            
            // Additional Settings (JSON for flexibility)
            $table->json('settings')->nullable()->comment('Type-specific settings');
            
            // Statistics (cached for performance)
            $table->integer('attempts_count')->default(0);
            $table->integer('submissions_count')->default(0);
            $table->integer('pending_grading_count')->default(0);
            $table->decimal('average_score', 5, 2)->nullable();
            $table->decimal('highest_score', 5, 2)->nullable();
            $table->decimal('lowest_score', 5, 2)->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // Allow recovery of accidentally deleted assessments
            
            // Indexes for better performance
            $table->index(['course_id', 'assessment_level', 'status']);
            $table->index(['due_date', 'status']);
            $table->index('release_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};