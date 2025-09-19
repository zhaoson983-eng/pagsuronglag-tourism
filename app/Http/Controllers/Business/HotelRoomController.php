<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\HotelRoom;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HotelRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $business = Auth::user()->business;
        $rooms = $business->hotelRooms()
            ->with('business')
            ->latest()
            ->paginate(10);
            
        return view('business.hotel.rooms.index', [
            'rooms' => $rooms,
            'business' => $business
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('business.hotel.rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:20',
            'room_type' => 'required|string|max:100',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100'
        ]);

        $business = Auth::user()->business;
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hotel-rooms', 'public');
        }

        $validated['business_id'] = $business->id;
        $validated['is_available'] = $request->has('is_available');

        HotelRoom::create($validated);

        return redirect()->route('business.hotel.rooms.index')
            ->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelRoom $room)
    {
        $this->authorize('view', $room);
        return view('business.hotel.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HotelRoom $room)
    {
        $this->authorize('update', $room);
        return view('business.hotel.rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HotelRoom $room)
    {
        $this->authorize('update', $room);

        $validated = $request->validate([
            'room_number' => 'required|string|max:20',
            'room_type' => 'required|string|max:100',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $validated['image'] = $request->file('image')->store('hotel-rooms', 'public');
        }

        $validated['is_available'] = $request->has('is_available');

        $room->update($validated);

        return redirect()->route('business.hotel.rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelRoom $room)
    {
        $this->authorize('delete', $room);
        
        // Delete image if exists
        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }
        
        $room->delete();
        
        return redirect()->route('business.hotel.rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}
