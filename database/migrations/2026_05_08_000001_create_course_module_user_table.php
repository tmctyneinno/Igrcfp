<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_module_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_module_id')->constrained('course_modules')->onDelete('cascade');
            $table->unsignedTinyInteger('reading_progress')->default(0);
            $table->boolean('read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('last_viewed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'enrollment_id', 'course_module_id'], 'course_module_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_module_user');
    }
};
