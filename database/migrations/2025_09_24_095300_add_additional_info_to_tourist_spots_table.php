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
        Schema::table('tourist_spots', function (Blueprint $table) {
            // Add additional_info column if it doesn't exist
            if (!Schema::hasColumn('tourist_spots', 'additional_info')) {
                $table->text('additional_info')->nullable()->after('location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourist_spots', function (Blueprint $table) {
            if (Schema::hasColumn('tourist_spots', 'additional_info')) {
                $table->dropColumn('additional_info');
            }
        });
    }
};
