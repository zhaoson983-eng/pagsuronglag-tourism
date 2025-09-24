<?php
echo "🔍 TESTING LARAVEL DATABASE CONNECTION...\n\n";

// Load Laravel
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "✅ Laravel loaded successfully\n";

    // Test database connection
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connection established\n";

    // Check if our tables exist
    $tables = DB::select("SHOW TABLES");
    echo "📋 Tables found: " . count($tables) . "\n";

    // Check sample data
    $userCount = DB::table('users')->count();
    $businessCount = DB::table('businesses')->count();
    $productCount = DB::table('products')->count();

    echo "📊 Sample Data:\n";
    echo "  - Users: $userCount\n";
    echo "  - Businesses: $businessCount\n";
    echo "  - Products: $productCount\n";

    if ($userCount > 0) {
        $sampleUser = DB::table('users')->first();
        echo "  - Sample user: " . $sampleUser->name . "\n";
    }

    echo "\n🎉 CONNECTION SUCCESSFUL!\n";
    echo "🚀 Your Pagsurong Lagonoy database is ready!\n";

} catch (Exception $e) {
    echo "❌ Connection Failed: " . $e->getMessage() . "\n";

    echo "\n🔧 Troubleshooting:\n";
    echo "  - Check your .env file for correct database credentials\n";
    echo "  - Ensure MySQL server is running\n";
    echo "  - Verify database 'pagsuronglag' exists\n";
    echo "  - Make sure the SQL file was imported successfully\n";
}
?>
