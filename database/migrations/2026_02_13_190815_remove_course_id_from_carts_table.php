<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Check if course_id column exists before trying to drop it
            if (Schema::hasColumn('carts', 'course_id')) {
                $table->dropColumn('course_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Only add the column back if it doesn't exist
            if (!Schema::hasColumn('carts', 'course_id')) {
                $table->foreignId('course_id')->nullable()->constrained();
            }
        });
    }
};