<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentorship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_profile_id')->constrained('mentor_profiles')->cascadeOnDelete();
            $table->foreignId('mentee_id')->constrained('users')->cascadeOnDelete();
            $table->text('goals');
            $table->string('preferred_duration')->nullable();
            $table->string('availability')->nullable();
            $table->string('communication_method')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->text('mentor_feedback')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentorship_applications');
    }
};