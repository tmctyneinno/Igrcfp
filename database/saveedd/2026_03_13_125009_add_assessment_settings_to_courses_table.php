<?php
// database/migrations/2024_01_01_000004_add_assessment_settings_to_courses_table.php

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
        Schema::table('courses', function (Blueprint $table) {
            $table->json('assessment_settings')->nullable()->after('sort_order')->comment('Default settings for course assessments');
            $table->boolean('has_final_exam')->default(false)->after('assessment_settings');
            $table->boolean('has_diploma_assessment')->default(false)->after('has_final_exam');
            $table->integer('quiz_count')->default(0)->after('has_diploma_assessment');
            $table->integer('module_assessment_count')->default(0)->after('quiz_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'assessment_settings',
                'has_final_exam',
                'has_diploma_assessment',
                'quiz_count',
                'module_assessment_count'
            ]);
        });
    }
};