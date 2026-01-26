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
        // First, check if 'total' column already exists to avoid errors
        if (!Schema::hasColumn('orders', 'total')) {
            Schema::table('orders', function (Blueprint $table) {
                // Add the total column with appropriate type
                // Using decimal with 10 total digits and 2 decimal places
                $table->decimal('total', 10, 2)->default(0.00);
            });
        }
        
        // Now add the status column after the total column
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])
                  ->default('pending')
                  ->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop both columns in reverse order
            $table->dropColumn('status');
            $table->dropColumn('total');
        });
    }
};