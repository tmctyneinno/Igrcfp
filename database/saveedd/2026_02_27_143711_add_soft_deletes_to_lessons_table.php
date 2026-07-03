<?php
// database/migrations/2026_01_12_xxxxxx_add_soft_deletes_to_lessons_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            // $table->softDeletes(); // This adds the deleted_at column
        });
    }

    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            // $table->dropSoftDeletes();
        });
    }
};