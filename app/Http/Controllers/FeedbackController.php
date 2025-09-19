<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        // Support two targets: product or business profile (hotel/resort)
        $isProduct = $request->filled('product_id');
        $isBiz = $request->filled('business_profile_id');

        if (!$isProduct && !$isBiz) {
            return back()->with('error', 'Invalid feedback target.');
        }

        $rules = [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];

        if ($isProduct) {
            $rules['product_id'] = 'required|exists:products,id';
        }
        if ($isBiz) {
            $rules['business_profile_id'] = 'required|exists:business_profiles,id';
            $rules['target'] = 'required|in:hotel,resort,shop';
        }

        $validated = $request->validate($rules);
        $validated['customer_id'] = Auth::id();

        // Default target for product
        if ($isProduct) {
            $validated['target'] = 'product';
        }

        Feedback::create($validated);
        return back()->with('success', 'Thank you for your feedback!');
    }
}


