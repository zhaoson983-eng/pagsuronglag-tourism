<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Business;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Place an order from the cart (all businesses)
     */
    public function orderFromCart(Request $request)
    {
        $user = Auth::user();

        $cartItems = Cart::with('product.business.owner')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')
                ->with('error', 'Your cart is empty.');
        }

        $groupedByBusiness = $cartItems->groupBy(fn ($item) => $item->product->business_id);

        DB::beginTransaction();

        try {
            foreach ($groupedByBusiness as $businessId => $items) {
                $this->createOrderAndNotify($items, $user, $request->notes);
            }

            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('customer.orders')
                ->with('success', 'Your orders have been placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    /**
     * Checkout items from a single business
     */
    public function checkout(Request $request, $businessId)
    {
        $user = Auth::user();
        
        DB::beginTransaction();
        
        try {
            $request->validate([
                'pickup_time' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
            ]);

            // Get cart items for this business
            $cartItems = Cart::with(['product.business.owner'])
                ->where('user_id', $user->id)
                ->whereHas('product', function ($query) use ($businessId) {
                    $query->where('business_id', $businessId);
                })
                ->get();

            if ($cartItems->isEmpty()) {
                DB::rollBack();
                return redirect()->route('customer.cart')
                    ->with('error', 'Your cart is empty for this business.');
            }

            // Calculate total
            $total = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . time() . '-' . $user->id,
                'user_id' => $user->id,
                'business_id' => $businessId,
                'subtotal' => $total,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => $request->input('notes', ''),
            ]);

            // Check stock availability and create order items
            foreach ($cartItems as $item) {
                $product = $item->product;
                
                // Check if enough stock is available
                if ($product->current_stock < $item->quantity) {
                    DB::rollBack();
                    return redirect()->route('customer.cart')
                        ->with('error', "Insufficient stock for {$product->name}. Only {$product->current_stock} items available.");
                }
                
                // Decrease stock
                $product->decreaseStock($item->quantity);
                
                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'selected_flavor' => $item->selected_flavor,
                ]);
            }

            // Send message to business owner
            $business = $cartItems->first()->product->business;
            \Log::info('Business found: ' . ($business ? $business->id : 'null'));
            \Log::info('Business owner: ' . ($business && $business->owner ? $business->owner->id : 'null'));
            
            if ($business && $business->owner) {
                $messageContent = "🛒 New Order #" . $order->id . "\n\n";
                $messageContent .= "Customer: {$user->name}\n";
                $messageContent .= "Pickup Time: " . ($request->input('pickup_time') ?: 'ASAP') . "\n";
                if ($request->input('notes')) {
                    $messageContent .= "Notes: " . $request->input('notes') . "\n";
                }
                $messageContent .= "\nOrder Details:\n";

                foreach ($cartItems as $item) {
                    $messageContent .= "• {$item->product->name}";
                    if ($item->selected_flavor) {
                        $messageContent .= " ({$item->selected_flavor})";
                    }
                    $messageContent .= " × {$item->quantity} = ₱" . number_format($item->product->price * $item->quantity, 2) . "\n";
                }

                $messageContent .= "\nTotal: ₱" . number_format($total, 2);

                try {
                    Message::create([
                        'sender_id' => $user->id,
                        'receiver_id' => $business->owner->id,
                        'content' => $messageContent,
                        'order_id' => $order->id,
                    ]);
                    \Log::info('Message created successfully');
                } catch (\Exception $e) {
                    \Log::error('Failed to create message: ' . $e->getMessage());
                    // Don't fail the order if message creation fails
                }
            } else {
                \Log::warning('Could not send message - business or owner not found');
            }

            // Clear cart items for this business
            Cart::where('user_id', $user->id)
                ->whereHas('product', function ($query) use ($businessId) {
                    $query->where('business_id', $businessId);
                })
                ->delete();
            
            DB::commit();

            return redirect()->route('customer.orders')
                ->with('success', "Order placed successfully! Order #" . $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order placement failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('customer.cart')
                ->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    /**
     * Create order and send message to business owner
     */
    private function createOrderAndNotify($items, $user, $orderData = [])
    {
        $businessId = $items->first()->product->business_id;

        $order = Order::create([
            'order_number' => 'ORD-' . time() . '-' . $user->id,
            'user_id' => $user->id,
            'business_id' => $businessId,
            'subtotal' => 0, // Will be updated below
            'total' => 0, // Will be updated below
            'status' => 'pending',
            'notes' => $orderData['notes'] ?? null,
        ]);

        $total = 0;

        foreach ($items as $item) {
            $price = $item->product->price;
            $subtotal = $price * $item->quantity;
            $total += $subtotal;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $price,
                'selected_flavor' => $item->selected_flavor ?? null,
            ]);
        }

        // Update order total
        $order->update([
            'subtotal' => $total,
            'total' => $total
        ]);

        // Notify business owner with full details
        $business = $items->first()->product->business;

        if ($business && $business->owner) {
            $notes = $orderData['notes'] ?? null;
            $messageContent = "🛒 New order received!\n\n";
            $messageContent .= "Customer: {$user->name}\n";
            $messageContent .= "Notes: " . ($notes ?? 'None') . "\n\n";
            $messageContent .= "Order Details:\n";

            foreach ($items as $item) {
                $messageContent .= "- {$item->product->name} ×{$item->quantity} @ ₱{$item->product->price}\n";
            }

            $messageContent .= "\nTotal: ₱" . number_format($total, 2);

            $message = Message::create([
                'sender_id' => $user->id,
                'receiver_id' => $business->owner->id,
                'message' => $messageContent,
                'order_id' => $order->id,
                'content' => $messageContent,
            ]);
        }
    }

    /**
     * Display all orders of the customer
     */
    public function myOrders()
    {
        $orders = Order::with(['orderItems.product', 'business'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Display details of a single order
     */
    public function orderDetails($id)
    {
        $order = Order::with(['orderItems.product', 'business', 'messages.sender'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Send a message about an order (customer or business owner)
     */
    public function sendMessage(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== Auth::id() && $order->business->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $receiverId = $order->user_id == Auth::id()
            ? $order->business->owner_id
            : $order->user_id;

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'order_id' => $order->id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Message sent successfully!');
    }

    /**
     * Display all orders for the business owner
     */
    public function businessOrders()
    {
        $user = Auth::user();

        if (!$user->business) {
            abort(403, 'You are not assigned to a business.');
        }

        $orders = Order::with(['orderItems.product', 'user'])
            ->where('business_id', $user->business->id)
            ->latest()
            ->paginate(10);

        return view('business.orders', compact('orders'));
    }

    /**
     * Update order status (business owner only)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,ready_for_pickup,completed,cancelled',
        ]);

        if ($order->business_id !== Auth::user()->business->id) {
            abort(403);
        }

        $order->update(['status' => $request->status]);

        // Notify the customer about status update
        try {
            Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $order->user_id,
                'order_id' => $order->id,
                'content' => "📦 Your order #{$order->id} status has been updated to: " . strtoupper($request->status),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Failed to send status update message: ' . $e->getMessage());
        }

        return back()->with('success', 'Order status updated.');
    }

    /**
     * Customer cancels an order only if it's still pending
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        // Notify business owner about cancellation
        try {
            $businessOwnerId = optional($order->business)->owner_id;
            if ($businessOwnerId) {
                Message::create([
                    'sender_id' => Auth::id(),
                    'receiver_id' => $businessOwnerId,
                    'order_id' => $order->id,
                    'content' => "❌ Order #{$order->id} has been cancelled by the customer.",
                ]);
            }
        } catch (\Throwable $e) {
            \Log::warning('Failed to notify owner about cancellation: ' . $e->getMessage());
        }

        return back()->with('success', 'Order cancelled successfully.');
    }
}