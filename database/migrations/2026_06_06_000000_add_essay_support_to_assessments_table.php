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
        Schema::table('assessments', function (Blueprint $table) {
            if (!Schema::hasColumn('assessments', 'has_essay')) {
                $table->boolean('has_essay')->default(false)->after('question_count');
            }
            if (!Schema::hasColumn('assessments', 'essay_instructions')) {
                $table->text('essay_instructions')->nullable()->after('has_essay');
            }
            if (!Schema::hasColumn('assessments', 'essay_settings')) {
                $table->json('essay_settings')->nullable()->after('essay_instructions')->comment('Additional settings for essay section such as grading guidance');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            if (Schema::hasColumn('assessments', 'essay_settings')) {
                $table->dropColumn('essay_settings');
            }
            if (Schema::hasColumn('assessments', 'essay_instructions')) {
                $table->dropColumn('essay_instructions');
            }
            if (Schema::hasColumn('assessments', 'has_essay')) {
                $table->dropColumn('has_essay');
            }
        });
    }
};
