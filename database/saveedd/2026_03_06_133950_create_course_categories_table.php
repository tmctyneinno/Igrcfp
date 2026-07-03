<?php
// database/migrations/2024_01_01_000000_create_course_categories_table.php

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
        // Create course_categories table
        Schema::create('course_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add category_id to courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('category_id')
                  ->nullable()
                  ->after('id') // You can adjust the position
                  ->constrained('course_categories')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First remove the foreign key and column from courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        // Then drop the course_categories table
        Schema::dropIfExists('course_categories');
    }
};