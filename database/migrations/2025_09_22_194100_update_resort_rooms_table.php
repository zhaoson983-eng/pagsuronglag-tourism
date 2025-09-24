<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('resort_rooms', function (Blueprint $table) {
            // Rename business_id to resort_id if it exists
            if (Schema::hasColumn('resort_rooms', 'business_id')) {
                $table->renameColumn('business_id', 'resort_id');
            }

            // Rename room_number to room_name if it exists
            if (Schema::hasColumn('resort_rooms', 'room_number')) {
                $table->renameColumn('room_number', 'room_name');
            }

            // Add any missing columns
            if (!Schema::hasColumn('resort_rooms', 'deleted_at')) {
                $table->softDeletes();
            }

            // Update foreign key constraint to reference businesses table instead of resorts
            $table->dropForeign(['resort_id']);
            $table->foreign('resort_id')
                  ->references('id')
                  ->on('businesses')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('resort_rooms', function (Blueprint $table) {
            // Revert the foreign key constraint
            $table->dropForeign(['resort_id']);
            $table->foreign('resort_id')
                  ->references('id')
                  ->on('resorts')
                  ->onDelete('cascade');
            
            // Revert the changes if needed
            if (Schema::hasColumn('resort_rooms', 'resort_id')) {
                $table->renameColumn('resort_id', 'business_id');
            }
            if (Schema::hasColumn('resort_rooms', 'room_name')) {
                $table->renameColumn('room_name', 'room_number');
            }
        });
    }
};
