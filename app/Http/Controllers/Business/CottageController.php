<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Cottage;
use App\Models\Business;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CottageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cottage_name' => 'required|string|max:100',
            'cottage_type' => 'required|string|max:100',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1|max:20',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'is_available' => 'nullable|boolean'
        ]);

        $user = Auth::user();
        $businessProfile = $user->businessProfile;
        
        if (!$businessProfile) {
            return response()->json(['success' => false, 'message' => 'Business profile not found'], 404);
        }

        try {
            DB::beginTransaction();

            // Create the cottage
            $cottage = new Cottage();
            $cottage->business_id = $businessProfile->business ? $businessProfile->business->id : null;
            $cottage->business_profile_id = $businessProfile->id;
            $cottage->cottage_name = $validated['cottage_name'];
            $cottage->cottage_type = $validated['cottage_type'];
            $cottage->price_per_night = $validated['price_per_night'];
            $cottage->capacity = $validated['capacity'];
            $cottage->description = $validated['description'] ?? null;
            $cottage->is_available = true; // New cottages are available by default
            $cottage->save();

            // Handle multiple image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('cottages', 'public');
                    
                    // Create gallery entry
                    Gallery::create([
                        'business_profile_id' => $businessProfile->id,
                        'image_path' => $imagePath,
                        'caption' => "Cottage {$cottage->cottage_name} - {$cottage->cottage_type}",
                        'cottage_id' => $cottage->id,
                        'room_type' => 'cottage'
                    ]);
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Cottage created successfully',
                    'cottage' => $cottage->load('galleries')
                ]);
            }

            return redirect()->route('business.my-resort')
                ->with('success', 'Cottage created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to create cottage: ' . $e->getMessage()], 500);
            }
            
            return back()->withErrors(['error' => 'Failed to create cottage: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cottage $cottage)
    {
        try {
            $this->authorize('update', $cottage);
            
            $user = Auth::user();

            $cottageData = [
                'id' => $cottage->id,
                'cottage_name' => $cottage->cottage_name,
                'cottage_type' => $cottage->cottage_type,
                'price_per_night' => $cottage->price_per_night,
                'capacity' => $cottage->capacity,
                'description' => $cottage->description,
                'is_available' => $cottage->is_available,
                'images' => $cottage->galleries->map(function($gallery) {
                    return [
                        'id' => $gallery->id,
                        'url' => Storage::url($gallery->image_path),
                        'name' => basename($gallery->image_path)
                    ];
                })
            ];

            return response()->json($cottageData);
        } catch (\Exception $e) {
            \Log::error('Error fetching cottage data: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error loading cottage data'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cottage $cottage)
    {
        $this->authorize('update', $cottage);
        
        $user = Auth::user();

        $validated = $request->validate([
            'cottage_name' => 'required|string|max:100',
            'cottage_type' => 'required|string|max:100',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1|max:20',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'existing_images' => 'nullable|string',
            'is_available' => 'nullable|boolean'
        ]);

        try {
            DB::beginTransaction();

            // Update cottage details
            $cottage->update([
                'cottage_name' => $validated['cottage_name'],
                'cottage_type' => $validated['cottage_type'],
                'price_per_night' => $validated['price_per_night'],
                'capacity' => $validated['capacity'],
                'description' => $validated['description'] ?? null,
                'is_available' => $request->has('is_available') ? true : false,
            ]);

            // Handle existing images (remove those not in the list)
            $existingImageIds = [];
            if ($request->filled('existing_images')) {
                $existingImageIds = explode(',', $request->existing_images);
                $existingImageIds = array_filter($existingImageIds);
            }

            // Remove images not in the existing list
            $currentImages = $cottage->galleries;
            foreach ($currentImages as $gallery) {
                if (!in_array($gallery->id, $existingImageIds)) {
                    Storage::disk('public')->delete($gallery->image_path);
                    $gallery->delete();
                }
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('cottages', 'public');
                    
                    Gallery::create([
                        'business_profile_id' => $user->businessProfile->id,
                        'image_path' => $imagePath,
                        'caption' => "Cottage {$cottage->cottage_name} - {$cottage->cottage_type}",
                        'cottage_id' => $cottage->id,
                        'room_type' => 'cottage'
                    ]);
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Cottage updated successfully',
                    'cottage' => $cottage->load('galleries')
                ]);
            }

            return redirect()->route('business.my-resort')
                ->with('success', 'Cottage updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to update cottage: ' . $e->getMessage()], 500);
            }
            
            return back()->withErrors(['error' => 'Failed to update cottage: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle cottage availability
     */
    public function toggleAvailability(Request $request, Cottage $cottage)
    {
        $user = Auth::user();
        
        // Check if user owns this cottage
        if ($cottage->business->owner_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $cottage->update(['is_available' => !$cottage->is_available]);

        return response()->json([
            'success' => true,
            'message' => 'Cottage availability updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cottage $cottage)
    {
        $this->authorize('delete', $cottage);
        
        $user = Auth::user();

        try {
            DB::beginTransaction();

            // Delete associated images
            foreach ($cottage->galleries as $gallery) {
                Storage::disk('public')->delete($gallery->image_path);
                $gallery->delete();
            }
            
            $cottage->delete();
            
            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cottage deleted successfully'
                ]);
            }

            return redirect()->route('business.my-resort')
                ->with('success', 'Cottage deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete cottage: ' . $e->getMessage()], 500);
            }
            
            return back()->withErrors(['error' => 'Failed to delete cottage: ' . $e->getMessage()]);
        }
    }
}
