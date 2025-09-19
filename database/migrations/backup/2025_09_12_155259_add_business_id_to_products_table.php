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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'business_id')) {
                $table->foreignId('business_id')->after('id')->constrained('businesses')->onDelete('cascade');
                $table->index(['business_id', 'is_active']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'business_id')) {
                $table->dropForeign(['business_id']);
                $table->dropColumn('business_id');
                $table->dropIndex(['business_id', 'is_active']);
            }
        });
    }
};
