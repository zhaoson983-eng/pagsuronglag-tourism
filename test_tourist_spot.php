<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TouristSpot;

// Check if tourist spot exists
$spot = TouristSpot::find(2);
if ($spot) {
    echo "Tourist Spot ID 2 exists:\n";
    echo "Name: " . $spot->name . "\n";
    echo "Description: " . substr($spot->description, 0, 100) . "...\n";
    echo "Uploader ID: " . $spot->uploaded_by . "\n";
} else {
    echo "Tourist Spot ID 2 does not exist\n";
    
    // Check what tourist spots do exist
    $spots = TouristSpot::all();
    echo "Available tourist spots:\n";
    foreach ($spots as $s) {
        echo "ID: " . $s->id . " - Name: " . $s->name . "\n";
    }
}
