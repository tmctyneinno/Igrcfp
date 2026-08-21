<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_submissions', function (Blueprint $table) {
            $table->string('examiner_report_path')->nullable()->after('feedback');
            $table->string('examiner_report_name')->nullable()->after('examiner_report_path');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_submissions', function (Blueprint $table) {
            $table->dropColumn(['examiner_report_path', 'examiner_report_name']);
        });
    }
};
