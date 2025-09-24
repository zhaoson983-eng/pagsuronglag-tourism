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
            // Add location column if it doesn't exist
            if (!Schema::hasColumn('tourist_spots', 'location')) {
                $table->string('location')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourist_spots', function (Blueprint $table) {
            if (Schema::hasColumn('tourist_spots', 'location')) {
                $table->dropColumn('location');
            }
        });
    }
};
