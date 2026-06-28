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
        Schema::table('questions', function (Blueprint $table) {
            $table->string('type')->default('MCQ')->after('quiz_id'); // MCQ, True/False, Short Answer, Essay
            $table->text('explanation')->nullable()->after('correct_option');
            $table->string('difficulty_level')->default('Medium')->after('explanation'); // Easy, Medium, Hard
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['type', 'explanation', 'difficulty_level']);
        });
    }
};
