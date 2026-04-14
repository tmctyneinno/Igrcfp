<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentor_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('domain')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->text('bio')->nullable();
            $table->text('expertise_summary')->nullable();
            $table->string('availability_status')->default('taking');
            $table->json('languages')->nullable();
            $table->json('skills')->nullable();
            $table->json('certifications')->nullable();
            $table->unsignedSmallInteger('max_mentees')->nullable();
            $table->string('status')->default('pending');
            $table->text('admin_feedback')->nullable();
            $table->foreignId('processed_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentor_applications');
    }
};
