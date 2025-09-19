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
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
                $table->text('message');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                
                // Add indexes for better performance
                $table->index(['receiver_id', 'read_at']);
                $table->index('sender_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table in production to prevent data loss
        if (app()->environment('local', 'testing')) {
            Schema::dropIfExists('messages');
        }
    }
};
