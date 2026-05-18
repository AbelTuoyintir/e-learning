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
        Schema::table('enrollments', function (Blueprint $table) {
            if (! Schema::hasColumn('enrollments', 'enrolled_at')) {
                $table->timestamp('enrolled_at')->nullable()->after('course_id');
            }

            if (! Schema::hasColumn('enrollments', 'price_paid')) {
                $table->decimal('price_paid', 10, 2)->default(0)->after('enrolled_at');
            }

            if (! Schema::hasColumn('enrollments', 'payment_status')) {
                $table->string('payment_status', 20)->default('free')->after('price_paid');
            }

            if (! Schema::hasColumn('enrollments', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_status');
            }

            if (! Schema::hasColumn('enrollments', 'purchased_at')) {
                $table->timestamp('purchased_at')->nullable()->after('payment_reference');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $columns = ['enrolled_at', 'price_paid', 'payment_status', 'payment_reference', 'purchased_at'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('enrollments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
