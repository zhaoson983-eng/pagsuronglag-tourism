<?php
echo "🔍 CHECKING MIGRATION STATUS...\n\n";

// Load Laravel
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $migrations = DB::table('migrations')->orderBy('batch', 'asc')->orderBy('migration', 'asc')->get();

    echo "📋 Migration Status:\n";
    echo "Total migrations: " . $migrations->count() . "\n\n";

    foreach ($migrations as $migration) {
        echo "✅ " . basename($migration->migration, '.php') . " (Batch " . $migration->batch . ")\n";
    }

    echo "\n📊 Checking for pending migrations...\n";
    $allMigrationFiles = collect(File::glob(database_path('migrations/*.php')))
        ->map(function($path) {
            return basename($path, '.php');
        })
        ->sort();

    $runMigrations = $migrations->pluck('migration')->map(function($migration) {
        return basename($migration, '.php');
    });

    $pendingMigrations = $allMigrationFiles->diff($runMigrations);

    if ($pendingMigrations->count() > 0) {
        echo "⚠️ Pending migrations (" . $pendingMigrations->count() . "):\n";
        foreach ($pendingMigrations as $migration) {
            echo "  - " . $migration . "\n";
        }

        echo "\n🔧 Running pending migrations...\n";
        $exitCode = Artisan::call('migrate', ['--force' => true]);
        echo "✅ Migration completed with exit code: " . $exitCode . "\n";
    } else {
        echo "✅ All migrations are up to date!\n";
    }

    echo "\n🎉 Database setup complete!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
