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
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'marking_scheme')) {
                $table->string('marking_scheme')->nullable()->after('price');
            }
        });

        Schema::table('modules', function (Blueprint $table) {
            if (!Schema::hasColumn('modules', 'marking_scheme')) {
                $table->string('marking_scheme')->nullable()->after('is_active');
            }
        });

        Schema::table('topics', function (Blueprint $table) {
            if (!Schema::hasColumn('topics', 'marking_scheme')) {
                $table->string('marking_scheme')->nullable()->after('is_active');
            }
        });

        Schema::table('quizzes', function (Blueprint $table) {
            if (!Schema::hasColumn('quizzes', 'marking_scheme')) {
                $table->string('marking_scheme')->nullable()->after('passing_score');
            }
        });

        Schema::table('results', function (Blueprint $table) {
            if (Schema::hasColumn('results', 'score')) {
                $table->decimal('score', 8, 2)->default(0)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'marking_scheme')) {
                $table->dropColumn('marking_scheme');
            }
        });

        Schema::table('modules', function (Blueprint $table) {
            if (Schema::hasColumn('modules', 'marking_scheme')) {
                $table->dropColumn('marking_scheme');
            }
        });

        Schema::table('topics', function (Blueprint $table) {
            if (Schema::hasColumn('topics', 'marking_scheme')) {
                $table->dropColumn('marking_scheme');
            }
        });

        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'marking_scheme')) {
                $table->dropColumn('marking_scheme');
            }
        });

        Schema::table('results', function (Blueprint $table) {
            if (Schema::hasColumn('results', 'score')) {
                $table->integer('score')->default(0)->change();
            }
        });
    }
};
