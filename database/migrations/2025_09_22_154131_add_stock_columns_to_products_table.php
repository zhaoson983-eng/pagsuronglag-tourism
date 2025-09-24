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
            $table->integer('stock_limit')->after('stock_quantity')->default(0);
            $table->integer('current_stock')->after('stock_limit')->default(0);
            
            // Update existing stock_quantity to current_stock
            \DB::statement('UPDATE products SET current_stock = stock_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock_limit', 'current_stock']);
        });
    }
};
