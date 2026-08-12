<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->unsignedTinyInteger('quiz_failed_attempts')->default(0)->after('progress');
            $table->timestamp('quiz_locked_until')->nullable()->after('quiz_failed_attempts');
            $table->boolean('quiz_permanently_locked')->default(false)->after('quiz_locked_until');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn([
                'quiz_failed_attempts',
                'quiz_locked_until',
                'quiz_permanently_locked',
            ]);
        });
    }
};
