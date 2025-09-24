<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Resorts table structure:\n";
    $columns = Schema::getColumnListing('resorts');
    foreach($columns as $col) {
        echo "- $col\n";
    }

    echo "\nBusinesses table structure:\n";
    $columns = Schema::getColumnListing('businesses');
    foreach($columns as $col) {
        echo "- $col\n";
    }

    echo "\nChecking relationships:\n";
    $businessCount = DB::table('businesses')->count();
    $resortCount = DB::table('resorts')->count();
    echo "businesses has $businessCount records\n";
    echo "resorts has $resortCount records\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
