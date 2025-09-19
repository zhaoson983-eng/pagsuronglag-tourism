<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BusinessProfile;

echo "Fixing resort ratings...\n";

$resorts = BusinessProfile::where('business_type', 'resort')->get();

foreach ($resorts as $resort) {
    echo "Resort ID: {$resort->id} - Before: {$resort->average_rating}\n";
    $resort->updateRating();
    $resort->refresh();
    echo "Resort ID: {$resort->id} - After: {$resort->average_rating}\n";
    echo "Total ratings: {$resort->total_ratings}\n";
    echo "---\n";
}

echo "Done!\n";
