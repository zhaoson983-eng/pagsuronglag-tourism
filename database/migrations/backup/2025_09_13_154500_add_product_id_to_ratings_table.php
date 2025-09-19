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
        Schema::table('ratings', function (Blueprint $table) {
            $table->foreignId('product_id')
                  ->nullable()
                  ->after('business_id')
                  ->constrained('products')
                  ->onDelete('cascade');
            
            // Drop the unique constraint on user_id and business_id since we'll have product ratings too
            $table->dropUnique(['user_id', 'business_id']);
            
            // Add a unique constraint for business ratings (product_id is null)
            $table->unique(['user_id', 'business_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropUnique(['user_id', 'business_id', 'product_id']);
            $table->dropColumn('product_id');
            
            // Restore the original unique constraint
            $table->unique(['user_id', 'business_id']);
        });
    }
};
