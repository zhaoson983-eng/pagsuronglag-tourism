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
            if (!Schema::hasColumn('businesses', 'email')) {
                $table->string('email')->nullable()->after('contact_number');
            }
            if (!Schema::hasColumn('businesses', 'website')) {
                $table->string('website')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            if (Schema::hasColumn('businesses', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('businesses', 'website')) {
                $table->dropColumn('website');
            }
        });
    }
};
