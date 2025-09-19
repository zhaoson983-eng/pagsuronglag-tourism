<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Business;
use App\Models\Product;
use App\Models\ProductFlavor;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Message;
use App\Models\Feedback;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create a business owner
        $owner = User::updateOrCreate(
            ['email' => 'owner@pagsuronglagonoy.test'],
            [
                'name' => 'Local Snacks Shop',
                'password' => Hash::make('password123'),
                'role' => 'business_owner',
            ]
        );

        Profile::updateOrCreate(['user_id' => $owner->id], [
            'phone' => '09171234567',
            'address' => 'Poblacion, Lagonoy, Cam. Sur',
        ]);

        $business = Business::updateOrCreate(
            ['owner_id' => $owner->id],
            [
                'name' => 'Lolo Ey Delicacies',
                'description' => 'Home-made local treats and delicacies.',
                'address' => 'Pagsurong, Lagonoy',
                'contact_number' => '0917-123-4567',
            ]
        );

        // Products
        $binanban = Product::updateOrCreate(
            ['business_id' => $business->id, 'name' => 'Binanban (Banana Chips)'],
            [
                'description' => 'Sweet and crunchy banana chips made locally.',
                'price' => 80,
            ]
        );
        ProductFlavor::firstOrCreate(['product_id' => $binanban->id, 'name' => 'Classic'], ['additional_price' => 0]);
        ProductFlavor::firstOrCreate(['product_id' => $binanban->id, 'name' => 'Chocolate'], ['additional_price' => 10]);

        $putotabla = Product::updateOrCreate(
            ['business_id' => $business->id, 'name' => 'Puto Tabla'],
            [
                'description' => 'Traditional Lagonoy rice cake.',
                'price' => 50,
            ]
        );

        // Create a customer
        $customer = User::updateOrCreate(
            ['email' => 'customer@pagsuronglagonoy.test'],
            [
                'name' => 'Juan Dela Cruz',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );
        Profile::updateOrCreate(['user_id' => $customer->id], [
            'phone' => '09999999999',
            'address' => 'Zone 1, Lagonoy',
        ]);

        // Sample order
        $order = Order::create([
            'business_id' => $business->id,
            'customer_id' => $customer->id,
            'status' => 'pending',
            'pickup_time' => 'Tomorrow 3:00 PM',
            'notes' => 'Please prepare with less sugar.',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $binanban->id,
            'quantity' => 2,
            'price' => $binanban->price,
            'selected_flavor' => 'Classic',
        ]);

        // Messages
        Message::create([
            'sender_id' => $customer->id,
            'receiver_id' => $owner->id,
            'order_id' => $order->id,
            'content' => 'Hello! Is my order available by 3PM?',
        ]);
        Message::create([
            'sender_id' => $owner->id,
            'receiver_id' => $customer->id,
            'order_id' => $order->id,
            'content' => 'Yes! It will be ready for pickup.',
        ]);

        // Feedback
        Feedback::create([
            'product_id' => $binanban->id,
            'customer_id' => $customer->id,
            'rating' => 5,
            'comment' => 'Super sarap! Highly recommended.',
        ]);
    }
}


