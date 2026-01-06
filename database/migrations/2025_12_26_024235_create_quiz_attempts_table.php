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
       // In database/migrations/[timestamp]_create_quiz_attempts_table.php

    Schema::create('quiz_attempts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->integer('score')->nullable();
        $table->integer('total_questions')->nullable();
        $table->integer('correct_answers')->nullable();
        $table->timestamp('started_at')->nullable();
        $table->timestamp('completed_at')->nullable();
        $table->json('answers')->nullable();
        $table->string('status')->default('in_progress'); // in_progress, completed, abandoned
        $table->timestamps();

        // Add unique constraint to prevent multiple attempts if needed
        // $table->unique(['quiz_id', 'user_id']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
