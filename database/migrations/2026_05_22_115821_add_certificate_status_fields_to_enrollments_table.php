<?php
// database/migrations/xxxx_xx_xx_add_certificate_status_fields_to_enrollments_table.php

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
        Schema::table('enrollments', function (Blueprint $table) {
            // Certificate status management
            if (!Schema::hasColumn('enrollments', 'certificate_status')) {
                $table->string('certificate_status')->default('pending')->after('certificate_number');
            }
            
            if (!Schema::hasColumn('enrollments', 'certificate_status_updated_at')) {
                $table->timestamp('certificate_status_updated_at')->nullable()->after('certificate_status');
            }
            
            if (!Schema::hasColumn('enrollments', 'certificate_status_updated_by')) {
                $table->unsignedBigInteger('certificate_status_updated_by')->nullable()->after('certificate_status_updated_at');
            }
            
            if (!Schema::hasColumn('enrollments', 'certificate_revocation_reason')) {
                $table->text('certificate_revocation_reason')->nullable()->after('certificate_status_updated_by');
            }
            
            if (!Schema::hasColumn('enrollments', 'certificate_verified')) {
                $table->boolean('certificate_verified')->default(false)->after('certificate_revocation_reason');
            }
            
            // Add foreign key for admin who updated status (optional)
            if (!Schema::hasColumn('enrollments', 'certificate_status_updated_by')) {
                // Already added above, but if you want foreign key:
                // $table->foreign('certificate_status_updated_by')
                //       ->references('id')
                //       ->on('admins')
                //       ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_status',
                'certificate_status_updated_at',
                'certificate_status_updated_by',
                'certificate_revocation_reason',
                'certificate_verified',
            ]);
        });
    }
};