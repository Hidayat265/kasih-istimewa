<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            // Composite primary key (event_id + user_id)
            $table->string('event_id');
            $table->string('user_id');
            
            // Status with default 'pending'
            $table->enum('participant_status', ['pending', 'confirmed', 'cancelled', 'attended'])->default('pending');
            
            // Timestamps
            $table->timestamp('participant_registered_at')->useCurrent();
            $table->timestamp('participant_confirmed_at')->nullable();
            $table->timestamp('participant_cancelled_at')->nullable();
            $table->text('participant_cancellation_reason')->nullable();
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            
            // Prevent duplicate registration (composite unique key)
            $table->unique(['event_id', 'user_id']);
            
            // Add indexes for better performance
            $table->index('participant_status');
            $table->index('participant_registered_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('participants');
    }
};