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
        Schema::create('bulk_sms_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 20)->unique();
            $table->string('firstname')->nullable(); // Optional: for personalized messages
            $table->string('lastname')->nullable();  // Optional: for personalized messages
            $table->string('email')->nullable();     // Optional: for future use
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_sms_recipients');
    }
};
