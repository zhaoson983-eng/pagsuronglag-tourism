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
        Schema::table('galleries', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->constrained('resort_rooms')->onDelete('cascade');
            $table->foreignId('cottage_id')->nullable()->constrained('cottages')->onDelete('cascade');
            $table->string('room_type')->nullable(); // 'resort', 'hotel', 'cottage'
            
            $table->index(['room_id']);
            $table->index(['cottage_id']);
            $table->index(['room_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropForeign(['cottage_id']);
            $table->dropIndex(['room_id']);
            $table->dropIndex(['cottage_id']);
            $table->dropIndex(['room_type']);
            $table->dropColumn(['room_id', 'cottage_id', 'room_type']);
        });
    }
};
