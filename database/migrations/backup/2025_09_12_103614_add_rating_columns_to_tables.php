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
        // Add columns to businesses table if they don't exist
        if (!Schema::hasColumn('businesses', 'average_rating')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->decimal('average_rating', 3, 1)->default(0)->after('business_type');
            });
        }
        
        if (!Schema::hasColumn('businesses', 'total_ratings')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->unsignedInteger('total_ratings')->default(0)->after('average_rating');
            });
        }

        // Add columns to products table if they don't exist
        if (!Schema::hasColumn('products', 'average_rating')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('average_rating', 3, 1)->default(0)->after('image');
            });
        }
        
        if (!Schema::hasColumn('products', 'total_ratings')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedInteger('total_ratings')->default(0)->after('average_rating');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from businesses table if they exist
        if (Schema::hasColumn('businesses', 'average_rating')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('average_rating');
            });
        }
        
        if (Schema::hasColumn('businesses', 'total_ratings')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('total_ratings');
            });
        }

        // Remove columns from products table if they exist
        if (Schema::hasColumn('products', 'average_rating')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('average_rating');
            });
        }
        
        if (Schema::hasColumn('products', 'total_ratings')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('total_ratings');
            });
        }
    }
};
