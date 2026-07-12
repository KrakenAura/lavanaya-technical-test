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
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'payment_method')) {
                $table->dropColumn('payment_method');
            }

            if (Schema::hasColumn('payments', 'reference_number')) {
                $table->dropColumn('reference_number');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'rejection_notes')) {
                $table->text('rejection_notes')
                    ->nullable()
                    ->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'rejection_notes')) {
                $table->dropColumn('rejection_notes');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_method')
                ->nullable()
                ->after('status');

            $table->string('reference_number')
                ->nullable()
                ->after('payment_method');
        });
    }
};
