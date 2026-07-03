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
        Schema::create('chapter_user', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys linking chapters and users
            $table->foreignId('chapter_id')
                ->constrained()
                ->cascadeOnDelete(); // Remove link if chapter is deleted
                
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete(); // Remove link if user is deleted

            // Optional: Add extra data like join date, membership type, etc.
            // $table->date('joined_at')->default(now());
            // $table->string('membership_type')->nullable();

            // Prevent duplicate entries
            $table->unique(['chapter_id', 'user_id']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapter_user');
    }
};