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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('email')->nullable();
            $table->string('verified_email')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('verified_phone')->nullable();
            $table->enum('education_level', ['basic', 'jhs', 'shs', 'tertiary'])->default('basic');
            $table->string('grade_level')->default('1'); // 1-6, JHS1-JHS3, SHS1-SHS3, 100-400
            $table->string('subject')->nullable(); // mathematics, science, english, etc.
            $table->string('Program')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
