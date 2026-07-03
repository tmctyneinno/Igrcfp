<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('assessment_questions', 'module_id')) {
                $table->foreignId('module_id')->nullable()->after('assessment_id')
                    ->constrained('course_modules')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assessment_questions', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn('module_id');
        });
    }
};