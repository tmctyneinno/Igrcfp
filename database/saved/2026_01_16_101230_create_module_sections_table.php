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
        Schema::create('module_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->onDelete('cascade');
            $table->string('title');
            $table->string('section_number'); // e.g., 1.1, 1.2
            $table->longText('content');
            $table->enum('content_type', ['text', 'video', 'quiz', 'exercise', 'download']);
            $table->json('attachments')->nullable(); // JSON array of file paths
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['module_id', 'section_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_sections');
    }
};
