<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::table('memberships', function (Blueprint $table) {
            $table->foreignId('chapter_id')->nullable()->constrained()->onDelete('set null');
        });
    }
    public function down() {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropForeign(['chapter_id']);
            $table->dropColumn('chapter_id');
        });
    }
};
