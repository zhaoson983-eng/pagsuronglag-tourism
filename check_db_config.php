<?php
echo "🔍 CHECKING DATABASE CONFIGURATION...\n\n";

// Load Laravel
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "✅ Laravel loaded successfully\n";

// Check database configuration
$defaultConnection = config('database.default');
echo "📊 Default Database Connection: " . $defaultConnection . "\n";

if ($defaultConnection === 'mysql') {
    $mysqlConfig = config('database.connections.mysql');
    echo "🗄️ MySQL Configuration:\n";
    echo "  - Host: " . $mysqlConfig['host'] . "\n";
    echo "  - Port: " . $mysqlConfig['port'] . "\n";
    echo "  - Database: " . $mysqlConfig['database'] . "\n";
    echo "  - Username: " . $mysqlConfig['username'] . "\n";

    // Test MySQL connection
    try {
        $pdo = new PDO(
            "mysql:host=" . $mysqlConfig['host'] . ";port=" . $mysqlConfig['port'],
            $mysqlConfig['username'],
            $mysqlConfig['password']
        );
        echo "✅ MySQL Server Connection: SUCCESS\n";

        // Check if database exists
        $dbName = $mysqlConfig['database'];
        $result = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbName'");
        if ($result->fetch()) {
            echo "✅ Database '$dbName' exists\n";

            // Check tables
            $pdo->exec("USE $dbName");
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            echo "📋 Tables found (" . count($tables) . "):\n";
            foreach ($tables as $table) {
                echo "  - " . $table . "\n";
            }
        } else {
            echo "❌ Database '$dbName' does not exist\n";
            echo "📝 Available databases:\n";
            $dbs = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($dbs as $db) {
                echo "  - " . $db . "\n";
            }
        }
    } catch (Exception $e) {
        echo "❌ MySQL Connection Failed: " . $e->getMessage() . "\n";
    }
} elseif ($defaultConnection === 'sqlite') {
    $sqliteConfig = config('database.connections.sqlite');
    $dbPath = $sqliteConfig['database'];

    if (file_exists($dbPath)) {
        echo "🗄️ SQLite Database: " . realpath($dbPath) . "\n";
        echo "📊 File Size: " . filesize($dbPath) . " bytes\n";

        try {
            $pdo = new PDO('sqlite:' . $dbPath);
            $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table';")->fetchAll(PDO::FETCH_COLUMN);
            echo "📋 Tables found (" . count($tables) . "):\n";
            foreach ($tables as $table) {
                echo "  - " . $table . "\n";
            }
        } catch (Exception $e) {
            echo "❌ SQLite Connection Failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ SQLite database file not found: " . $dbPath . "\n";
    }
} else {
    echo "❌ Unknown database connection: " . $defaultConnection . "\n";
}

echo "\n🚀 RECOMMENDATIONS:\n";
if ($defaultConnection === 'sqlite') {
    echo "  - Currently using SQLite - Consider switching to MySQL for production\n";
} elseif ($defaultConnection === 'mysql') {
    echo "  - Currently using MySQL - Good choice for production\n";
}

echo "  - Run 'php artisan migrate:fresh' to reset and recreate all tables\n";
echo "  - Run 'php artisan db:seed' to populate with initial data\n";
?>
