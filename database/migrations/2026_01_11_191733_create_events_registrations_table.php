<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    { 
        Schema::create('events_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('registration_number')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->integer('additional_attendees')->default(0);
            $table->text('dietary_requirements')->nullable();
            $table->text('special_requirements')->nullable();
            $table->string('hear_about_event')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'attended'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('payment_status')->nullable();
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('registration_number');
            $table->index('event_id');
            $table->index('email');
            $table->index('status');
            $table->index('payment_status');
            $table->index(['event_id', 'status']);
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events_registrations');
    }
};
