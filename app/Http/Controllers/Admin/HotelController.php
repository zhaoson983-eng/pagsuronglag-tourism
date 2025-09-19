<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    /**
     * Show the form for creating a new hotel.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.upload.hotels');
    }

    /**
     * Store a newly created hotel in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'short_info' => 'required|string',
            'full_info' => 'nullable|string',
            'cover_photo' => 'required|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'rooms' => 'required|array|min:1',
            'rooms.*.type' => 'required|string|max:255',
            'rooms.*.price' => 'required|numeric|min:0',
            'rooms.*.features' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle cover photo upload
        $coverPath = $request->file('cover_photo')->store('hotels/cover', 'public');

        // Handle gallery images upload
        $galleryPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $galleryPaths[] = $image->store('hotels/gallery', 'public');
            }
        }

        // Create hotel data array
        $hotelData = [
            'name' => $request->name,
            'location' => $request->location,
            'short_info' => $request->short_info,
            'full_info' => $request->full_info,
            'cover_photo' => $coverPath,
            'gallery_images' => !empty($galleryPaths) ? json_encode($galleryPaths) : null,
            'room_details' => json_encode($request->rooms),
        ];

        // Create the hotel
        Hotel::create($hotelData);

        return redirect()->route('admin.upload.hotels')
            ->with('success', 'Hotel uploaded successfully!');
    }

    /**
     * Display the specified hotel.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\View\View
     */
    public function show(Hotel $hotel)
    {
        return view('hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified hotel.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\View\View
     */
    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    /**
     * Update the specified hotel in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Hotel $hotel)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'short_info' => 'required|string',
            'full_info' => 'nullable|string',
            'cover_photo' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'rooms' => 'required|array|min:1',
            'rooms.*.type' => 'required|string|max:255',
            'rooms.*.price' => 'required|numeric|min:0',
            'rooms.*.features' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $hotelData = [
            'name' => $request->name,
            'location' => $request->location,
            'short_info' => $request->short_info,
            'full_info' => $request->full_info,
            'room_details' => json_encode($request->rooms),
        ];

        // Handle cover photo update
        if ($request->hasFile('cover_photo')) {
            // Delete old cover photo
            if ($hotel->cover_photo) {
                Storage::disk('public')->delete($hotel->cover_photo);
            }
            $hotelData['cover_photo'] = $request->file('cover_photo')->store('hotels/cover', 'public');
        }

        // Handle gallery images update
        if ($request->hasFile('images')) {
            // Delete old gallery images
            if ($hotel->gallery_images) {
                foreach (json_decode($hotel->gallery_images) as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $galleryPaths = [];
            foreach ($request->file('images') as $image) {
                $galleryPaths[] = $image->store('hotels/gallery', 'public');
            }
            $hotelData['gallery_images'] = json_encode($galleryPaths);
        }

        $hotel->update($hotelData);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel updated successfully!');
    }

    /**
     * Remove the specified hotel from storage.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Hotel $hotel)
    {
        // Delete cover photo
        if ($hotel->cover_photo) {
            Storage::disk('public')->delete($hotel->cover_photo);
        }

        // Delete gallery images
        if ($hotel->gallery_images) {
            foreach (json_decode($hotel->gallery_images) as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $hotel->delete();

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel deleted successfully!');
    }
}