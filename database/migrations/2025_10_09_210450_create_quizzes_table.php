<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image')->nullable();
            $table->enum('quiz_type', ['topic_quiz', 'module_assessment', 'course_exam']);
            $table->morphs('related_to');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->integer('time_limit')->default(30); // in minutes
            $table->integer('time_per_question')->default(30); // in seconds
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
