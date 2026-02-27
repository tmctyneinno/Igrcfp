<?php
// database/migrations/2026_01_11_191733_create_events_registrations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events_registrations', function (Blueprint $table) {
            $table->id();
            
            // Just add the column without foreign key constraint for now
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('user_id');
            
            $table->string('registration_number')->unique();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'attended'])->default('pending');
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->text('special_requirements')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();
            
            // Add indexes but no foreign keys
            $table->index('event_id');
            $table->index('user_id');
            $table->unique(['event_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('events_registrations');
    }
};