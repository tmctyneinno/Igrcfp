<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cohort_applications', function (Blueprint $table) {
            $table->string('document_path')->nullable()->after('message');
        });
    }

    public function down(): void
    {
        Schema::table('cohort_applications', function (Blueprint $table) {
            $table->dropColumn('document_path');
        });
    }
};