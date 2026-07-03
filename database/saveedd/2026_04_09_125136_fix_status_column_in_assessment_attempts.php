<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {
            // Drop the enum column and recreate as string
            DB::statement("ALTER TABLE assessment_attempts MODIFY COLUMN status VARCHAR(255) DEFAULT 'not_started'");
        });
    }

    public function down(): void
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {
            DB::statement("ALTER TABLE assessment_attempts MODIFY COLUMN status ENUM('not_started', 'in_progress', 'completed', 'expired') DEFAULT 'not_started'");
        });
    }
};