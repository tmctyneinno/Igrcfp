<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create the pivot table
        Schema::create('assessment_modules_pivot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')
                  ->constrained('assessments')
                  ->cascadeOnDelete();
            $table->foreignId('module_id')
                  ->constrained('course_modules')
                  ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['assessment_id', 'module_id']);
        });

        // 2. Migrate existing module_id values into the pivot table
        DB::statement('
            INSERT INTO assessment_modules_pivot (assessment_id, module_id, created_at, updated_at)
            SELECT id, module_id, NOW(), NOW()
            FROM assessments
            WHERE module_id IS NOT NULL
        ');

        // 3. Drop the old module_id column
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropForeign('assessments_module_id_foreign');
            $table->dropColumn('module_id');
        });
    }

    public function down(): void
    {
        // 1. Re-add module_id to assessments
        Schema::table('assessments', function (Blueprint $table) {
            $table->foreignId('module_id')
                  ->nullable()
                  ->after('course_id')
                  ->constrained('course_modules')
                  ->nullOnDelete();
        });

        // 2. Restore first linked module per assessment back into module_id
        DB::statement('
            UPDATE assessments a
            JOIN (
                SELECT assessment_id, MIN(module_id) AS module_id
                FROM assessment_modules_pivot
                GROUP BY assessment_id
            ) am ON a.id = am.assessment_id
            SET a.module_id = am.module_id
        ');

        // 3. Drop the pivot table
        Schema::dropIfExists('assessment_modules_pivot');
    }
};