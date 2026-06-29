<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->string('donation_id')->primary(); // Custom ID: DON-0001, DON-0002, etc.

            // Donor info
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_phone');

            // Donation info
            $table->decimal('donation_amount', 10, 2);

            // Payment method (string to allow 'cash', 'toyyibpay', 'stripe', 'cash')
            $table->string('donation_payment_method', 20);

            // Who received the payment (gateway or staff id)
            $table->string('donation_received_by', 50);

            // Optional transaction reference (for online payments)
            $table->string('donation_transaction_id')->nullable();

            // Status of donation
            $table->string('donation_status', 20)->default('pending');

            // created_at & updated_at
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
