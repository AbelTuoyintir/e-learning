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
        Schema::table('quizzes', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('quizzes', 'related_to_id')) {
                $table->unsignedBigInteger('related_to_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('quizzes', 'related_to_type')) {
                $table->string('related_to_type')->nullable()->after('related_to_id');
            }

            // Make module_id nullable (if not already)
            if (Schema::hasColumn('quizzes', 'module_id')) {
                $table->unsignedBigInteger('module_id')->nullable()->change();
            }

            // Add index for better query performance
            $table->index(['related_to_type', 'related_to_id']);
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Drop the columns if they exist
            if (Schema::hasColumn('quizzes', 'related_to_id')) {
                $table->dropColumn('related_to_id');
            }

            if (Schema::hasColumn('quizzes', 'related_to_type')) {
                $table->dropColumn('related_to_type');
            }

            // Remove the index
            $table->dropIndex(['related_to_type', 'related_to_id']);

            // Revert module_id to not nullable if needed
            if (Schema::hasColumn('quizzes', 'module_id')) {
                $table->unsignedBigInteger('module_id')->nullable(false)->change();
            }
        });
    }
};
