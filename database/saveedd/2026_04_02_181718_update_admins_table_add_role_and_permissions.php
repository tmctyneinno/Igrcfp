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
        Schema::table('admins', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('admins', 'role')) {
                $table->enum('role', ['super_admin', 'admin', 'moderator'])
                      ->default('admin')
                      ->after('password');
            }
            
            if (!Schema::hasColumn('admins', 'is_active')) {
                $table->boolean('is_active')
                      ->default(true)
                      ->after('role');
            }
            
            if (!Schema::hasColumn('admins', 'created_by')) {
                $table->foreignId('created_by')
                      ->nullable()
                      ->after('is_active')
                      ->constrained('admins')
                      ->onDelete('set null');
            }
            
            if (!Schema::hasColumn('admins', 'last_login_at')) {
                $table->timestamp('last_login_at')
                      ->nullable()
                      ->after('remember_token');
            }
            
            if (!Schema::hasColumn('admins', 'last_login_ip')) {
                $table->string('last_login_ip', 45)
                      ->nullable()
                      ->after('last_login_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('admins', 'created_by')) {
                $table->dropForeign(['created_by']);
            }
            
            // Drop columns if they exist
            $columns = ['role', 'is_active', 'created_by', 'last_login_at', 'last_login_ip'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('admins', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};