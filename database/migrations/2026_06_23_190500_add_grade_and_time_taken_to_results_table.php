<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            if (!Schema::hasColumn('results', 'grade')) {
                $table->string('grade')->nullable()->after('total_possible_points');
            }
            if (!Schema::hasColumn('results', 'time_taken')) {
                $table->integer('time_taken')->nullable()->after('grade'); // in seconds
            }
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn(['grade', 'time_taken']);
        });
    }
};
