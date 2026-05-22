<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Fix the timestamps
        DB::statement('ALTER TABLE cart_items MODIFY updated_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE cart_items MODIFY created_at TIMESTAMP NULL DEFAULT NULL');
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // Revert to original if needed (optional)
        DB::statement('ALTER TABLE cart_items MODIFY updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        DB::statement('ALTER TABLE cart_items MODIFY created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    }
};