<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TouristSpot;
use App\Models\TouristSpotRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TouristSpotController extends Controller
{
    public function index()
    {
        $touristSpots = TouristSpot::with('uploader')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.upload-spots', compact('touristSpots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'profile_avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'nullable|string|max:255',
            'additional_info' => 'nullable|string',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['uploaded_by'] = Auth::id();

        // Handle profile avatar upload
        if ($request->hasFile('profile_avatar')) {
            $data['profile_avatar'] = $request->file('profile_avatar')->store('tourist-spots/avatars', 'public');
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('tourist-spots/covers', 'public');
        }

        // Handle gallery images upload
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('tourist-spots/gallery', 'public');
                $galleryImages[] = $path;
            }
        }
        $data['gallery_images'] = json_encode($galleryImages);

        TouristSpot::create($data);

        return redirect()->route('admin.upload.spots')->with('success', 'Tourist spot uploaded successfully!');
    }

    public function uploadGallery(Request $request)
    {
        $request->validate([
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $uploadedFiles = [];
        
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('admin/gallery', 'public');
                $uploadedFiles[] = $path;
            }
        }

        return response()->json([
            'success' => true,
            'files' => $uploadedFiles,
            'message' => 'Gallery images uploaded successfully!'
        ]);
    }


    public function edit(TouristSpot $touristSpot)
    {
        return response()->json($touristSpot);
    }

    public function update(Request $request, TouristSpot $touristSpot)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'profile_avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'nullable|string|max:255',
            'additional_info' => 'nullable|string',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        // Handle profile avatar upload
        if ($request->hasFile('profile_avatar')) {
            if ($touristSpot->profile_avatar) {
                Storage::disk('public')->delete($touristSpot->profile_avatar);
            }
            $data['profile_avatar'] = $request->file('profile_avatar')->store('tourist-spots/avatars', 'public');
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            if ($touristSpot->cover_image) {
                Storage::disk('public')->delete($touristSpot->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('tourist-spots/covers', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            // Delete existing gallery images
            if ($touristSpot->gallery_images) {
                $existingImages = json_decode($touristSpot->gallery_images, true);
                if (is_array($existingImages)) {
                    foreach ($existingImages as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }
            
            // Upload new gallery images
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('tourist-spots/gallery', 'public');
                $galleryImages[] = $path;
            }
            $data['gallery_images'] = json_encode($galleryImages);
        }

        $touristSpot->update($data);

        return redirect()->route('admin.upload.spots')->with('success', 'Tourist spot updated successfully!');
    }

    public function destroy(TouristSpot $touristSpot)
    {
        // Delete associated files
        if ($touristSpot->profile_avatar) {
            Storage::disk('public')->delete($touristSpot->profile_avatar);
        }
        if ($touristSpot->cover_image) {
            Storage::disk('public')->delete($touristSpot->cover_image);
        }
        
        // Delete gallery images
        if ($touristSpot->gallery_images) {
            $galleryImages = json_decode($touristSpot->gallery_images, true);
            if (is_array($galleryImages)) {
                foreach ($galleryImages as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $touristSpot->delete();

        return response()->json(['success' => true, 'message' => 'Tourist spot deleted successfully!']);
    }
}
