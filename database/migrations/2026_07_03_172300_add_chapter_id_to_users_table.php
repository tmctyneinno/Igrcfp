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
        Schema::table('users', function (Blueprint $table) {
            // Add chapter_id column, nullable so existing users don't break
            $table->foreignId('chapter_id')
                ->nullable()
                ->constrained('chapters')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove foreign key first, then the column
            $table->dropForeign(['chapter_id']);
            $table->dropColumn('chapter_id');
        });
    }
};