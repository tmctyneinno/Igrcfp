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
        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('excerpt')->nullable();
                $table->longText('content'); 
                $table->string('image_path')->nullable();
                $table->foreignId('article_category_id')->constrained()->onDelete('cascade');
                $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
                $table->boolean('is_featured')->default(false);
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
                $table->timestamp('published_at')->nullable();
                $table->integer('read_time')->default(5);
                $table->string('tags')->nullable();
                $table->integer('views')->default(0);
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                // Optional: Add indexes for better performance
                $table->index('slug');
                $table->index('status');
                $table->index('published_at');
                $table->index(['article_category_id', 'published_at']);
                $table->index(['is_featured', 'published_at']);
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
