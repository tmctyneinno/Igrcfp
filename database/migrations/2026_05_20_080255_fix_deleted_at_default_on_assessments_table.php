<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixDeletedAtDefaultOnAssessmentsTable extends Migration
{
    public function up()
    {
        // Fix the deleted_at column to allow NULL with no bad default
        DB::statement('ALTER TABLE assessments MODIFY deleted_at TIMESTAMP NULL DEFAULT NULL');

        // Also clean up any bad 0000-00-00 values already in the table
        DB::statement("UPDATE assessments SET deleted_at = NULL WHERE deleted_at = '0000-00-00 00:00:00'");
    }

    public function down()
    {
        DB::statement('ALTER TABLE assessments MODIFY deleted_at TIMESTAMP NULL DEFAULT NULL');
    }
}