<?php
echo "🗑️ RESETTING AND REBUILDING DATABASE...\n\n";

// Load Laravel
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "1️⃣ Dropping all tables...\n";
    $tables = ['migrations', 'users', 'businesses', 'products', 'orders', 'messages', 'profiles', 'carts', 'cottages', 'hotels', 'resorts', 'galleries'];

    foreach ($tables as $table) {
        try {
            DB::statement("DROP TABLE IF EXISTS `$table`");
            echo "   ✅ Dropped $table\n";
        } catch (Exception $e) {
            echo "   ⚠️ Could not drop $table: " . $e->getMessage() . "\n";
        }
    }

    echo "\n2️⃣ Clearing migration records...\n";
    DB::table('migrations')->truncate();
    echo "   ✅ Migration records cleared\n";

    echo "\n3️⃣ Running fresh migrations...\n";
    $exitCode = Artisan::call('migrate', ['--force' => true]);

    if ($exitCode === 0) {
        echo "   ✅ Migrations completed successfully\n";
    } else {
        echo "   ❌ Migration failed with exit code: $exitCode\n";
        return;
    }

    echo "\n4️⃣ Running seeders...\n";
    $seedExitCode = Artisan::call('db:seed', ['--force' => true]);

    if ($seedExitCode === 0) {
        echo "   ✅ Database seeded successfully\n";
    } else {
        echo "   ❌ Seeding failed with exit code: $seedExitCode\n";
    }

    echo "\n5️⃣ Verifying database...\n";
    $userCount = DB::table('users')->count();
    $businessCount = DB::table('businesses')->count();
    $productCount = DB::table('products')->count();

    echo "   ✅ Users: $userCount\n";
    echo "   ✅ Businesses: $businessCount\n";
    echo "   ✅ Products: $productCount\n";

    echo "\n🎉 DATABASE COMPLETELY REBUILT!\n";
    echo "📊 Your Pagsurong Lagonoy tourism platform is ready!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n🔧 Manual steps needed:\n";
    echo "   - Run: php artisan migrate:fresh --seed --force\n";
    echo "   - Check your .env file for correct database credentials\n";
    echo "   - Ensure MySQL server is running\n";
}
?>
