<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Route::get('/check-db', function () {
    try {
        // Check if table exists
        if (!Schema::hasTable('business_profiles')) {
            return 'Table business_profiles does not exist';
        }

        // Get columns
        $columns = Schema::getColumnListing('business_profiles');
        
        // Get foreign keys
        $foreignKeys = collect(DB::select(
            "SELECT 
                COLUMN_NAME, 
                REFERENCED_TABLE_NAME, 
                REFERENCED_COLUMN_NAME
            FROM 
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE 
                TABLE_SCHEMA = ? 
                AND TABLE_NAME = 'business_profiles' 
                AND REFERENCED_TABLE_NAME IS NOT NULL",
            [config('database.connections.mysql.database')]
        ));

        return [
            'columns' => $columns,
            'foreign_keys' => $foreignKeys->toArray()
        ];
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
});
