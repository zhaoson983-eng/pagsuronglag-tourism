<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create businesses table if it doesn't exist
        if (!Schema::hasTable('businesses')) {
            Schema::create('businesses', function (Blueprint $table) {
                $table->id();
                $table->string('business_type')->default('local_products');
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('website')->nullable();
                $table->string('logo')->nullable();
                $table->string('banner')->nullable();
                $table->decimal('average_rating', 3, 1)->default(0);
                $table->unsignedInteger('total_ratings')->default(0);
                $table->unsignedBigInteger('owner_id');
                $table->timestamps();

                $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Create products table if it doesn't exist
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->unsignedInteger('stock')->default(0);
                $table->string('image')->nullable();
                $table->unsignedBigInteger('business_id');
                $table->decimal('average_rating', 3, 1)->default(0);
                $table->unsignedInteger('total_ratings')->default(0);
                $table->timestamps();

                $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            });
        }

        // Create ratings table if it doesn't exist
        if (!Schema::hasTable('ratings')) {
            Schema::create('ratings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('business_id')->nullable();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->unsignedTinyInteger('rating');
                $table->text('comment')->nullable();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            });

            // Add check constraint to ensure a rating is either for a business or a product
            if (env('DB_CONNECTION') === 'mysql') {
                DB::statement('ALTER TABLE ratings ADD CONSTRAINT chk_rating_type CHECK ((business_id IS NOT NULL AND product_id IS NULL) OR (business_id IS NULL AND product_id IS NOT NULL))');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order of creation
        Schema::dropIfExists('ratings');
        Schema::dropIfExists('products');
        Schema::dropIfExists('businesses');
    }
};
