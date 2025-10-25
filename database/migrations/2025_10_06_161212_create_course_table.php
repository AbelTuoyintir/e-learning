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
       Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('category')->nullable();
            $table->integer('duration_weeks')->nullable();
            $table->string('instructor')->nullable();
            $table->string('status')->default('active');

            // Educational level system
            $table->enum('education_level', ['basic', 'jhs', 'shs', 'tertiary'])->default('basic');
            $table->string('grade_level')->default('1'); // 1-6, JHS1-JHS3, SHS1-SHS3, 100-400
            $table->string('subject')->nullable(); // mathematics, science, english, etc.

            $table->timestamps();

            // Indexes
            $table->index(['education_level', 'grade_level']);
            $table->index('subject');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course');
    }
};
