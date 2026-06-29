<?php
// database/migrations/2026_06_19_000002_create_donation_allocations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donation_allocations', function (Blueprint $table) {
            $table->string('allocation_id')->primary();
            $table->string('allocation_category_id');
            $table->string('allocation_month', 7)->index();
            $table->decimal('allocation_percent', 5, 2)->default(0.00);
            $table->decimal('allocation_amount', 15, 2)->default(0.00);
            $table->string('allocation_changed_by')->nullable();
            $table->foreign('allocation_changed_by')->references('user_id')->on('users')->onDelete('set null');
            $table->text('allocation_notes')->nullable();
            $table->timestamps();

            $table->foreign('allocation_category_id')
                ->references('alc_cat_id')
                ->on('donation_allocation_categories')
                ->onDelete('cascade');

            // Fixed: Added custom short name for the unique constraint
            $table->unique(['allocation_month', 'allocation_category_id'], 'donation_alloc_month_cat_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_allocations');
    }
};