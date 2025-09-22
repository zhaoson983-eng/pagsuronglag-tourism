<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role === 'customer') {
            // For customers, show only shops (exclude hotels and resorts)
            $businesses = Business::with(['products', 'businessProfile'])
                ->whereHas('businessProfile', function($query) {
                    $query->whereNotIn('business_type', ['hotel', 'resort']);
                })
                ->where('is_published', true)
                ->get();

            // Get some featured products from shops only
            $featuredProducts = Product::with(['business.businessProfile'])
                ->whereHas('business', function($query) {
                    $query->where('is_published', true)
                          ->whereHas('businessProfile', function($subQuery) {
                              $subQuery->whereNotIn('business_type', ['hotel', 'resort']);
                          });
                })
                ->take(8)
                ->get();

            return view('customer.products', compact('businesses', 'featuredProducts'));
        }

        // For public view, show all products
        $products = Product::with(['business'])->paginate(12);
        return view('products.index', compact('products'));
    }

    public function hotels(Request $request)
    {
        $searchQuery = $request->get('search');
        
        // Show only hotels
        $hotelsQuery = Business::with(['businessProfile', 'rooms'])
            ->whereHas('businessProfile', function($query) {
                $query->where('business_type', 'hotel');
            })
            ->where('is_published', true);
        
        // Apply search filter if provided
        if ($searchQuery) {
            $hotelsQuery->where(function($query) use ($searchQuery) {
                $query->where('name', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('description', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('address', 'LIKE', "%{$searchQuery}%")
                      ->orWhereHas('businessProfile', function($profileQuery) use ($searchQuery) {
                          $profileQuery->where('business_name', 'LIKE', "%{$searchQuery}%")
                                      ->orWhere('description', 'LIKE', "%{$searchQuery}%");
                      });
            });
        }
        
        $hotels = $hotelsQuery->get();

        return view('customer.hotels', compact('hotels'));
    }

    public function show(Product $product)
    {
        $product->load(['business']);
        return view('customer.product-show', compact('product'));
    }

    public function showBusiness(Business $business)
    {
        if (!$business->is_published) {
            abort(404, 'Business not found');
        }

        $business->load(['products']);
        return view('customer.business-show', compact('business'));
    }

    // Business owner methods
    public function create()
    {
        return view('business.product-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_limit' => 'required|integer|min:0',
            'current_stock' => 'required|integer|min:0',
            'flavors' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ensure current stock doesn't exceed stock limit
        if ($validated['current_stock'] > $validated['stock_limit']) {
            return back()->with('error', 'Current stock cannot exceed stock limit!')->withInput();
        }

        $business = Auth::user()->business;
        if (!$business) {
            return redirect()->route('business.my-shop')->with('error', 'Please set up your business first.');
        }

        $validated['business_id'] = $business->id;

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Set default values if not provided
        $validated['stock_limit'] = $validated['stock_limit'] ?? 0;
        $validated['current_stock'] = $validated['current_stock'] ?? 0;

        $product = Product::create($validated);
        
        // Get the business type to determine the correct redirect
        $business = $product->business;
        $redirectRoute = 'business.my-shop'; // default
        
        if ($business && $business->businessProfile) {
            switch ($business->businessProfile->business_type) {
                case 'hotel':
                    $redirectRoute = 'business.my-hotel';
                    break;
                case 'resort':
                    $redirectRoute = 'business.my-resort';
                    break;
            }
        }
        
        return redirect()->route($redirectRoute)
            ->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        return view('business.product-form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_limit' => 'required|integer|min:0',
            'current_stock' => 'required|integer|min:0|max:' . $request->stock_limit,
            'flavors' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ensure current stock doesn't exceed stock limit
        if ($validated['current_stock'] > $validated['stock_limit']) {
            return back()->with('error', 'Current stock cannot exceed stock limit!')->withInput();
        }

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);
        
        // Get the business type to determine the correct redirect
        $business = $product->business;
        $redirectRoute = 'business.my-shop'; // default
        
        if ($business && $business->businessProfile) {
            switch ($business->businessProfile->business_type) {
                case 'hotel':
                    $redirectRoute = 'business.my-hotel';
                    break;
                case 'resort':
                    $redirectRoute = 'business.my-resort';
                    break;
            }
        }
        
        return redirect()->route($redirectRoute)
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        
        try {
            $product->delete();
            
            // Return JSON response for AJAX requests
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product deleted successfully!'
                ]);
            }
            
            return back()->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting product: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error deleting product.');
        }
    }

    /**
     * Update product stock
     */
    public function updateStock(Request $request, Product $product)
    {
        // Check if user owns this product
        if ($product->business->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'stock_limit' => 'required|integer|min:0',
            'current_stock' => 'required|integer|min:0',
        ]);

        // Ensure current stock doesn't exceed stock limit
        if ($request->current_stock > $request->stock_limit) {
            return back()->with('error', 'Current stock cannot exceed stock limit!');
        }

        $product->update([
            'stock_limit' => $request->stock_limit,
            'current_stock' => $request->current_stock,
        ]);

        return back()->with('success', 'Product stock updated successfully!');
    }

    /**
     * Get products feed data (AJAX endpoint)
     */
    public function getProductsFeedData(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        // Get published businesses (shops) with products
        $businesses = Business::with(['products', 'businessProfile'])
            ->whereHas('businessProfile', function($query) {
                $query->whereNotIn('business_type', ['hotel', 'resort'])
                      ->where('status', 'approved');
            })
            ->where('is_published', true)
            ->whereHas('products') // Only businesses that have products
            ->get();

        // Get all products from shops
        $products = Product::with(['business.businessProfile'])
            ->whereHas('business', function($query) {
                $query->where('is_published', true)
                      ->whereHas('businessProfile', function($subQuery) {
                          $subQuery->whereNotIn('business_type', ['hotel', 'resort'])
                                   ->where('status', 'approved');
                      });
            })
            ->get();

        // Combine businesses and products into a single feed
        $feedItems = collect();

        // Add businesses as shop cards
        foreach ($businesses as $business) {
            $profile = $business->businessProfile;
            if ($profile) {
                $user = auth()->user();
                $userLiked = $user ? $business->isLikedBy($user) : false;
                $userRating = $user ? $business->ratings()->where('user_id', $user->id)->first() : null;
                
                $feedItems->push([
                    'type' => 'business',
                    'id' => $business->id,
                    'title' => $profile->business_name ?? $business->name ?? 'Business',
                    'location' => $profile->address ?? 'Location not specified',
                    'image' => $business->cover_image ? \Storage::url($business->cover_image) : null,
                    'profile_avatar' => $profile->profile_avatar ? \Storage::url($profile->profile_avatar) : null,
                    'rating' => (float)($business->average_rating ?? 0),
                    'rating_count' => (int)($business->total_ratings ?? 0),
                    'like_count' => $business->likes->count(),
                    'comment_count' => $business->comments()->count(),
                    'user_liked' => $userLiked,
                    'user_rating' => $userRating ? $userRating->rating : 0,
                    'status' => 'Published',
                    'url' => route('customer.business.show', $business->id),
                    'created_at' => $business->created_at,
                    'product_count' => $business->products->count(),
                    'description' => $profile->description ?? ''
                ]);
            }
        }

        // Add individual products
        foreach ($products as $product) {
            $businessName = 'Local Business';
            if ($product->business && $product->business->businessProfile) {
                $businessName = $product->business->businessProfile->business_name ?? $product->business->name ?? 'Local Business';
            }
            
            $user = auth()->user();
            $userLiked = $user ? $product->likes()->where('user_id', $user->id)->exists() : false;
            $userRating = $user ? $product->ratings()->where('user_id', $user->id)->first() : null;
            
            // Calculate stock status
            $stockStatus = 'No Stock Info';
            $stockColor = 'text-gray-600 bg-gray-100';
            $currentStock = $product->current_stock ?? 0;
            $stockLimit = $product->stock_limit ?? 0;
            
            if ($stockLimit > 0) {
                if ($currentStock <= 0) {
                    $stockStatus = 'Out of Stock';
                    $stockColor = 'text-red-600 bg-red-100';
                } elseif ($currentStock <= ($stockLimit * 0.2)) {
                    $stockStatus = 'Low Stock';
                    $stockColor = 'text-orange-600 bg-orange-100';
                } else {
                    $stockStatus = 'In Stock';
                    $stockColor = 'text-green-600 bg-green-100';
                }
            }
            
            $feedItems->push([
                'type' => 'product',
                'id' => $product->id,
                'title' => $product->name ?? 'Product',
                'location' => $businessName,
                'image' => $product->image ? \Storage::url($product->image) : null,
                'profile_avatar' => null, // Products don't have profile avatars
                'rating' => (float)($product->average_rating ?? 0),
                'rating_count' => (int)($product->total_ratings ?? 0),
                'like_count' => $product->likes()->count(),
                'comment_count' => $product->comments()->count(),
                'user_liked' => $userLiked,
                'user_rating' => $userRating ? $userRating->rating : 0,
                'status' => 'Published',
                'url' => route('customer.business.show', $product->business_id),
                'created_at' => $product->created_at,
                'price' => $product->price,
                'description' => $product->description ?? '',
                'stock_status' => $stockStatus,
                'stock_color' => $stockColor,
                'current_stock' => $currentStock,
                'business_id' => $product->business_id
            ]);
        }

        // Shuffle the feed items for variety
        $feedItems = $feedItems->shuffle();

        // Paginate the results
        $paginatedItems = $feedItems->slice($offset, $perPage)->values();
        $hasMore = $feedItems->count() > ($offset + $perPage);

        return response()->json([
            'items' => $paginatedItems,
            'hasMore' => $hasMore,
            'currentPage' => $page
        ]);
    }
    /**
     * Toggle like for a product
     */
    public function toggleLike(Product $product)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $existingLike = $product->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $product->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $product->likes()->count()
        ]);
    }

    /**
     * Get product comments
     */
    public function getComments(Product $product)
    {
        $comments = $product->comments()
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($comment) {
                $user = auth()->user();
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'rating' => $comment->rating,
                    'user_id' => $comment->user->id,
                    'user_name' => $comment->user->name,
                    'profile_picture' => $comment->user->profile_picture ? \Storage::url($comment->user->profile_picture) : null,
                    'created_at_human' => $comment->created_at->diffForHumans(),
                    'like_count' => $comment->likes()->count(),
                    'user_liked' => $user ? $comment->likes()->where('user_id', $user->id)->exists() : false,
                    'can_delete' => $user && ($user->id === $comment->user_id || $user->role === 'admin')
                ];
            });

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
    /**
     * Rate a product
     */
    public function rateProduct(Request $request, Product $product)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        // Check if user already rated this product
        $existingRating = $product->ratings()->where('user_id', $user->id)->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
        } else {
            // Create new rating
            $product->ratings()->create([
                'user_id' => $user->id,
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
        }

        // Update product's average rating
        $product->updateRating();

        return response()->json([
            'success' => true,
            'average_rating' => $product->average_rating,
            'total_ratings' => $product->total_ratings
        ]);
    }

    /**
     * Add comment to a product
     */
    public function addComment(Request $request, Product $product)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $comment = $product->comments()->create([
            'user_id' => $user->id,
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully'
        ]);
    }
}
