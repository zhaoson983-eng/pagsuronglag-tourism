<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/test-db', function () {
    try {
        // Test database connection
        DB::connection()->getPdo();
        $connected = true;
    } catch (\Exception $e) {
        $connected = false;
        return response()->json([
            'connected' => false,
            'error' => $e->getMessage(),
        ]);
    }

    // Get list of all tables
    $tables = DB::select('SHOW TABLES');
    $tableList = [];
    $key = 'Tables_in_' . env('DB_DATABASE');
    
    foreach ($tables as $table) {
        $tableName = $table->$key;
        $columns = Schema::getColumnListing($tableName);
        $tableList[$tableName] = $columns;
    }

    return response()->json([
        'connected' => true,
        'tables' => $tableList,
    ]);
});
