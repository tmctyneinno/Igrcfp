<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentorship_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentorship_id')->constrained('mentorships')->cascadeOnDelete();
            $table->string('type');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentorship_updates');
    }
};