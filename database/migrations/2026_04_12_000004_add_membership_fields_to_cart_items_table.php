<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('item_type')->default('course')->after('cart_id');
            $table->foreignId('membership_plan_id')->nullable()->after('course_id')->constrained('membership_plans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['membership_plan_id']);
            $table->dropColumn(['item_type', 'membership_plan_id']);
        });
    }
};
