<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            if (!Schema::hasColumn('results', 'percentage')) {
                $table->decimal('percentage', 5, 2)->default(0);
            }

            // StudentController also writes total_possible_points; add if missing.
            if (!Schema::hasColumn('results', 'total_possible_points')) {
                $table->integer('total_possible_points')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            if (Schema::hasColumn('results', 'percentage')) {
                $table->dropColumn('percentage');
            }
            if (Schema::hasColumn('results', 'total_possible_points')) {
                $table->dropColumn('total_possible_points');
            }
        });
    }
};

