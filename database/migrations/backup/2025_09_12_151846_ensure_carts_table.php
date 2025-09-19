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
        // First, create the products table if it doesn't exist
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->timestamps();
            });
        }

        // Then create or update the carts table
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->decimal('price', 10, 2);
                $table->json('options')->nullable();
                $table->timestamps();
                
                // Add unique constraint to prevent duplicate cart items
                $table->unique(['user_id', 'product_id']);
            });
        } else {
            // Table exists, add any missing columns
            Schema::table('carts', function (Blueprint $table) {
                $columns = [
                    'user_id' => 'foreignId',
                    'product_id' => 'foreignId',
                    'quantity' => 'integer',
                    'price' => 'decimal',
                    'options' => 'json',
                ];

                foreach ($columns as $column => $type) {
                    if (!Schema::hasColumn('carts', $column)) {
                        if ($type === 'foreignId') {
                            $table->foreignId($column)->constrained($column === 'product_id' ? 'products' : 'users')->onDelete('cascade');
                        } else {
                            $table->$type($column, $type === 'decimal' ? [10, 2] : [])->nullable($column === 'options');
                        }
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a safe migration that doesn't drop any data
        // If you need to rollback, create a separate migration
    }
};
