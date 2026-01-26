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
        // Check if 'total' column exists AND 'total_amount' doesn't exist
        if (Schema::hasColumn('orders', 'total') && !Schema::hasColumn('orders', 'total_amount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('total', 'total_amount');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if 'total_amount' column exists AND 'total' doesn't exist
        if (Schema::hasColumn('orders', 'total_amount') && !Schema::hasColumn('orders', 'total')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('total_amount', 'total');
            });
        }
    }
};