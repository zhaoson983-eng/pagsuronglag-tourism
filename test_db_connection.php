<?php
echo "🔍 TESTING DATABASE CONNECTION...\n\n";

// Load Laravel
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "✅ Laravel loaded\n";

    // Test basic connection
    $pdo = DB::connection()->getPdo();
    echo "✅ PDO connection established\n";

    // Try to create a simple test table
    echo "🧪 Creating test table...\n";

    DB::statement("CREATE TABLE IF NOT EXISTS test_table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    echo "✅ Test table created successfully\n";

    // Insert test data
    DB::statement("INSERT INTO test_table (name) VALUES ('Test Record')");
    echo "✅ Test data inserted\n";

    // Read test data
    $result = DB::select("SELECT * FROM test_table");
    echo "✅ Test data retrieved: " . $result[0]->name . "\n";

    // Clean up
    DB::statement("DROP TABLE test_table");
    echo "✅ Test table cleaned up\n";

    echo "\n🎉 DATABASE CONNECTION WORKING PERFECTLY!\n";
    echo "🚀 Ready to run migrations\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n🔧 Connection Details:\n";
    echo "  - Driver: " . config('database.default') . "\n";
    echo "  - Host: " . config('database.connections.mysql.host') . "\n";
    echo "  - Database: " . config('database.connections.mysql.database') . "\n";
    echo "  - Username: " . config('database.connections.mysql.username') . "\n";
}
?>
