<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_highlights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('target_type');
            $table->unsignedBigInteger('target_id');
            $table->json('highlights')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'course_id', 'target_type', 'target_id'], 'doc_highlights_unique_target');
            $table->index(['target_type', 'target_id'], 'doc_highlights_target_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_highlights');
    }
};
