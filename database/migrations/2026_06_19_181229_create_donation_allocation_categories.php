<?php
// database/migrations/2026_06_19_000001_create_donation_allocation_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donation_allocation_categories', function (Blueprint $table) {
            $table->string('alc_cat_id')->primary();
            $table->string('alc_cat_name')->unique();
            $table->string('alc_cat_icon')->nullable();
            $table->string('alc_cat_color', 7)->nullable()->default('#554994');
            $table->boolean('alc_cat_is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_allocation_categories');
    }
};