<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $room = new Room();
        $room->business_profile_id = Auth::user()->businessProfile->id;
        $room->name = $validated['room_name'];
        $room->description = $validated['description'];
        $room->price_per_night = $validated['price_per_night'];
        $room->capacity = $validated['capacity'];
        $room->is_available = true;
        $room->save();

        // Handle room images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('room_images', 'public');
                $room->images()->create([
                    'image_path' => $path,
                    'is_primary' => false, // You might want to handle primary image logic
                ]);
            }
        }

        return redirect()->back()->with('success', 'Room added successfully!');
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room)
    {
        try {
            $this->authorize('update', $room);
            
            return response()->json([
                'id' => $room->id,
                'room_name' => $room->name,
                'description' => $room->description,
                'price_per_night' => $room->price_per_night,
                'capacity' => $room->capacity,
                'is_available' => $room->is_available,
                'images' => $room->images ? $room->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'path' => Storage::url($image->image_path)
                    ];
                }) : []
            ]);
        } catch (\Exception $e) {
            \Log::error('Room edit error: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch room data'], 500);
        }
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Room $room)
    {
        $this->authorize('update', $room);

        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $room->update([
            'name' => $validated['room_name'],
            'description' => $validated['description'],
            'price_per_night' => $validated['price_per_night'],
            'capacity' => $validated['capacity'],
            'is_available' => $request->has('is_available') ? $validated['is_available'] : $room->is_available,
        ]);

        // Handle new room images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('room_images', 'public');
                $room->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room)
    {
        $this->authorize('delete', $room);
        
        // Delete associated images
        foreach ($room->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        
        $room->delete();
        
        // Check if request expects JSON (AJAX request)
        if (request()->expectsJson() || request()->is('business/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Room deleted successfully!'
            ]);
        }
        
        return redirect()->back()->with('success', 'Room deleted successfully!');
    }
}
