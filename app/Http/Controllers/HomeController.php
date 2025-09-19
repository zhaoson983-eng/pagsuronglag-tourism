<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Home Page
    public function index()
    {
        return view('home');
    }

    // About Page
    public function about()
    {
        return view('about');
    }

    // Contact Page
    public function contact()
    {
        return view('contact');
    }

    // Resorts Page
    public function resorts()
    {
        return view('resorts');
    }

    // Tourist Attractions Page
    public function attractions()
    {
        return view('attractions');
    }
}