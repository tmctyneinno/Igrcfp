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
        Schema::table('scholarship_courses', function (Blueprint $table) {
            if (!Schema::hasColumn('scholarship_courses', 'assigned_by')) {
                $table->foreignId('assigned_by')->nullable()->after('course_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('scholarship_courses', 'assigned_at')) {
                $table->timestamp('assigned_at')->useCurrent()->after('assigned_by');
            }
            if (!Schema::hasColumn('scholarship_courses', 'activated_at')) {
                $table->timestamp('activated_at')->nullable()->after('assigned_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholarship_courses', function (Blueprint $table) {
            //
        });
    }
};
