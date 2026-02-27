<?php
// database/migrations/2024_01_01_000001_create_lessons_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->text('short_description')->nullable();
            $table->longText('content')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_embed_code')->nullable();
            $table->integer('duration')->nullable(); // in minutes
            $table->integer('sort_order')->default(0); // ADD THIS LINE
            $table->boolean('is_free')->default(false);
            $table->boolean('is_published')->default(true);
            $table->json('attachments')->nullable();
            $table->json('resources')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['module_id', 'sort_order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lessons');
    }
};