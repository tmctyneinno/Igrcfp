<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('enrollments')) {
            return;
        }

        Schema::table('enrollments', function (Blueprint $table) {
            if (! Schema::hasColumn('enrollments', 'external_user_id')) {
                $table->string('external_user_id')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('enrollments', 'client')) {
                $table->string('client')->nullable()->after('external_user_id');
            }

            if (! Schema::hasColumn('enrollments', 'progress_percentage')) {
                $table->decimal('progress_percentage', 5, 2)->default(0)->after('progress');
            }

            if (! Schema::hasColumn('enrollments', 'last_activity_at')) {
                $table->timestamp('last_activity_at')->nullable()->after('completed_at');
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['external_user_id', 'client'], 'enrollments_external_user_client_idx');
            $table->index(['course_id', 'external_user_id', 'client'], 'enrollments_course_external_user_client_idx');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('enrollments')) {
            return;
        }

        Schema::table('enrollments', function (Blueprint $table) {
            foreach (['enrollments_external_user_client_idx', 'enrollments_course_external_user_client_idx'] as $indexName) {
                try {
                    $table->dropIndex($indexName);
                } catch (Throwable) {
                    // no-op
                }
            }

            if (Schema::hasColumn('enrollments', 'last_activity_at')) {
                $table->dropColumn('last_activity_at');
            }

            if (Schema::hasColumn('enrollments', 'progress_percentage')) {
                $table->dropColumn('progress_percentage');
            }

            if (Schema::hasColumn('enrollments', 'client')) {
                $table->dropColumn('client');
            }

            if (Schema::hasColumn('enrollments', 'external_user_id')) {
                $table->dropColumn('external_user_id');
            }
        });
    }
};
