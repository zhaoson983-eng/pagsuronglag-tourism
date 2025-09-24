<?php
require_once 'vendor/autoload.php';

try {
    echo "🔍 CHECKING DATABASE CONNECTION...\n\n";

    // Test database connection
    $pdo = DB::connection()->getPdo();
    $dbName = DB::connection()->getDatabaseName();

    echo "✅ Database Connection: SUCCESS\n";
    echo "📊 Database Type: " . DB::connection()->getDriverName() . "\n";
    echo "🗄️ Database Name: " . $dbName . "\n";
    echo "🌐 Host: " . DB::connection()->getConfig('host') . "\n";
    echo "🔌 Port: " . DB::connection()->getConfig('port') . "\n";

    // Check if tables exist
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "\n📋 Tables found (" . count($tables) . "):\n";
    foreach ($tables as $table) {
        echo "  - " . $table . "\n";
    }

    // Check migration status
    echo "\n🔄 Migration Status:\n";
    if (in_array('migrations', $tables)) {
        $migrations = $pdo->query("SELECT migration, batch FROM migrations ORDER BY batch ASC, migration ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo "  - Migrations table exists\n";
        echo "  - Total migrations: " . count($migrations) . "\n";

        if (count($migrations) > 0) {
            echo "  - Latest batch: " . end($migrations)['batch'] . "\n";
            echo "  - Recent migrations:\n";
            $recent = array_slice($migrations, -5);
            foreach ($recent as $migration) {
                echo "    * " . basename($migration['migration'], '.php') . " (batch " . $migration['batch'] . ")\n";
            }
        }
    } else {
        echo "  - ❌ Migrations table not found\n";
    }

    // Test a few key tables
    echo "\n🧪 Key Tables Check:\n";
    $keyTables = ['users', 'businesses', 'products', 'orders'];
    foreach ($keyTables as $table) {
        if (in_array($table, $tables)) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "  - ✅ $table: $count records\n";
        } else {
            echo "  - ❌ $table: not found\n";
        }
    }

    // Check Laravel version
    echo "\n🛠️ Laravel Version: " . app()->version() . "\n";

} catch (Exception $e) {
    echo "❌ Database Connection: FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";

    // Try to get more details
    echo "\n🔧 Connection Config:\n";
    echo "  - Driver: " . config('database.default') . "\n";
    try {
        echo "  - Host: " . config('database.connections.' . config('database.default') . '.host') . "\n";
        echo "  - Port: " . config('database.connections.' . config('database.default') . '.port') . "\n";
        echo "  - Database: " . config('database.connections.' . config('database.default') . '.database') . "\n";
    } catch (Exception $configError) {
        echo "  - Config Error: " . $configError->getMessage() . "\n";
    }
}
?>
