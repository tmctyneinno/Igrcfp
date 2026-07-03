<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('scholarship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->nullable()->constrained('articles')->onDelete('set null');
            $table->string('full_name');
            $table->string('nationality');
            $table->string('country_of_residence');
            $table->string('email');
            $table->string('phone_number');
            $table->text('academic_background')->nullable();
            $table->string('highest_qualification');
            $table->string('institution');
            $table->string('year_completed');
            $table->string('current_role')->nullable();
            $table->string('organisation')->nullable();
            $table->json('preferred_programmes');
            $table->text('personal_statement');
            $table->boolean('declaration')->default(false);
            $table->string('status')->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scholarship_applications');
    }
};