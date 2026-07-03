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
            if (!Schema::hasColumn('assessments', 'instructions')) {
                $table->text('instructions')->nullable()->after('project_brief');
            }
            if (!Schema::hasColumn('assessments', 'max_file_size')) {
                $table->integer('max_file_size')->default(50)->after('instructions');
            }
            if (!Schema::hasColumn('assessments', 'allowed_file_types')) {
                $table->json('allowed_file_types')->nullable()->after('max_file_size');
            }
            if (!Schema::hasColumn('assessments', 'allow_late_submissions')) {
                $table->boolean('allow_late_submissions')->default(false)->after('allowed_file_types');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn(['instructions', 'max_file_size', 'allowed_file_types', 'allow_late_submissions']);
        });
    }
};