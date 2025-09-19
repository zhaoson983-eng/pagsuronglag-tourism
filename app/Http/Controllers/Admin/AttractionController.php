<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AttractionController extends Controller
{
    public function create()
    {
        return view('admin.upload.attractions');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'short_info' => 'required|string',
            'full_info' => 'nullable|string',
            'cover_photo' => 'required|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'has_entrance_fee' => 'required|boolean',
            'entrance_fee' => 'nullable|required_if:has_entrance_fee,1|numeric|min:0',
            'additional_info' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $coverPath = $request->file('cover_photo')->store('attractions/cover', 'public');

        $galleryPaths = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('attractions/gallery', 'public');
            }
        }

        Attraction::create([
            'name' => $request->name,
            'location' => $request->location,
            'short_info' => $request->short_info,
            'full_info' => $request->full_info,
            'cover_photo' => $coverPath,
            'gallery_images' => !empty($galleryPaths) ? json_encode($galleryPaths) : null,
            'has_entrance_fee' => $request->has_entrance_fee,
            'entrance_fee' => $request->has_entrance_fee ? $request->entrance_fee : null,
            'additional_info' => $request->additional_info,
        ]);

        return redirect()->route('admin.upload.attractions')
            ->with('success', 'Attraction uploaded successfully!');
    }

    public function index()
    {
        $attractions = Attraction::latest()->get();
        return view('admin.attractions.index', compact('attractions'));
    }

    public function edit(Attraction $attraction)
    {
        return view('admin.attractions.edit', compact('attraction'));
    }

    public function update(Request $request, Attraction $attraction)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'short_info' => 'required|string',
            'full_info' => 'nullable|string',
            'cover_photo' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'has_entrance_fee' => 'required|boolean',
            'entrance_fee' => 'nullable|required_if:has_entrance_fee,1|numeric|min:0',
            'additional_info' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['name', 'location', 'short_info', 'full_info', 'has_entrance_fee', 'entrance_fee', 'additional_info']);

        if ($request->hasFile('cover_photo')) {
            if ($attraction->cover_photo) {
                Storage::disk('public')->delete($attraction->cover_photo);
            }
            $data['cover_photo'] = $request->file('cover_photo')->store('attractions/cover', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            if ($attraction->gallery_images) {
                foreach (json_decode($attraction->gallery_images) as $old) {
                    Storage::disk('public')->delete($old);
                }
            }
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('attractions/gallery', 'public');
            }
            $data['gallery_images'] = json_encode($galleryPaths);
        }

        $attraction->update($data);

        return redirect()->route('admin.attractions.index')
            ->with('success', 'Attraction updated successfully!');
    }

    public function destroy(Attraction $attraction)
    {
        if ($attraction->cover_photo) {
            Storage::disk('public')->delete($attraction->cover_photo);
        }
        if ($attraction->gallery_images) {
            foreach (json_decode($attraction->gallery_images) as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $attraction->delete();

        return redirect()->route('admin.attractions.index')
            ->with('success', 'Attraction deleted successfully!');
    }
}