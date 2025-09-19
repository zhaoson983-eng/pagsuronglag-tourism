<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ResortController extends Controller
{
    /**
     * Show the form for creating a new resort.
     */
    public function create()
    {
        return view('admin.upload.resorts');
    }

    /**
     * Store a newly created resort in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'short_info' => 'required|string',
            'full_info' => 'nullable|string',
            'cover_photo' => 'required|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'rooms' => 'required|array|min:1',
            'rooms.*.type' => 'required|string|max:255',
            'rooms.*.price' => 'required|numeric|min:0',
            'rooms.*.features' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle cover photo upload
        $coverPath = $request->file('cover_photo')->store('resorts/cover', 'public');

        // Handle gallery images
        $galleryPaths = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('resorts/gallery', 'public');
            }
        }

        Resort::create([
            'name' => $request->name,
            'location' => $request->location,
            'short_info' => $request->short_info,
            'full_info' => $request->full_info,
            'cover_photo' => $coverPath,
            'gallery_images' => !empty($galleryPaths) ? json_encode($galleryPaths) : null,
            'room_details' => json_encode($request->rooms),
        ]);

        return redirect()->route('admin.upload.resorts')
            ->with('success', 'Resort uploaded successfully!');
    }

    /**
     * Display all resorts (admin list).
     */
    public function index()
    {
        $resorts = Resort::latest()->get();
        return view('admin.resorts.index', compact('resorts'));
    }

    /**
     * Show the form for editing the specified resort.
     */
    public function edit(Resort $resort)
    {
        return view('admin.resorts.edit', compact('resort'));
    }

    /**
     * Update the specified resort.
     */
    public function update(Request $request, Resort $resort)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'short_info' => 'required|string',
            'full_info' => 'nullable|string',
            'cover_photo' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'rooms' => 'required|array|min:1',
            'rooms.*.type' => 'required|string|max:255',
            'rooms.*.price' => 'required|numeric|min:0',
            'rooms.*.features' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'location' => $request->location,
            'short_info' => $request->short_info,
            'full_info' => $request->full_info,
            'room_details' => json_encode($request->rooms),
        ];

        // Handle cover photo update
        if ($request->hasFile('cover_photo')) {
            if ($resort->cover_photo) {
                Storage::disk('public')->delete($resort->cover_photo);
            }
            $data['cover_photo'] = $request->file('cover_photo')->store('resorts/cover', 'public');
        }

        // Handle gallery images update
        if ($request->hasFile('gallery_images')) {
            if ($resort->gallery_images) {
                foreach (json_decode($resort->gallery_images) as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('resorts/gallery', 'public');
            }
            $data['gallery_images'] = json_encode($galleryPaths);
        }

        $resort->update($data);

        return redirect()->route('admin.resorts.index')
            ->with('success', 'Resort updated successfully!');
    }

    /**
     * Remove the specified resort.
     */
    public function destroy(Resort $resort)
    {
        // Delete cover photo
        if ($resort->cover_photo) {
            Storage::disk('public')->delete($resort->cover_photo);
        }

        // Delete gallery images
        if ($resort->gallery_images) {
            foreach (json_decode($resort->gallery_images) as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $resort->delete();

        return redirect()->route('admin.resorts.index')
            ->with('success', 'Resort deleted successfully!');
    }
}