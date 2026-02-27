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
        Schema::table('carts', function (Blueprint $table) {
            if (!Schema::hasColumn('carts', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->default(0)->after('status');
            }
            
            if (!Schema::hasColumn('carts', 'item_count')) {
                $table->integer('item_count')->default(0)->after('total_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
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