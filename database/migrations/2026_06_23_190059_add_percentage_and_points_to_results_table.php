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
        Schema::table('results', function (Blueprint $table) {
            if (!Schema::hasColumn('results', 'percentage')) {
                $table->decimal('percentage', 5, 2)->nullable()->after('score');
            }
            if (!Schema::hasColumn('results', 'total_possible_points')) {
                $table->integer('total_possible_points')->nullable()->after('percentage');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn(['percentage', 'total_possible_points']);
        });
    }
};
