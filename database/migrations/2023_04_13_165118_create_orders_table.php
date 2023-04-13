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
        Schema::create('orders', function (Blueprint $table) {
            // TODO: add campaign/customer table + foreign key
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('product'); // TODO: create products table
            $table->string('location'); // TODO: add location table
            $table->string('seller'); // TODO: add sellers/people table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
