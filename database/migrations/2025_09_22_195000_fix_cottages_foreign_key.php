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
        // First, drop the existing foreign key constraint if it exists
        Schema::table('cottages', function (Blueprint $table) {
            // Check if the foreign key exists before trying to drop it
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = collect($sm->listTableForeignKeys('cottages'))
                ->filter(function($fk) {
                    return in_array('business_id', $fk->getLocalColumns());
                });

            if ($foreignKeys->isNotEmpty()) {
                $table->dropForeign(['business_id']);
            }
        });

        // Then add the new foreign key constraint
        Schema::table('cottages', function (Blueprint $table) {
            $table->foreign('business_id')
                  ->references('id')
                  ->on('business_profiles')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint
        Schema::table('cottages', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
        });

        // Add back the original foreign key constraint (if needed)
        Schema::table('cottages', function (Blueprint $table) {
            $table->foreign('business_id')
                  ->references('id')
                  ->on('businesses')
                  ->onDelete('cascade');
        });
    }
};
