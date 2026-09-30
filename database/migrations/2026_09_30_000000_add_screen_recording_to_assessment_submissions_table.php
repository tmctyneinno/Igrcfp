<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_submissions', function (Blueprint $table) {
            $table->string('screen_recording_path')->nullable()->after('submission_file_path');
            $table->string('screen_recording_mime_type', 64)->nullable()->after('screen_recording_path');
            $table->unsignedBigInteger('screen_recording_size')->nullable()->after('screen_recording_mime_type');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'screen_recording_path',
                'screen_recording_mime_type',
                'screen_recording_size',
            ]);
        });
    }
};
