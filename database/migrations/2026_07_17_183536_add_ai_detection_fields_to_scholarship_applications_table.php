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
        Schema::table('scholarship_applications', function (Blueprint $table) {
            // Add AI detection score (0.0000 to 1.0000)
            $table->decimal('ai_detection_score', 5, 4)->nullable()->after('personal_statement');
            
            // Add timestamp when AI check was performed
            $table->timestamp('ai_checked_at')->nullable()->after('ai_detection_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholarship_applications', function (Blueprint $table) {
            $table->dropColumn(['ai_detection_score', 'ai_checked_at']);
        });
    }
};
