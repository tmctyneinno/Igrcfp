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
        
        Schema::create('cohort_applications', function (Blueprint $table) {
            $table->id();
 
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country');
 
            $table->string('level');
            $table->string('discipline')->nullable();
            $table->text('message')->nullable();
 
            // Which cohort intake this application is for, e.g. "October 2026"
            $table->string('cohort');
 
            // Simple pipeline status for admissions to track progress
            $table->enum('status', ['new', 'reviewing', 'admitted', 'rejected', 'withdrawn'])
                ->default('new');
 
            // Basic metadata, useful for admissions / fraud checks
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
 
            $table->timestamp('reviewed_at')->nullable();
 
            $table->timestamps();
 
            $table->index(['cohort', 'status']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cohort_applications');
    }
};
