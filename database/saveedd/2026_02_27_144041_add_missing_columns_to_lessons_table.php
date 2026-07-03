<?php
// database/migrations/2026_01_12_xxxxxx_add_missing_columns_to_lessons_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('lessons', 'short_description')) {
                $table->text('short_description')->nullable()->after('title');
            }
            
            if (!Schema::hasColumn('lessons', 'content')) {
                $table->longText('content')->nullable()->after('short_description');
            }
            
            if (!Schema::hasColumn('lessons', 'video_url')) {
                $table->string('video_url')->nullable()->after('content');
            }
            
            if (!Schema::hasColumn('lessons', 'video_embed_code')) {
                $table->string('video_embed_code')->nullable()->after('video_url');
            }
            
            if (!Schema::hasColumn('lessons', 'duration')) {
                $table->integer('duration')->nullable()->after('video_embed_code');
            }
            
            if (!Schema::hasColumn('lessons', 'is_free')) {
                $table->boolean('is_free')->default(false)->after('sort_order');
            }
            
            if (!Schema::hasColumn('lessons', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('is_free');
            }
            
            if (!Schema::hasColumn('lessons', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('title');
            }
        });
    }

    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn([
                'short_description',
                'content',
                'video_url',
                'video_embed_code',
                'duration',
                'is_free',
                'is_published',
                'slug'
            ]);
        });
    }
};