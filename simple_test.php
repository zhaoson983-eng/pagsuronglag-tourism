<?php
// Simple test to add cart items manually
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== SIMPLE CART TEST ===\n";

// Check existing products
$products = DB::table('products')->where('business_id', 1)->get();
echo "Products for business 1: " . $products->count() . "\n";

if ($products->count() > 0) {
    $product = $products->first();
    echo "Using product: {$product->name} (ID: {$product->id})\n";
    
    // Clear existing cart items for user 4
    DB::table('carts')->where('user_id', 4)->delete();
    
    // Add cart item manually
    $cartId = DB::table('carts')->insertGetId([
        'user_id' => 4,
        'product_id' => $product->id,
        'quantity' => 2,
        'price' => $product->price,
        'selected_flavor' => 'Original',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "Cart item created with ID: {$cartId}\n";
    
    // Verify cart item
    $cartItem = DB::table('carts')->where('id', $cartId)->first();
    echo "Cart item verified: User {$cartItem->user_id}, Product {$cartItem->product_id}, Qty {$cartItem->quantity}\n";
    
} else {
    echo "No products found for business 1\n";
    
    // Check all products
    $allProducts = DB::table('products')->get();
    echo "Total products in database: " . $allProducts->count() . "\n";
    
    if ($allProducts->count() > 0) {
        foreach ($allProducts->take(3) as $p) {
            echo "- Product: {$p->name} (Business: {$p->business_id})\n";
        }
    }
}

echo "=== END TEST ===\n";
