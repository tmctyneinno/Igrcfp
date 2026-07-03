<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedBigInteger('chapter_id')->nullable()->after('id');
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['chapter_id']);
            $table->dropColumn('chapter_id');
        });
    }
};