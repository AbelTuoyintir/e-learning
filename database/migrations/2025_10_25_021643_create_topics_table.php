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
       Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content')->nullable();
            $table->enum('content_type', ['video', 'article', 'quiz', 'assignment'])->default('article');
            $table->string('video_url')->nullable();
            $table->text('article_content')->nullable();
            $table->integer('order')->default(0);
            $table->integer('duration_minutes')->default(0);
            $table->boolean('is_active')->default(true);
             $table->boolean('has_quiz')->default(false);
             $table->integer('passing_score')->default(70);
            $table->timestamps();

            $table->index(['module_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
