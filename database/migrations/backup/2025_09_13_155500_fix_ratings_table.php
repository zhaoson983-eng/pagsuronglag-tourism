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
        // First, drop any existing foreign key constraints that might reference the ratings table
        Schema::table('ratings', function (Blueprint $table) {
            // This will drop the foreign key constraint if it exists
            $table->dropForeign(['business_id']);
            
            // Drop the unique constraint if it exists
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('ratings');
            
            if (array_key_exists('ratings_user_id_business_id_unique', $indexesFound)) {
                $table->dropUnique('ratings_user_id_business_id_unique');
            }
        });

        // Now add the product_id column
        if (!Schema::hasColumn('ratings', 'product_id')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->foreignId('product_id')
                      ->nullable()
                      ->after('business_id')
                      ->constrained('products')
                      ->onDelete('cascade');
            });
        }

        // Recreate the foreign key constraint for business_id
        Schema::table('ratings', function (Blueprint $table) {
            $table->foreign('business_id')
                  ->references('id')
                  ->on('businesses')
                  ->onDelete('cascade');
        });

        // Add a new unique constraint that includes product_id
        Schema::table('ratings', function (Blueprint $table) {
            $table->unique(['user_id', 'business_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            // Drop the new unique constraint
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('ratings');
            
            if (array_key_exists('ratings_user_id_business_id_product_id_unique', $indexesFound)) {
                $table->dropUnique('ratings_user_id_business_id_product_id_unique');
            }
            
            // Drop the product_id column if it exists
            if (Schema::hasColumn('ratings', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            
            // Recreate the original unique constraint
            $table->unique(['user_id', 'business_id']);
        });
    }
};
