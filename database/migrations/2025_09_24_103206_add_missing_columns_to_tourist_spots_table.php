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
            // Add missing columns that the model expects
            if (!Schema::hasColumn('tourist_spots', 'profile_avatar')) {
                $table->string('profile_avatar')->nullable()->after('description');
            }
            if (!Schema::hasColumn('tourist_spots', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('profile_avatar');
            }
            if (!Schema::hasColumn('tourist_spots', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('cover_image');
            }
            if (!Schema::hasColumn('tourist_spots', 'map_link')) {
                $table->string('map_link')->nullable()->after('gallery_images');
            }
            if (!Schema::hasColumn('tourist_spots', 'uploaded_by')) {
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('cascade')->after('total_ratings');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourist_spots', function (Blueprint $table) {
            $table->dropColumn(['profile_avatar', 'cover_image', 'gallery_images', 'map_link', 'uploaded_by']);
        });
    }
};
