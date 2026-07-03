<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Add loggable polymorphic columns
            $table->string('loggable_type')->nullable()->after('id');
            $table->unsignedBigInteger('loggable_id')->nullable()->after('loggable_type');
            
            // Add subject polymorphic columns
            $table->string('subject_type')->nullable()->after('loggable_id');
            $table->unsignedBigInteger('subject_id')->nullable()->after('subject_type');
            
            // Add other columns
            $table->string('event')->after('subject_id');
            $table->string('module')->nullable()->after('event');
            $table->json('properties')->nullable()->after('description');
            $table->string('severity')->default('info')->after('user_agent');
            
            // Modify existing columns
            $table->string('ip_address', 45)->nullable()->change();
            $table->text('user_agent')->nullable()->change();
            
            // Make user_id nullable if keeping it temporarily
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // Add indexes
            $table->index(['event', 'module']);
            $table->index('created_at');
            $table->index(['loggable_type', 'loggable_id']);
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['event', 'module']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['loggable_type', 'loggable_id']);
            $table->dropIndex(['subject_type', 'subject_id']);
            
            // Drop added columns
            $table->dropColumn([
                'loggable_type', 
                'loggable_id', 
                'subject_type', 
                'subject_id', 
                'event', 
                'module', 
                'properties', 
                'severity'
            ]);
        });
    }
};