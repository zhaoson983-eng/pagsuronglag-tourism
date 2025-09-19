<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\TouristSpot;

// Get or create a user to be the uploader
$user = User::first();
if (!$user) {
    $user = User::create([
        'name' => 'Site Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
    ]);
}

// Create a sample tourist spot
$touristSpot = TouristSpot::create([
    'name' => 'Caguiscan River',
    'description' => 'A beautiful river located in Loho Lagonoy Camarines Sur. Perfect for swimming, kayaking, and enjoying the natural beauty of the area. The crystal-clear waters and lush surroundings make it an ideal spot for nature lovers and adventure seekers.',
    'location' => 'Loho Lagonoy Camarines Sur',
    'additional_info' => 'Best visited during dry season (November to April). Swimming is allowed but please be careful of strong currents. Local guides are available for hire.',
    'map_link' => 'https://maps.google.com',
    'average_rating' => 4.5,
    'total_ratings' => 12,
    'is_active' => true,
    'uploaded_by' => $user->id,
]);

echo "Sample tourist spot created successfully!\n";
echo "ID: " . $touristSpot->id . "\n";
echo "Name: " . $touristSpot->name . "\n";
echo "Description: " . substr($touristSpot->description, 0, 100) . "...\n";
