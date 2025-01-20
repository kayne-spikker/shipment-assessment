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
        if(!Schema::hasTable('csv_fields')) {
            Schema::create('csv_fields', function (Blueprint $table) {
                $table->id();
                $table->foreignId('csv_upload_id')->constrained()->onDelete('cascade');
                $table->json('field_data');
                $table->string('validation_status');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csv_fields');
    }
};
