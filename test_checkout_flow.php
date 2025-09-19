<?php
// Test the actual checkout flow
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

echo "=== TESTING CHECKOUT FLOW ===\n";

// Simulate authentication
$customer = User::find(4);
Auth::login($customer);
echo "Logged in as: {$customer->name} (ID: {$customer->id})\n";

// Check cart items
$cartItems = DB::table('carts')
    ->join('products', 'carts.product_id', '=', 'products.id')
    ->where('carts.user_id', $customer->id)
    ->select('carts.*', 'products.name as product_name', 'products.business_id')
    ->get();

echo "Cart items found: " . $cartItems->count() . "\n";

if ($cartItems->count() > 0) {
    foreach ($cartItems as $item) {
        echo "- {$item->product_name} x{$item->quantity} (Business: {$item->business_id})\n";
    }
    
    $businessId = $cartItems->first()->business_id;
    echo "\nTesting checkout for business ID: {$businessId}\n";
    
    // Create a mock request
    $request = new Request();
    $request->merge([
        'pickup_time' => 'Tomorrow 2:00 PM',
        'notes' => 'Test order from script'
    ]);
    
    // Test the checkout method
    try {
        $controller = new OrderController();
        $response = $controller->checkout($request, $businessId);
        
        echo "Checkout response type: " . get_class($response) . "\n";
        
        // Check if orders were created
        $orders = DB::table('orders')->where('customer_id', $customer->id)->get();
        echo "Orders created: " . $orders->count() . "\n";
        
        if ($orders->count() > 0) {
            $order = $orders->first();
            echo "Order ID: {$order->id}, Status: {$order->status}, Total: {$order->total_amount}\n";
            
            // Check order items
            $orderItems = DB::table('order_items')->where('order_id', $order->id)->get();
            echo "Order items: " . $orderItems->count() . "\n";
            
            // Check if cart was cleared
            $remainingCartItems = DB::table('carts')->where('user_id', $customer->id)->get();
            echo "Remaining cart items: " . $remainingCartItems->count() . "\n";
            
            // Check messages
            $messages = DB::table('messages')->where('order_id', $order->id)->get();
            echo "Messages created: " . $messages->count() . "\n";
        }
        
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
    
} else {
    echo "No cart items found. Run simple_test.php first to create test data.\n";
}

echo "\n=== END CHECKOUT TEST ===\n";
