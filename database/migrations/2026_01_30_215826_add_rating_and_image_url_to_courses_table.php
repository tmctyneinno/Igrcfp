<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Add rating column if it doesn't exist
            if (!Schema::hasColumn('courses', 'rating')) {
                $table->decimal('rating', 3, 2)->nullable()->default(0)->after('format');
            }
            
            // Add image_url column if it doesn't exist
            if (!Schema::hasColumn('courses', 'image_url')) {
                $table->string('image_url')->nullable()->after('banner_image');
            }
            
            // Add is_popular column if it doesn't exist
            if (!Schema::hasColumn('courses', 'is_popular')) {
                $table->boolean('is_popular')->default(false)->after('is_featured');
            }
            
            // Add total_enrollments column if it doesn't exist
            if (!Schema::hasColumn('courses', 'total_enrollments')) {
                $table->integer('total_enrollments')->default(0)->after('rating');
            }
        });
        
        // Copy banner_image to image_url for existing records
        if (Schema::hasColumn('courses', 'banner_image') && Schema::hasColumn('courses', 'image_url')) {
            \DB::statement('UPDATE courses SET image_url = banner_image WHERE banner_image IS NOT NULL');
        }
        
        // Set some random ratings for existing courses
        if (Schema::hasColumn('courses', 'rating')) {
            \DB::statement('UPDATE courses SET rating = ROUND(RAND() * (5.0 - 3.5) + 3.5, 1)');
        }
        
        // Mark some courses as popular
        if (Schema::hasColumn('courses', 'is_popular')) {
            \DB::statement('UPDATE courses SET is_popular = 1 WHERE id % 3 = 0'); // Mark every 3rd course as popular
        }
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $columns = ['rating', 'image_url', 'is_popular', 'total_enrollments'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('courses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};