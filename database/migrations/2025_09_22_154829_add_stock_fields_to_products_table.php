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
        // Check if the columns don't exist before adding them
        if (!Schema::hasColumn('products', 'stock_limit')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('stock_limit')->after('stock')->default(0);
            });
        }
        
        if (!Schema::hasColumn('products', 'current_stock')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('current_stock')->after('stock_limit')->default(0);
            });
            
            // Update existing stock to current_stock if current_stock is 0
            \DB::statement('UPDATE products SET current_stock = stock WHERE current_stock = 0');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'stock_limit')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('stock_limit');
            });
        }
        
        if (Schema::hasColumn('products', 'current_stock')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('current_stock');
            });
        }
    }
};
