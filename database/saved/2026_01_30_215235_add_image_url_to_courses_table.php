<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('banner_image');
        });
        
        // Copy data from banner_image to image_url if banner_image exists
        \DB::statement('UPDATE courses SET image_url = banner_image WHERE banner_image IS NOT NULL');
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }
};