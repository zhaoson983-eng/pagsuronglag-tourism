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
        Schema::table('carts', function (Blueprint $table) {
            // Add product_id column if it doesn't exist
            if (!Schema::hasColumn('carts', 'product_id')) {
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
            }
            
            // Add price column if it doesn't exist
            if (!Schema::hasColumn('carts', 'price')) {
                $table->decimal('price', 10, 2);
            }
            
            // Add quantity column if it doesn't exist
            if (!Schema::hasColumn('carts', 'quantity')) {
                $table->integer('quantity')->default(1);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('carts', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            if (Schema::hasColumn('carts', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('carts', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }
};
