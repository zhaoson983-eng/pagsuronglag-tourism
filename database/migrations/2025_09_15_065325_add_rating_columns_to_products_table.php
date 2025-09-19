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
            if (!Schema::hasColumn('products', 'average_rating')) {
                $table->decimal('average_rating', 3, 2)->default(0)->after('image');
            }
            if (!Schema::hasColumn('products', 'total_ratings')) {
                $table->integer('total_ratings')->default(0)->after('average_rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['average_rating', 'total_ratings']);
        });
    }
};
