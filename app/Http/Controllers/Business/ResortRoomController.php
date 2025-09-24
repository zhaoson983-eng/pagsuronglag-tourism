<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\ResortRoom;
use App\Models\Business;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ResortRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $business = $user->businessProfile->business ?? null;
        
        if (!$business) {
            return redirect()->route('business.setup');
        }
        
        $rooms = $business->resortRooms()
            ->with('galleries')
            ->latest()
            ->paginate(10);
            
        return view('business.resort.rooms.index', [
            'rooms' => $rooms,
            'business' => $business
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('business.resort.rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug: Log the incoming request data
        \Log::info('ResortRoomController store request data:', [
            'all' => $request->all(),
            'files' => $request->hasFile('images') ? 'has images' : 'no images',
            'content_type' => $request->header('Content-Type')
        ]);

        $validated = $request->validate([
            'room_name' => 'required|string|max:100',
            'room_type' => 'required|string|max:100',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1|max:20',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'is_available' => 'nullable|boolean'
            // Removed amenities validation to match cottage functionality
        ]);

        // Debug: Log validation success
        \Log::info('ResortRoomController validation passed');


        $user = Auth::user();
        
        // Debug: Check user relationships
        \Log::info('ResortRoomController user debug', [
            'user_id' => $user->id,
            'has_business_profile' => $user->businessProfile ? 'YES' : 'NO',
            'business_profile_id' => $user->businessProfile ? $user->businessProfile->id : null
        ]);

        $businessProfile = $user->businessProfile;
        
        if (!$businessProfile) {
            \Log::error('ResortRoomController: No business profile found for user', ['user_id' => $user->id]);
            return response()->json(['success' => false, 'message' => 'Business profile not found. Please complete your business setup first.'], 404);
        }

        $business = $businessProfile->business ?? null;
        
        if (!$business) {
            \Log::error('ResortRoomController: No business found for business profile', ['business_profile_id' => $businessProfile->id]);
            return response()->json(['success' => false, 'message' => 'Business not found. Please complete your business setup first.'], 404);
        }

        \Log::info('ResortRoomController business found', [
            'business_id' => $business->id,
            'business_profile_id' => $businessProfile->id
        ]);

        try {
            DB::beginTransaction();

            // Create the room
            $room = new ResortRoom();
            $room->resort_id = $business->id;
            $room->room_name = $validated['room_name'];
            $room->room_type = $validated['room_type'];
            $room->price_per_night = $validated['price_per_night'];
            $room->capacity = $validated['capacity'];
            $room->description = $validated['description'] ?? null;
            $room->is_available = $request->has('is_available') ? true : false;
            // Handle amenities - removed to match cottage functionality
            $room->amenities = null; // Set to null since we're not using amenities

            // Debug: Log before saving
            \Log::info('ResortRoomController about to save:', [
                'room_name' => $room->room_name,
                'room_type' => $room->room_type,
                'resort_id' => $room->resort_id,
                'price_per_night' => $room->price_per_night,
                'capacity' => $room->capacity
            ]);

            // Save the room to database
            $room->save();

            // Debug: Log after saving
            \Log::info('ResortRoomController saved successfully:', [
                'room_id' => $room->id,
                'created_at' => $room->created_at
            ]);

            // Handle multiple image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('resort-rooms', 'public');
                    
                    // Create gallery entry
                    Gallery::create([
                        'business_profile_id' => $user->businessProfile->id,
                        'image_path' => $imagePath,
                        'caption' => "Room {$room->room_name} - {$room->room_type}",
                        'room_id' => $room->id,
                        'room_type' => 'resort'
                    ]);
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Room created successfully',
                    'room' => $room->load('galleries')
                ]);
            }

            return redirect()->route('business.my-resort')
                ->with('success', 'Room created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Debug: Log validation errors
            \Log::error('ResortRoomController validation failed:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Debug: Log the exact error
            \Log::error('ResortRoomController database error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'business_profile_id' => $businessProfile->id ?? null,
                'business_id' => $business->id ?? null
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Database error: ' . $e->getMessage()], 500);
            }
            
            return back()->withErrors(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ResortRoom $room)
    {
        $this->authorize('view', $room);
        return view('business.resort.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResortRoom $room)
    {
        try {
            $this->authorize('update', $room);
            
            $user = Auth::user();

            $roomData = [
                'id' => $room->id,
                'room_name' => $room->room_name,
                'room_type' => $room->room_type,
                'price_per_night' => $room->price_per_night,
                'capacity' => $room->capacity,
                'description' => $room->description,
                'is_available' => $room->is_available,
                'amenities' => null, // Not using amenities
                'images' => $room->galleries->map(function($gallery) {
                    return [
                        'id' => $gallery->id,
                        'url' => Storage::url($gallery->image_path),
                        'name' => basename($gallery->image_path)
                    ];
                })
            ];

            return response()->json($roomData);
        } catch (\Exception $e) {
            \Log::error('Error fetching room data: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error loading room data'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResortRoom $room)
    {
        $this->authorize('update', $room);
        
        $user = Auth::user();

        $validated = $request->validate([
            'room_name' => 'required|string|max:100',
            'room_type' => 'required|string|max:100',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1|max:20',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'existing_images' => 'nullable|string',
            'is_available' => 'nullable|boolean'
            // Removed amenities validation to match cottage functionality
        ]);

        try {
            DB::beginTransaction();

            // Update room details
            $room->update([
                'room_name' => $validated['room_name'],
                'room_type' => $validated['room_type'],
                'price_per_night' => $validated['price_per_night'],
                'capacity' => $validated['capacity'],
                'description' => $validated['description'] ?? null,
                'is_available' => $request->has('is_available') ? true : false,
                'amenities' => null // Set to null since we're not using amenities
            ]);

            // Handle existing images (remove those not in the list)
            $existingImageIds = [];
            if ($request->filled('existing_images')) {
                $existingImageIds = explode(',', $request->existing_images);
                $existingImageIds = array_filter($existingImageIds);
            }

            // Remove images not in the existing list
            $currentImages = $room->galleries;
            foreach ($currentImages as $gallery) {
                if (!in_array($gallery->id, $existingImageIds)) {
                    Storage::disk('public')->delete($gallery->image_path);
                    $gallery->delete();
                }
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('resort-rooms', 'public');
                    
                    Gallery::create([
                        'business_profile_id' => $user->businessProfile->id,
                        'image_path' => $imagePath,
                        'caption' => "Room {$room->room_name} - {$room->room_type}",
                        'room_id' => $room->id,
                        'room_type' => 'resort'
                    ]);
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Room updated successfully',
                    'room' => $room->load('galleries')
                ]);
            }

            return redirect()->route('business.my-resort')
                ->with('success', 'Room updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to update room: ' . $e->getMessage()], 500);
            }
            
            return back()->withErrors(['error' => 'Failed to update room: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle room availability
     */
    public function toggleAvailability(Request $request, ResortRoom $room)
    {
        $user = Auth::user();
        
        // Check if user owns this room
        if ($room->business->owner_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'is_available' => 'required|boolean'
        ]);

        $room->update(['is_available' => $validated['is_available']]);

        return response()->json([
            'success' => true,
            'message' => 'Room availability updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResortRoom $room)
    {
        $this->authorize('delete', $room);
        
        $user = Auth::user();

        try {
            DB::beginTransaction();

            // Delete associated images
            foreach ($room->galleries as $gallery) {
                Storage::disk('public')->delete($gallery->image_path);
                $gallery->delete();
            }
            
            $room->delete();
            
            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Room deleted successfully'
                ]);
            }

            return redirect()->route('business.my-resort')
                ->with('success', 'Room deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete room: ' . $e->getMessage()], 500);
            }
            
            return back()->withErrors(['error' => 'Failed to delete room: ' . $e->getMessage()]);
        }
    }
}
