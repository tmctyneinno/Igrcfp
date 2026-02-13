<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, check if course_id column exists
        $columns = DB::select('DESCRIBE carts');
        $columnNames = array_column($columns, 'Field');
        
        if (in_array('course_id', $columnNames)) {
            // Drop foreign key constraints first if they exist
            try {
                Schema::table('carts', function (Blueprint $table) {
                    $table->dropForeign(['course_id']);
                });
            } catch (\Exception $e) {
                // Foreign key might not exist
            }
            
            // Now drop the column
            Schema::table('carts', function (Blueprint $table) {
                $table->dropColumn('course_id');
            });
        }
        
        // Add missing columns
        if (!in_array('session_id', $columnNames)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->string('session_id')->nullable()->after('user_id');
            });
        }
        
        if (!in_array('status', $columnNames)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->string('status')->default('active')->after('session_id');
            });
        }
        
        if (!in_array('total_amount', $columnNames)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->decimal('total_amount', 10, 2)->default(0)->after('status');
            });
        }
        
        if (!in_array('item_count', $columnNames)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->integer('item_count')->default(0)->after('total_amount');
            });
        }
    }

    public function down(): void
    {
        // Not needed
    }
};