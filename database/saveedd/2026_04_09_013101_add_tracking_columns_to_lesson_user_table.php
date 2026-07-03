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
        Schema::table('lesson_user', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('lesson_user', 'time_spent')) {
                $table->integer('time_spent')->default(0)->after('completed_at')
                    ->comment('Time spent in seconds viewing the lesson');
            }
            
            if (!Schema::hasColumn('lesson_user', 'auto_completed')) {
                $table->boolean('auto_completed')->default(false)->after('time_spent')
                    ->comment('Whether lesson was auto-completed by system');
            }
            
            if (!Schema::hasColumn('lesson_user', 'scroll_progress')) {
                $table->integer('scroll_progress')->default(0)->after('auto_completed')
                    ->comment('Percentage of content scrolled (0-100)');
            }
            
            if (!Schema::hasColumn('lesson_user', 'attempts')) {
                $table->integer('attempts')->default(1)->after('scroll_progress')
                    ->comment('Number of times lesson was viewed');
            }
            
            if (!Schema::hasColumn('lesson_user', 'last_viewed_at')) {
                $table->timestamp('last_viewed_at')->nullable()->after('attempts')
                    ->comment('Last time user viewed this lesson');
            }
            
            if (!Schema::hasColumn('lesson_user', 'metadata')) {
                $table->json('metadata')->nullable()->after('last_viewed_at')
                    ->comment('Additional tracking data (JSON)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_user', function (Blueprint $table) {
            $table->dropColumn([
                'time_spent',
                'auto_completed',
                'scroll_progress',
                'attempts',
                'last_viewed_at',
                'metadata'
            ]);
        });
    }
};