<?php
// database/migrations/xxxx_xx_xx_create_assessments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['exam', 'assignment', 'quiz', 'project']);
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->integer('duration')->nullable(); // in minutes
            $table->integer('total_marks')->nullable();
            $table->integer('weight')->nullable(); // percentage of final grade
            $table->timestamp('due_date')->nullable();
            $table->timestamp('release_date')->nullable();
            $table->boolean('is_timed')->default(true);
            $table->boolean('needs_manual_marking')->default(false);
            $table->boolean('allow_late_submissions')->default(false);
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->integer('submissions_count')->default(0);
            $table->integer('pending_grading_count')->default(0);
            $table->timestamps();
        });

       
    }

    public function down()
    {
        Schema::dropIfExists('assessment_submissions');
        Schema::dropIfExists('assessments');
    }
};