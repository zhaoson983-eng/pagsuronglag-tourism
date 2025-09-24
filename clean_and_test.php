<?php
echo "🧹 CLEANING UP EXISTING TABLES...\n\n";

// Load Laravel
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Get all table names
    $tables = DB::select("SHOW TABLES");
    $tableNames = [];

    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        $tableNames[] = $tableName;
    }

    echo "📋 Found " . count($tableNames) . " tables:\n";
    foreach ($tableNames as $tableName) {
        echo "  - $tableName\n";
    }

    echo "\n🗑️ Dropping all tables...\n";
    foreach ($tableNames as $tableName) {
        try {
            DB::statement("DROP TABLE `$tableName`");
            echo "  ✅ Dropped $tableName\n";
        } catch (Exception $e) {
            echo "  ⚠️ Could not drop $tableName: " . $e->getMessage() . "\n";
        }
    }

    echo "\n🧪 Testing fresh migration...\n";

    // Try to run just the first migration
    $exitCode = Artisan::call('migrate', ['--force' => true, '--step' => true]);

    if ($exitCode === 0) {
        echo "✅ First migration completed successfully\n";

        // Check what was created
        $newTables = DB::select("SHOW TABLES");
        echo "📋 New tables created:\n";
        foreach ($newTables as $table) {
            $tableName = array_values((array)$table)[0];
            echo "  - $tableName\n";
        }

    } else {
        echo "❌ Migration failed with exit code: $exitCode\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
