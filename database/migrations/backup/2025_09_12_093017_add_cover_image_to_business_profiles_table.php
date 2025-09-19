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
        Schema::table('business_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('business_profiles', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('business_permit_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('business_profiles', 'cover_image')) {
            Schema::table('business_profiles', function (Blueprint $table) {
                $table->dropColumn('cover_image');
            });
        }
    }
};
