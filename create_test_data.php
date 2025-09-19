<?php
// Create test data for checkout testing
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Business;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

echo "=== CREATING TEST DATA ===\n";

// Get or create a customer user
$customer = User::where('role', 'customer')->first();
if (!$customer) {
    echo "No customer found, creating one...\n";
    $customer = User::create([
        'name' => 'Test Customer',
        'email' => 'customer@test.com',
        'password' => bcrypt('password'),
        'role' => 'customer',
        'email_verified_at' => now(),
    ]);
}
echo "Customer: {$customer->name} (ID: {$customer->id})\n";

// Get or create a business owner
$businessOwner = User::where('role', 'business_owner')->first();
if (!$businessOwner) {
    echo "No business owner found, creating one...\n";
    $businessOwner = User::create([
        'name' => 'Test Business Owner',
        'email' => 'owner@test.com',
        'password' => bcrypt('password'),
        'role' => 'business_owner',
        'email_verified_at' => now(),
    ]);
}
echo "Business Owner: {$businessOwner->name} (ID: {$businessOwner->id})\n";

// Get or create a business
$business = Business::where('owner_id', $businessOwner->id)->first();
if (!$business) {
    echo "No business found, creating one...\n";
    $business = Business::create([
        'name' => 'Test Restaurant',
        'owner_id' => $businessOwner->id,
        'business_type' => 'restaurant',
        'status' => 'approved',
    ]);
}
echo "Business: {$business->name} (ID: {$business->id})\n";

// Create test products
$product1 = Product::where('business_id', $business->id)->where('name', 'Test Burger')->first();
if (!$product1) {
    echo "Creating test product 1...\n";
    $product1 = Product::create([
        'name' => 'Test Burger',
        'description' => 'Delicious test burger',
        'price' => 150.00,
        'business_id' => $business->id,
        'category' => 'food',
        'status' => 'active',
    ]);
}

$product2 = Product::where('business_id', $business->id)->where('name', 'Test Pizza')->first();
if (!$product2) {
    echo "Creating test product 2...\n";
    $product2 = Product::create([
        'name' => 'Test Pizza',
        'description' => 'Amazing test pizza',
        'price' => 250.00,
        'business_id' => $business->id,
        'category' => 'food',
        'status' => 'active',
    ]);
}

echo "Products created: {$product1->name} (₱{$product1->price}), {$product2->name} (₱{$product2->price})\n";

// Clear existing cart items for this customer
Cart::where('user_id', $customer->id)->delete();

// Add items to cart
echo "Adding items to cart...\n";
$cart1 = Cart::create([
    'user_id' => $customer->id,
    'product_id' => $product1->id,
    'quantity' => 2,
    'selected_flavor' => 'Spicy',
]);

$cart2 = Cart::create([
    'user_id' => $customer->id,
    'product_id' => $product2->id,
    'quantity' => 1,
    'selected_flavor' => 'Cheese',
]);

echo "Cart items created:\n";
echo "- {$product1->name} x{$cart1->quantity} (Flavor: {$cart1->selected_flavor})\n";
echo "- {$product2->name} x{$cart2->quantity} (Flavor: {$cart2->selected_flavor})\n";

$total = ($product1->price * $cart1->quantity) + ($product2->price * $cart2->quantity);
echo "Total cart value: ₱{$total}\n";

echo "\n=== TEST DATA READY ===\n";
echo "Customer ID: {$customer->id}\n";
echo "Business ID: {$business->id}\n";
echo "Cart items: " . Cart::where('user_id', $customer->id)->count() . "\n";
echo "You can now test checkout at: /cart/checkout/{$business->id}\n";
