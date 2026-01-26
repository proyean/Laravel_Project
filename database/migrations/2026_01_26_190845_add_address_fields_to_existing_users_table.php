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
        // First check if columns exist
        if (!Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('address')->nullable()->after('email');
            });
        }
        
        if (!Schema::hasColumn('users', 'city')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('city')->nullable()->after('address');
            });
        }
        
        if (!Schema::hasColumn('users', 'country')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('country')->nullable()->default('Ethiopia')->after('city');
            });
        }
        
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('country');
            });
        }
        
        if (!Schema::hasColumn('users', 'postal_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('postal_code')->nullable()->after('phone');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop if they exist
        if (Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('address');
            });
        }
        
        if (Schema::hasColumn('users', 'city')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('city');
            });
        }
        
        if (Schema::hasColumn('users', 'country')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('country');
            });
        }
        
        if (Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
        }
        
        if (Schema::hasColumn('users', 'postal_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('postal_code');
            });
        }
    }
};