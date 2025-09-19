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
            if (!Schema::hasColumn('businesses', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('is_published');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('businesses', 'is_approved')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('is_approved');
            });
        }
    }
};
