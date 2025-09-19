<?php
// Quick test script to check database and checkout flow
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== CHECKOUT DEBUG TEST ===\n";

// Check orders table
echo "Checking orders table...\n";
$orders = DB::table('orders')->get();
echo "Total orders in database: " . $orders->count() . "\n";

if ($orders->count() > 0) {
    echo "Recent orders:\n";
    foreach ($orders->take(3) as $order) {
        echo "- Order ID: {$order->id}, Customer: {$order->customer_id}, Business: {$order->business_id}, Status: {$order->status}\n";
    }
}

// Check cart items
echo "\nChecking cart items...\n";
$cartItems = DB::table('carts')->get();
echo "Total cart items: " . $cartItems->count() . "\n";

if ($cartItems->count() > 0) {
    echo "Cart items:\n";
    foreach ($cartItems->take(5) as $item) {
        echo "- Cart ID: {$item->id}, User: {$item->user_id}, Product: {$item->product_id}, Qty: {$item->quantity}\n";
    }
}

// Check users
echo "\nChecking users...\n";
$users = DB::table('users')->where('role', 'customer')->get();
echo "Customer users: " . $users->count() . "\n";

if ($users->count() > 0) {
    $user = $users->first();
    echo "Sample customer: ID {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
    
    // Check if this user has cart items
    $userCartItems = DB::table('carts')->where('user_id', $user->id)->get();
    echo "Cart items for user {$user->id}: " . $userCartItems->count() . "\n";
}

echo "\n=== END DEBUG TEST ===\n";
