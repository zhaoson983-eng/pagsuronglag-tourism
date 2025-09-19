<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('business_profiles') && !Schema::hasColumn('business_profiles', 'cover_image')) {
            Schema::table('business_profiles', function (Blueprint $table) {
                $table->string('cover_image')->nullable()->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('business_profiles') && Schema::hasColumn('business_profiles', 'cover_image')) {
            Schema::table('business_profiles', function (Blueprint $table) {
                $table->dropColumn('cover_image');
            });
        }
    }
};
