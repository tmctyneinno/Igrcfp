<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Set is_verified to true for users who have verified their email
        DB::table('users')
            ->whereNotNull('email_verified_at')
            ->update(['is_verified' => true]);
    }

    public function down(): void
    {
        // No need to rollback
    }
};