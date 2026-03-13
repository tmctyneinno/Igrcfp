<?php
// database/migrations/2024_01_01_000003_create_assessment_attempts_table.php

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
        Schema::create('assessment_attempts', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('submission_id')->nullable()->constrained('assessment_submissions')->onDelete('cascade');
            
            $table->integer('attempt_number');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['in_progress', 'completed', 'abandoned', 'expired'])->default('in_progress');
            
            $table->json('answers_snapshot')->nullable(); // Auto-save answers
            $table->integer('last_question_index')->default(0);
            $table->json('flagged_questions')->nullable();
            
            $table->timestamps();
            
            $table->index(['assessment_id', 'user_id', 'status']);
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_attempts');
    }
};