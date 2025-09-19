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
        if (env('DB_CONNECTION') === 'mysql' && Schema::hasTable('ratings')) {
            // Check if the constraint already exists
            $constraintExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'ratings' 
                AND CONSTRAINT_NAME = 'chk_rating_type'
            ");
            
            if (empty($constraintExists)) {
                DB::statement('ALTER TABLE ratings ADD CONSTRAINT chk_rating_type CHECK ((business_id IS NOT NULL AND product_id IS NULL) OR (business_id IS NULL AND product_id IS NOT NULL))');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (env('DB_CONNECTION') === 'mysql') {
            DB::statement('ALTER TABLE ratings DROP CONSTRAINT IF EXISTS chk_rating_type');
        }
    }
};
