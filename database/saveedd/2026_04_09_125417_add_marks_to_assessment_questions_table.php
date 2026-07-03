<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('assessment_questions', 'marks')) {
                $table->integer('marks')->default(1)->after('options');
            }
            
            if (!Schema::hasColumn('assessment_questions', 'correct_answer')) {
                $table->string('correct_answer')->nullable()->after('marks');
            }
            
            if (!Schema::hasColumn('assessment_questions', 'explanation')) {
                $table->text('explanation')->nullable()->after('correct_answer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assessment_questions', function (Blueprint $table) {
            $table->dropColumn(['marks', 'correct_answer', 'explanation']);
        });
    }
};