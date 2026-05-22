<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('external_users')) {
            Schema::create('external_users', function (Blueprint $table) {
                $table->id();
                $table->string('external_user_id');
                $table->string('client');
                $table->timestamps();

                $table->unique(['external_user_id', 'client']);
                $table->index('client');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('external_users');
    }
};
