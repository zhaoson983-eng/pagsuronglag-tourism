<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Checking if resorts table exists:\n";

    $tables = ['resorts', 'businesses', 'business_profiles'];
    foreach($tables as $table) {
        try {
            $count = DB::table($table)->count();
            echo "$table: $count records\n";
        } catch (Exception $e) {
            echo "$table: TABLE DOES NOT EXIST\n";
        }
    }

    echo "\nChecking foreign key constraints:\n";
    $fks = DB::select("
        SELECT
            TABLE_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM
            information_schema.KEY_COLUMN_USAGE
        WHERE
            TABLE_NAME = 'resort_rooms'
            AND REFERENCED_TABLE_NAME IS NOT NULL
    ");

    foreach($fks as $fk) {
        echo "{$fk->TABLE_NAME}.{$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
