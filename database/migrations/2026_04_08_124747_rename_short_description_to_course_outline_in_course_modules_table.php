<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('course_modules', function (Blueprint $table) {
            $table->renameColumn('short_description', 'course_outline');
        });
    }

    public function down()
    {
        Schema::table('course_modules', function (Blueprint $table) {
            $table->renameColumn('course_outline', 'short_description');
        });
    }
};