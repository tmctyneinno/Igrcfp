<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('research_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('title');
            $table->string('organisation');
            $table->string('email');
            $table->unsignedBigInteger('document_id');
            $table->string('document_title');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_contacts');
    }
};
