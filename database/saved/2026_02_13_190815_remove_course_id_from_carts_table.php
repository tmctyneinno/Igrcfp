<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Comment this out since the column doesn't exist
            // if (Schema::hasColumn('carts', 'course_id')) {
            //     $table->dropColumn('course_id');
            // }
            
            // Just add the missing columns
            if (!Schema::hasColumn('carts', 'session_id')) {
                $table->string('session_id')->nullable()->after('user_id');
            }
            
            if (!Schema::hasColumn('carts', 'status')) {
                $table->string('status')->default('active')->after('session_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            if (Schema::hasColumn('carts', 'session_id')) {
                $table->dropColumn('session_id');
            }
            if (Schema::hasColumn('carts', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};