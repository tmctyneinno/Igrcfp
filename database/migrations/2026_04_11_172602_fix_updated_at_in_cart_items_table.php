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
            DB::statement('ALTER TABLE cart_items 
                MODIFY updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
                ON UPDATE CURRENT_TIMESTAMP');
        }

    public function down(): void
        {
            DB::statement("ALTER TABLE cart_items 
                MODIFY updated_at TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
        }
};
