<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            // Primary Key
            $table->string('event_id', 255)->primary();
            
            // Foreign Keys
            $table->string('event_created_by_id', 255);
            $table->foreign('event_created_by_id')->references('user_id')->on('users')->onDelete('cascade');
            
            $table->string('event_company_name', 255);
            $table->string('event_name', 255);
            
            // Event Description
            $table->text('event_description')->nullable();
            
            // Location fields with geolocation
            $table->text('event_location_name');
            $table->decimal('event_location_latitude', 10, 8)->nullable();
            $table->decimal('event_location_longitude', 11, 8)->nullable();
            $table->string('event_location_address', 500)->nullable();
            
            // Date and Session
            $table->date('event_start_date');
            $table->date('event_end_date')->nullable();
            $table->string('event_start_session', 20);
            $table->string('event_end_session', 20)->nullable();
            
            $table->integer('event_maximum_participant')->default(0);
            $table->integer('event_current_participant')->default(0);
            
            $table->string('event_document', 2048)->nullable();
            $table->string('event_picture', 2048)->nullable();
            
            $table->string('event_approval_status', 20)->default('Pending');
            $table->string('event_status', 20)->nullable(); // New field for event status (e.g., Successfull, Unsuccessful, Rejected)
            $table->text('event_remarks', 500)->nullable();
            
            $table->boolean('event_publish')->default(0);
            $table->text('event_post_review', 500)->nullable();
            
            $table->string('event_approver_id', 255)->nullable();
            $table->foreign('event_approver_id')->references('user_id')->on('users')->onDelete('set null');

            $table->timestamps();
            
            // Indexes
            $table->index('event_start_date');
            $table->index('event_approval_status');
            $table->index('event_publish');
            $table->index(['event_start_date', 'event_start_session'], 'event_date_session_idx');
            $table->index(['event_location_latitude', 'event_location_longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
