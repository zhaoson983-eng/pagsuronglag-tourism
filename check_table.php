<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Current resort_rooms table structure:\n";

    $columns = Schema::getColumnListing('resort_rooms');
    foreach($columns as $col) {
        echo "- $col\n";
    }

    echo "\nForeign keys:\n";
    $fks = DB::select('SELECT COLUMN_NAME, REFERENCED_TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = "resort_rooms" AND REFERENCED_TABLE_NAME IS NOT NULL');
    foreach($fks as $fk) {
        echo "- {$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}\n";
    }

    echo "\nChecking if table exists and has data:\n";
    $count = DB::table('resort_rooms')->count();
    echo "resort_rooms has $count records\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
