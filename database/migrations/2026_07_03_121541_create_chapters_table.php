<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('slug')->unique();
            $table->string('country_focus')->nullable();
            $table->text('description');
            $table->decimal('annual_fee', 8, 2)->default(0);
            $table->string('contact_email')->nullable();
            $table->string('meeting_frequency')->default('Quarterly');
            $table->json('benefits')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};