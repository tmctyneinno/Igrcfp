<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapter_leadership', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')
                ->constrained('chapters')
                ->onDelete('cascade');
            $table->string('name');
            $table->string('role');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapter_leadership');
    }
};