<?php

namespace App\Http\Controllers;

use App\Models\Resort;
use Illuminate\Http\Request;

class ResortController extends Controller
{
    /**
     * Show all resorts.
     */
    public function index()
    {
        $resorts = Resort::latest()->get();
        return view('resorts', compact('resorts'));
    }

    /**
     * Show a single resort.
     */
    public function show($id)
    {
        $resort = Resort::findOrFail($id);
        return view('resorts-show', compact('resort'));
    }
}