<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Quiz type: practice vs final (used for progression gating)
            if (!Schema::hasColumn('quizzes', 'type')) {
                $table->string('type')->default('practice')->after('description');
            }

            // Passing score threshold (percentage)
            if (!Schema::hasColumn('quizzes', 'passing_score')) {
                $table->unsignedInteger('passing_score')->default(65)->after('type');
            }

            // Distribution map (e.g. {"topic_id": 5, "topic_id2": 10})
            if (!Schema::hasColumn('quizzes', 'question_distribution')) {
                $table->json('question_distribution')->nullable()->after('passing_score');
            }

            // Ensure legacy code using quiz_type keeps working: no changes here.
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'question_distribution')) {
                $table->dropColumn('question_distribution');
            }
            if (Schema::hasColumn('quizzes', 'passing_score')) {
                $table->dropColumn('passing_score');
            }
            if (Schema::hasColumn('quizzes', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};

