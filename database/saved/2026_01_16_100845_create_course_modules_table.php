<?php

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
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('code')->nullable(); // e.g., MOD1
            $table->integer('module_number');
            $table->text('short_description');
            $table->longText('full_content');
            $table->text('learning_objectives')->nullable();
            $table->text('key_concepts')->nullable();
            $table->text('topics_covered')->nullable();
            $table->text('case_study')->nullable();
            $table->text('exercise')->nullable();
            $table->text('additional_notes')->nullable();
            $table->integer('estimated_hours');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['course_id', 'module_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_modules');
    }
};
