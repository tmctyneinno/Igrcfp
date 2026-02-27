<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get current columns
        $columns = DB::select('DESCRIBE carts');
        $columnNames = array_column($columns, 'Field');
        
        // Add total_amount if missing
        if (!in_array('total_amount', $columnNames)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->decimal('total_amount', 10, 2)->default(0);
            });
            echo "Added total_amount column\n";
        } else {
            echo "total_amount column already exists\n";
        }
        
        // Add item_count if missing
        if (!in_array('item_count', $columnNames)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->integer('item_count')->default(0);
            });
            echo "Added item_count column\n";
        } else {
            echo "item_count column already exists\n";
        }
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            if (Schema::hasColumn('carts', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if (Schema::hasColumn('carts', 'item_count')) {
                $table->dropColumn('item_count');
            }
        });
    }
};