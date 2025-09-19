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
        Schema::create('business_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_profile_id')->constrained()->onDelete('cascade');
            $table->date('date');
            
            // Visitor metrics
            $table->integer('page_views')->default(0);
            $table->integer('unique_visitors')->default(0);
            
            // Product metrics
            $table->integer('product_views')->default(0);
            $table->integer('product_clicks')->default(0);
            
            // Sales metrics
            $table->integer('orders_received')->default(0);
            $table->decimal('revenue', 10, 2)->default(0);
            $table->decimal('average_order_value', 10, 2)->default(0);
            
            // Conversion metrics
            $table->decimal('conversion_rate', 5, 2)->default(0);
            
            // Customer metrics
            $table->integer('new_customers')->default(0);
            $table->integer('returning_customers')->default(0);
            
            $table->timestamps();
            
            // Add composite unique index
            $table->unique(['business_profile_id', 'date']);
            
            // Add index for faster date-based queries
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_statistics', function (Blueprint $table) {
            $table->dropForeign(['business_profile_id']);
            $table->dropUnique(['business_profile_id', 'date']);
            $table->dropIndex(['date']);
        });
        
        Schema::dropIfExists('business_statistics');
    }
};
