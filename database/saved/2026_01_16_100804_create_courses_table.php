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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // e.g., CGFCS
            $table->string('short_title');
            $table->text('short_description');
            $table->longText('full_description');
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('video_type')->default('none');
            $table->string('video')->nullable();
            $table->string('video_url')->nullable();
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'expert']);
            $table->enum('format', ['self_paced', 'instructor_led', 'hybrid']);
            $table->string('duration');
            $table->integer('total_modules');
            $table->integer('total_hours');
            $table->string('certification_name');
            $table->string('certifying_body'); // e.g., IGRCFP
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->default(0);
            $table->json('target_audience')->nullable(); // JSON array of target roles
            $table->longText('learning_outcomes')->nullable();
            $table->longText('prerequisites')->nullable();
            $table->longText('career_pathways')->nullable();
            $table->text('assessment_structure')->nullable();
            $table->text('code_of_conduct')->nullable();
            $table->text('programme_overview')->nullable();
            $table->text('programme_architecture')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'is_featured']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
