<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tier_id')->constrained('membership_tiers')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 10)->default('EUR');
            $table->string('billing_interval')->default('yearly');
            $table->unsignedSmallInteger('duration_months')->default(12);
            $table->json('benefits')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
    }
};
