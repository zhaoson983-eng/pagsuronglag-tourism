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
            // Common fields for both hotels and resorts
            if (!Schema::hasColumn('businesses', 'check_in_time')) {
                $table->time('check_in_time')->default('14:00:00')->after('delivery_radius');
            }
            if (!Schema::hasColumn('businesses', 'check_out_time')) {
                $table->time('check_out_time')->default('12:00:00')->after('check_in_time');
            }
            if (!Schema::hasColumn('businesses', 'policies')) {
                $table->text('policies')->nullable()->after('check_out_time');
            }
            if (!Schema::hasColumn('businesses', 'amenities')) {
                $table->text('amenities')->nullable()->after('policies');
            }
            
            // Hotel specific fields
            if (!Schema::hasColumn('businesses', 'star_rating')) {
                $table->integer('star_rating')->nullable()->after('amenities');
            }
            
            // Resort specific fields
            if (!Schema::hasColumn('businesses', 'has_swimming_pool')) {
                $table->boolean('has_swimming_pool')->default(false)->after('star_rating');
            }
            if (!Schema::hasColumn('businesses', 'has_restaurant')) {
                $table->boolean('has_restaurant')->default(false)->after('has_swimming_pool');
            }
            if (!Schema::hasColumn('businesses', 'has_parking')) {
                $table->boolean('has_parking')->default(true)->after('has_restaurant');
            }
            if (!Schema::hasColumn('businesses', 'has_wifi')) {
                $table->boolean('has_wifi')->default(true)->after('has_parking');
            }
            
            // Location specific fields
            if (!Schema::hasColumn('businesses', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('has_wifi');
            }
            if (!Schema::hasColumn('businesses', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            
            // Business hours
            if (!Schema::hasColumn('businesses', 'monday_hours')) {
                $table->string('monday_hours', 20)->default('09:00-18:00')->after('longitude');
            }
            if (!Schema::hasColumn('businesses', 'tuesday_hours')) {
                $table->string('tuesday_hours', 20)->default('09:00-18:00')->after('monday_hours');
            }
            if (!Schema::hasColumn('businesses', 'wednesday_hours')) {
                $table->string('wednesday_hours', 20)->default('09:00-18:00')->after('tuesday_hours');
            }
            if (!Schema::hasColumn('businesses', 'thursday_hours')) {
                $table->string('thursday_hours', 20)->default('09:00-18:00')->after('wednesday_hours');
            }
            if (!Schema::hasColumn('businesses', 'friday_hours')) {
                $table->string('friday_hours', 20)->default('09:00-18:00')->after('thursday_hours');
            }
            if (!Schema::hasColumn('businesses', 'saturday_hours')) {
                $table->string('saturday_hours', 20)->default('09:00-18:00')->after('friday_hours');
            }
            if (!Schema::hasColumn('businesses', 'sunday_hours')) {
                $table->string('sunday_hours', 20)->default('09:00-18:00')->after('saturday_hours');
            }
            
            // Social media links
            if (!Schema::hasColumn('businesses', 'facebook_url')) {
                $table->string('facebook_url', 255)->nullable()->after('sunday_hours');
            }
            if (!Schema::hasColumn('businesses', 'instagram_url')) {
                $table->string('instagram_url', 255)->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('businesses', 'twitter_url')) {
                $table->string('twitter_url', 255)->nullable()->after('instagram_url');
            }
            
            // Additional contact information
            if (!Schema::hasColumn('businesses', 'email')) {
                $table->string('email', 255)->nullable()->after('twitter_url');
            }
            if (!Schema::hasColumn('businesses', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('businesses', 'website')) {
                $table->string('website', 255)->nullable()->after('phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            // Drop all the added columns
            $table->dropColumn([
                'check_in_time',
                'check_out_time',
                'policies',
                'amenities',
                'star_rating',
                'has_swimming_pool',
                'has_restaurant',
                'has_parking',
                'has_wifi',
                'latitude',
                'longitude',
                'monday_hours',
                'tuesday_hours',
                'wednesday_hours',
                'thursday_hours',
                'friday_hours',
                'saturday_hours',
                'sunday_hours',
                'facebook_url',
                'instagram_url',
                'twitter_url',
                'email',
                'phone',
                'website'
            ]);
        });
    }
};
