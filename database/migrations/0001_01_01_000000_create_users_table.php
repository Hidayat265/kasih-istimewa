<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id')->primary();
            $table->string('user_name');
            $table->string('user_email')->unique();
            $table->timestamp('user_email_verified_at')->nullable();
            $table->string('user_password');
            $table->date('user_dob')->nullable();
            $table->string('user_phone_number')->nullable();
            $table->string('user_profile_picture')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->enum('user_status', ['active', 'deactivated'])->default('active');
            $table->string('remember_token')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};