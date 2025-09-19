<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    /**
     * Show the business dashboard.
     */
    public function dashboard()
    {
        $business = Business::with('products')->where('owner_id', Auth::id())->first();

        if (!$business) {
            return redirect()->route('business.setup-form')->with('info', 'Please set up your business profile.');
        }

        return view('business.dashboard', compact('business'));
    }

    /**
     * Show the business setup form.
     */
    public function setupForm()
    {
        $business = Auth::user()->business;
        return view('business.setup', compact('business'));
    }

    /**
     * Store or update business setup.
     */
    public function storeSetup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delivery_available' => 'boolean',
            'delivery_fee' => 'nullable|numeric|min:0',
            'delivery_radius' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $business = $user->business;

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($business && $business->cover_image) {
                Storage::disk('public')->delete($business->cover_image);
            }

            // Store new image
            $validated['cover_image'] = $request->file('cover_image')->store('business-covers', 'public');
        }

        // Create or update business
        if ($business) {
            $business->update($validated);
        } else {
            $validated['owner_id'] = $user->id;
            Business::create($validated);
        }

        return redirect()->route('business.my-shop')->with('success', 'Business profile saved successfully.');
    }

    /**
     * Publish the shop.
     */
    public function publishShop()
    {
        $business = Auth::user()->business;

        if (!$business) {
            return redirect()->route('business.my-shop')->with('error', 'Please set up your business first.');
        }

        $business->update(['is_published' => true]);

        return redirect()->route('business.my-shop')->with('success', 'Your shop is now published!');
    }

    /**
     * Unpublish the shop.
     */
    public function unpublishShop()
    {
        $business = Auth::user()->business;

        if (!$business) {
            return redirect()->route('business.my-shop')->with('error', 'No business found.');
        }

        $business->update(['is_published' => false]);

        return redirect()->route('business.my-shop')->with('success', 'Your shop is now unpublished.');
    }

    /**
     * Show the user's shop (for owner).
     */
    public function myShop()
    {
        $user = Auth::user();
        $business = $user->business;
        $products = $business ? $business->products : collect();

        return view('business.my-shop', compact('business', 'products'));
    }

    /**
     * Update the cover image via form upload (no AJAX).
     */
    public function updateCover(Request $request)
    {
        $request->validate([
            'cover_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            return redirect()->route('business.my-shop')->with('error', 'Business not found.');
        }

        // Delete old cover image
        if ($business->cover_image) {
            Storage::disk('public')->delete($business->cover_image);
        }

        // Store new image
        $path = $request->file('cover_image')->store('business-covers', 'public');
        $business->update(['cover_image' => $path]);

        return redirect()->route('business.my-shop')->with('success', 'Cover image updated successfully!');
    }
}