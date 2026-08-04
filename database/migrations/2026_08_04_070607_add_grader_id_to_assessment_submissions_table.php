<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_submissions', function (Blueprint $table) {
            // Add grader_id column (nullable in case of auto-grading or legacy data)
            $table->foreignId('grader_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Ensure graded_at exists if it doesn't already
            if (!Schema::hasColumn('assessment_submissions', 'graded_at')) {
                $table->timestamp('graded_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('assessment_submissions', function (Blueprint $table) {
            $table->dropForeign(['grader_id']);
            $table->dropColumn('grader_id');
            $table->dropColumn('graded_at');
        });
    }
};