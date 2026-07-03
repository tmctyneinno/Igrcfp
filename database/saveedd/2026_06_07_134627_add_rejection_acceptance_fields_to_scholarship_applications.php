<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('scholarship_applications', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('scholarship_applications', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('admin_notes');
            }
            
            if (!Schema::hasColumn('scholarship_applications', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            }
            
            if (!Schema::hasColumn('scholarship_applications', 'accepted_at')) {
                $table->timestamp('accepted_at')->nullable()->after('rejected_at');
            }
            
            if (!Schema::hasColumn('scholarship_applications', 'user_accepted')) {
                $table->boolean('user_accepted')->default(false)->after('accepted_at');
            }
        });
    }

    public function down()
    {
        Schema::table('scholarship_applications', function (Blueprint $table) {
            $table->dropColumn([
                'rejection_reason',
                'rejected_at',
                'accepted_at',
                'user_accepted'
            ]);
        });
    }
};