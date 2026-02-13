<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, let's see what columns we have
        $columns = DB::select('DESCRIBE carts');
        $columnNames = array_column($columns, 'Field');
        
        // Only try to drop course_id if it exists
        if (in_array('course_id', $columnNames)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropColumn('course_id');
            });
        }
        
        // Add session_id if it doesn't exist
        if (!in_array('session_id', $columnNames)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->string('session_id')->nullable()->after('user_id');
            });
        }
        
        // Make sure status column exists with correct default
        if (!in_array('status', $columnNames)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->string('status')->default('active')->after('session_id');
            });
        }
    }

    public function down(): void
    {
        // This migration is safe to not revert
    }
};