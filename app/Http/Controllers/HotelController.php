<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display a listing of the hotels.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $hotels = Hotel::latest()->get();
        
        return view('hotels.index', compact('hotels'));
    }

    /**
     * Display the specified hotel.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $hotel = Hotel::findOrFail($id);
        
        return view('hotels.show', compact('hotel'));
    }
}