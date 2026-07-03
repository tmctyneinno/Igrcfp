<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_modules_table.php

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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->integer('module_number');
            $table->string('code')->nullable();
            $table->text('short_description')->nullable();
            $table->integer('estimated_hours')->default(0);
            $table->integer('lesson_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for faster queries
            $table->index('course_id');
            $table->index('module_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};