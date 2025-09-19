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
        Schema::table("business_profiles", function (Blueprint $table) {
            if (!Schema::hasColumn("business_profiles", "profile_avatar")) {
                $table->string("profile_avatar")->nullable()->after("business_permit_path");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("business_profiles", function (Blueprint $table) {
            if (Schema::hasColumn("business_profiles", "profile_avatar")) {
                $table->dropColumn("profile_avatar");
            }
        });
    }
};
