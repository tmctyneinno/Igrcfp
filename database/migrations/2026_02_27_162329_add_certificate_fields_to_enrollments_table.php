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
        Schema::table('enrollments', function (Blueprint $table) {
            // Add new certificate fields
            $table->boolean('certificate_generated')->default(false)->after('certificate_issued');
            $table->timestamp('certificate_generated_date')->nullable()->after('certificate_generated');
            $table->string('certificate_number')->nullable()->unique()->after('certificate_generated_date');
            $table->string('final_grade')->nullable()->after('certificate_number');
            
            // You might want to keep certificate_issued and certificate_url for backward compatibility
            // Or you can decide to deprecate them later
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_generated',
                'certificate_generated_date',
                'certificate_number',
                'final_grade'
            ]);
        });
    }
};