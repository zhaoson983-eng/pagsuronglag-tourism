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
        // Create orders table if it doesn't exist
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->constrained()->onDelete('cascade');
                $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
                $table->enum('status', ['pending', 'ready_for_pickup', 'cancelled', 'completed'])->default('pending');
                $table->string('pickup_time')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Create messages table if it doesn't exist
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('message');
                $table->timestamps();
            });
        }

        // Create feedback table if it doesn't exist
        if (!Schema::hasTable('feedback')) {
            Schema::create('feedback', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('rating');
                $table->text('comment')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop tables if they exist and are empty
        $tables = ['orders', 'messages', 'feedback'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                // Check if table is empty before dropping
                $count = DB::table($table)->count();
                if ($count === 0) {
                    Schema::dropIfExists($table);
                }
            }
        }
    }
};
