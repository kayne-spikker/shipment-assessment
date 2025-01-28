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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Links to orders table
            $table->enum('type', ['billing', 'delivery']); // Address type
            $table->string('companyname')->nullable();
            $table->string('name');
            $table->string('street');
            $table->string('housenumber');
            $table->string('address_line_2')->nullable();
            $table->string('zipcode');
            $table->string('city');
            $table->string('country');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
