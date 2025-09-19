<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use Illuminate\Http\Request;

class AttractionController extends Controller
{
    /**
     * Show all attractions.
     */
    public function index()
    {
        $attractions = Attraction::whereNull('deleted_at')
                                ->latest()
                                ->get();
        
        return view('attractions', compact('attractions'));
    }

    /**
     * Show a single attraction.
     */
    public function show($id)
    {
        $attraction = Attraction::whereNull('deleted_at')
                               ->findOrFail($id);
        return view('attractions-show', compact('attraction'));
    }
}