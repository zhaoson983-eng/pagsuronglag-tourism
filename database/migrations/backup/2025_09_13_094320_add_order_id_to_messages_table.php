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
        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                if (!Schema::hasColumn('messages', 'order_id')) {
                    $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('messages', 'content')) {
                    $table->text('content')->nullable();
                }
            });
            
            // Rename message column to content if it exists (separate operation)
            if (Schema::hasColumn('messages', 'message') && !Schema::hasColumn('messages', 'content')) {
                Schema::table('messages', function (Blueprint $table) {
                    $table->renameColumn('message', 'content');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
        });
    }
};
