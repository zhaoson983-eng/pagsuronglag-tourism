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
        Schema::table('businesses', function (Blueprint $table) {
            $table->decimal('entrance_fee', 10, 2)->nullable()->after('description');
            $table->decimal('cottage_fee', 10, 2)->nullable()->after('entrance_fee');
            $table->time('check_in_time')->nullable()->after('cottage_fee');
            $table->time('check_out_time')->nullable()->after('check_in_time');
            $table->json('amenities')->nullable()->after('check_out_time');
            $table->boolean('has_swimming_pool')->default(false)->after('amenities');
            $table->boolean('has_restaurant')->default(false)->after('has_swimming_pool');
            $table->boolean('has_parking')->default(false)->after('has_restaurant');
            $table->boolean('has_wifi')->default(false)->after('has_parking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn([
                'entrance_fee',
                'cottage_fee',
                'check_in_time',
                'check_out_time',
                'amenities',
                'has_swimming_pool',
                'has_restaurant',
                'has_parking',
                'has_wifi'
            ]);
        });
    }
};
