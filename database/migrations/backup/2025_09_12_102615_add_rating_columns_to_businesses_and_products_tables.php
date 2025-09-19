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
        // Add columns to businesses table
        Schema::table('businesses', function (Blueprint $table) {
            if (!Schema::hasColumn('businesses', 'average_rating')) {
                $table->decimal('average_rating', 3, 1)->default(0)->after('business_type');
            }
            if (!Schema::hasColumn('businesses', 'total_ratings')) {
                $table->unsignedInteger('total_ratings')->default(0)->after('average_rating');
            }
        });

        // Add columns to products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'average_rating')) {
                $table->decimal('average_rating', 3, 1)->default(0)->after('image');
            }
            if (!Schema::hasColumn('products', 'total_ratings')) {
                $table->unsignedInteger('total_ratings')->default(0)->after('average_rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from businesses table
        if (Schema::hasColumn('businesses', 'average_rating') || Schema::hasColumn('businesses', 'total_ratings')) {
            Schema::table('businesses', function (Blueprint $table) {
                if (Schema::hasColumn('businesses', 'average_rating')) {
                    $table->dropColumn('average_rating');
                }
                if (Schema::hasColumn('businesses', 'total_ratings')) {
                    $table->dropColumn('total_ratings');
                }
            });
        }

        // Remove columns from products table
        if (Schema::hasColumn('products', 'average_rating') || Schema::hasColumn('products', 'total_ratings')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'average_rating')) {
                    $table->dropColumn('average_rating');
                }
                if (Schema::hasColumn('products', 'total_ratings')) {
                    $table->dropColumn('total_ratings');
                }
            });
        }
    }
};
