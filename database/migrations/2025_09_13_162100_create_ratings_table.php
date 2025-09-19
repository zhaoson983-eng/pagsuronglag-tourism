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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('business_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('rating')->unsigned()->check('rating >= 1 AND rating <= 5');
            $table->text('review')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['business_id', 'rating']);
            $table->index(['product_id', 'rating']);
            $table->index('user_id');
            
            // Ensure user can only rate a business/product once
            $table->unique(['user_id', 'business_id']);
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
