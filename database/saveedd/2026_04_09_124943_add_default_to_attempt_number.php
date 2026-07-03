<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {
            // Set default value for existing column
            DB::statement('ALTER TABLE assessment_attempts MODIFY attempt_number INT NOT NULL DEFAULT 1');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {
            DB::statement('ALTER TABLE assessment_attempts MODIFY attempt_number INT NOT NULL');
        });
    }
};