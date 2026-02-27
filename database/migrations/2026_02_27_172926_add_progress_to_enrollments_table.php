<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_certificate_and_progress_fields_to_enrollments_table.php

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
        Schema::table('enrollments', function (Blueprint $table) {
            // Add progress field
            $table->integer('progress')->default(0)->after('status');
            
            // Add certificate fields (if they don't exist yet)
            if (!Schema::hasColumn('enrollments', 'certificate_generated')) {
                $table->boolean('certificate_generated')->default(false)->after('progress');
                $table->timestamp('certificate_generated_date')->nullable()->after('certificate_generated');
                $table->string('certificate_number')->nullable()->unique()->after('certificate_generated_date');
                $table->string('final_grade')->nullable()->after('certificate_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn([
                'progress',
                'certificate_generated',
                'certificate_generated_date',
                'certificate_number',
                'final_grade'
            ]);
        });
    }
};