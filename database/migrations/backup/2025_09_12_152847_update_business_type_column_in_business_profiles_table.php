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
        // First, update existing NULL values to 'shop'
        \DB::table('business_profiles')
            ->whereNull('business_type')
            ->update(['business_type' => 'shop']);

        // Then modify the column to have a default value
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->string('business_type')
                  ->default('shop')
                  ->nullable(false)
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the column to its previous state
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->string('business_type')
                  ->default(null)
                  ->nullable()
                  ->change();
        });
    }
};
