<?php
// database/migrations/2024_01_01_000001_create_assessment_questions_table.php

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
        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->id();
            
            // Relationship
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            
            // Question Content
            $table->text('question_text');
            $table->enum('question_type', [
                'multiple_choice',
                'true_false',
                'multiple_answer',
                'short_answer',
                'essay',
                'case_study',
                'fill_blank',
                'matching'
            ])->default('multiple_choice');
            
            // Options (for multiple choice, matching, etc.)
            $table->json('options')->nullable();
            
            // Correct Answer(s)
            $table->text('correct_answer')->nullable();
            $table->json('correct_answers')->nullable(); // For multiple answer questions
            
            // Scoring
            $table->decimal('points', 8, 2)->default(1.00);
            $table->boolean('is_required')->default(true);
            
            // Media/Attachments
            $table->string('image_url')->nullable();
            $table->text('reference_text')->nullable();
            
            // Metadata
            $table->integer('order')->default(0);
            $table->string('difficulty_level')->default('medium')->comment('easy, medium, hard');
            $table->json('tags')->nullable();
            
            // Explanation (shown after answering)
            $table->text('explanation')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['assessment_id', 'order']);
            $table->index('difficulty_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_questions');
    }
};