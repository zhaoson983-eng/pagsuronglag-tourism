<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration is just for verification
        if (Schema::hasTable('ratings')) {
            $columns = Schema::getColumnListing('ratings');
            echo "Ratings table columns: " . implode(', ', $columns) . "\n";
            
            // Check if foreign key constraints exist
            $foreignKeys = DB::select(
                "SELECT 
                    COLUMN_NAME, 
                    REFERENCED_TABLE_NAME, 
                    REFERENCED_COLUMN_NAME
                FROM 
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE 
                    TABLE_SCHEMA = ? 
                    AND TABLE_NAME = 'ratings' 
                    AND REFERENCED_TABLE_NAME IS NOT NULL",
                [config('database.connections.mysql.database')]
            );
            
            echo "Foreign keys:\n";
            foreach ($foreignKeys as $fk) {
                echo "- {$fk->COLUMN_NAME} references {$fk->REFERENCED_TABLE_NAME}({$fk->REFERENCED_COLUMN_NAME})\n";
            }
        } else {
            echo "Ratings table does not exist\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to do here
    }
};
