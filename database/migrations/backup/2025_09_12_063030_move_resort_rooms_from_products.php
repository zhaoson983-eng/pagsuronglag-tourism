<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the type column exists in the products table
        if (Schema::hasColumn('products', 'type')) {
            // Move resort rooms from products to resort_rooms
            $resortRooms = DB::table('products')
                ->where('type', 'resort_room')
                ->get();

            foreach ($resortRooms as $product) {
                $amenities = [];
                if (property_exists($product, 'features') && $product->features) {
                    $amenities = json_decode($product->features, true) ?: [];
                }

                DB::table('resort_rooms')->insert([
                    'business_id' => $product->business_id,
                    'room_number' => $product->name,
                    'room_type' => $product->category ?? 'Standard',
                    'price_per_night' => $product->price ?? 0,
                    'capacity' => $product->stock ?? 1,
                    'description' => $product->description ?? '',
                    'image' => $product->image ?? null,
                    'is_available' => $product->is_available ?? true,
                    'amenities' => json_encode($amenities),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Remove resort rooms from products
            DB::table('products')
                ->where('type', 'resort_room')
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if the type column exists in the products table
        if (Schema::hasColumn('products', 'type')) {
            // Move resort rooms back to products
            $resortRooms = DB::table('resort_rooms')->get();

            foreach ($resortRooms as $room) {
                $features = [];
                if (property_exists($room, 'amenities') && $room->amenities) {
                    $features = json_decode($room->amenities, true) ?: [];
                }

                $productData = [
                    'business_id' => $room->business_id,
                    'name' => $room->room_number,
                    'price' => $room->price_per_night,
                    'stock' => $room->capacity,
                    'description' => $room->description ?? '',
                    'is_available' => $room->is_available ?? true,
                    'type' => 'resort_room',
                    'features' => json_encode($features),
                    'created_at' => $room->created_at ?? now(),
                    'updated_at' => $room->updated_at ?? now(),
                ];

                // Only add these if the columns exist
                if (Schema::hasColumn('products', 'category')) {
                    $productData['category'] = $room->room_type;
                }
                if (Schema::hasColumn('products', 'image')) {
                    $productData['image'] = $room->image;
                }

                DB::table('products')->insert($productData);
            }
        }
    }
};
