<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessProfile;
use App\Models\Product;
use App\Models\Attraction;
use App\Models\Room;
use App\Models\Cottage;
use App\Models\TouristSpot;
use App\Models\TouristSpotLike;
use App\Models\TouristSpotRating;
use App\Models\TouristSpotComment;
use App\Models\BusinessProfileLike;
use App\Models\BusinessProfileRating;
use App\Models\BusinessProfileComment;
use App\Models\ProductLike;
use App\Models\ProductRating;
use App\Models\ProductComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Show customer dashboard.
     */
    public function dashboard()
    {
        return view('customer.dashboard');
    }

    /**
     * Get feed data for the dashboard (AJAX endpoint)
     */
    public function getFeedData(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // This method now serves as a general feed with all types of content
        // For specific feed types, we'll use the dedicated methods below
        
        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        // Get published businesses (shops) - same as ProductController
        $shopBusinesses = Business::with(['products', 'businessProfile'])
            ->whereHas('businessProfile', function($query) {
                $query->whereNotIn('business_type', ['hotel', 'resort'])
                      ->where('status', 'approved');
            })
            ->where('is_published', true)
            ->get();

        // Get published hotels - same as ProductController
        $hotels = Business::with(['businessProfile', 'rooms'])
            ->whereHas('businessProfile', function($query) {
                $query->where('business_type', 'hotel')
                      ->where('status', 'approved');
            })
            ->where('is_published', true)
            ->get();

        // Get published resorts - same as CustomerController resorts method
        $resorts = BusinessProfile::with(['business', 'gallery'])
            ->where('business_type', 'resort')
            ->where('status', 'approved')
            ->whereHas('business', function($q) {
                $q->where('is_published', true);
            })
            ->get();

        // Get active tourist spots
        $touristSpots = TouristSpot::where('is_active', true)->get();

        // Get featured products from shops
        $products = Product::with(['business.businessProfile'])
            ->whereHas('business', function($query) {
                $query->where('is_published', true)
                      ->whereHas('businessProfile', function($subQuery) {
                          $subQuery->whereNotIn('business_type', ['hotel', 'resort'])
                                   ->where('status', 'approved');
                      });
            })
            ->get();

        // Combine all items into a single feed
        $feedItems = collect();

        // Add shop businesses
        foreach ($shopBusinesses as $business) {
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
                    'image' => $business->cover_image ? Storage::url($business->cover_image) : null,
                    'profile_avatar' => $profile->profile_avatar ? Storage::url($profile->profile_avatar) : null,
                    'rating' => (float)($business->average_rating ?? 0),
                    'rating_count' => (int)($business->total_ratings ?? 0),
                    'like_count' => $business->likes->count(),
                    'comment_count' => $business->comments()->count(),
                    'user_liked' => $userLiked,
                    'user_rating' => $userRating ? $userRating->rating : 0,
                    'status' => 'Published',
                    'url' => route('customer.business.show', $business->id),
                    'created_at' => $business->created_at
                ]);
            }
        }

        // Add hotels
        foreach ($hotels as $hotel) {
            $profile = $hotel->businessProfile;
            if ($profile) {
                $user = auth()->user();
                $userLiked = $user ? $profile->hotelLikes()->where('user_id', $user->id)->exists() : false;
                $userRating = $user ? $profile->hotelRatings()->where('user_id', $user->id)->first() : null;
                
                $feedItems->push([
                    'type' => 'hotel',
                    'id' => $profile->id, // Use profile ID for hotels
                    'title' => $profile->business_name ?? $hotel->name ?? 'Hotel',
                    'location' => $profile->address ?? 'Location not specified',
                    'image' => $profile->cover_image ? Storage::url($profile->cover_image) : null,
                    'profile_avatar' => $profile->profile_avatar ? Storage::url($profile->profile_avatar) : null,
                    'rating' => (float)($profile->average_rating ?? 0),
                    'rating_count' => (int)($profile->total_ratings ?? 0),
                    'like_count' => $profile->hotelLikes()->count(),
                    'comment_count' => $profile->hotelComments()->count(),
                    'user_liked' => $userLiked,
                    'user_rating' => $userRating ? $userRating->rating : 0,
                    'status' => 'Published',
                    'url' => route('customer.hotels.show', $hotel->id),
                    'created_at' => $hotel->created_at
                ]);
            }
        }

        // Add resorts
        foreach ($resorts as $resort) {
            $user = auth()->user();
            $userLiked = $user ? $resort->resortLikes()->where('user_id', $user->id)->exists() : false;
            $userRating = $user ? $resort->resortRatings()->where('user_id', $user->id)->first() : null;
            
            $feedItems->push([
                'type' => 'resort',
                'id' => $resort->id,
                'title' => $resort->business_name ?? 'Resort',
                'location' => $resort->address ?? 'Location not specified',
                'image' => $resort->cover_image ? Storage::url($resort->cover_image) : ($resort->gallery->first() ? Storage::url($resort->gallery->first()->image_path) : null),
                'profile_avatar' => $resort->profile_avatar ? Storage::url($resort->profile_avatar) : null,
                'rating' => (float)($resort->average_rating ?? 0),
                'rating_count' => (int)($resort->total_ratings ?? 0),
                'like_count' => $resort->resortLikes()->count(),
                'comment_count' => $resort->resortComments()->count(),
                'user_liked' => $userLiked,
                'user_rating' => $userRating ? $userRating->rating : 0,
                'status' => 'Published',
                'url' => route('customer.resorts.show', $resort->id),
                'created_at' => $resort->created_at
            ]);
        }

        // Add tourist spots
        foreach ($touristSpots as $spot) {
            $user = auth()->user();
            $userLiked = $user ? $spot->likes()->where('user_id', $user->id)->exists() : false;
            $userRating = $user ? $spot->ratings()->where('user_id', $user->id)->first() : null;
            
            $feedItems->push([
                'type' => 'attraction',
                'id' => $spot->id,
                'title' => $spot->name ?? 'Tourist Spot',
                'location' => $spot->location ?? 'Location not specified',
                'image' => $spot->cover_image ? Storage::url($spot->cover_image) : ($spot->image ? Storage::url($spot->image) : null),
                'profile_avatar' => $spot->profile_avatar ? Storage::url($spot->profile_avatar) : null,
                'rating' => (float)($spot->average_rating ?? 0),
                'rating_count' => (int)($spot->total_ratings ?? 0),
                'like_count' => $spot->likes()->count(),
                'comment_count' => $spot->comments()->count(),
                'user_liked' => $userLiked,
                'user_rating' => $userRating ? $userRating->rating : 0,
                'status' => 'Published',
                'url' => route('customer.attractions.show', $spot->id),
                'created_at' => $spot->created_at
            ]);
        }

        // Add products
        foreach ($products as $product) {
            $businessName = 'Local Business';
            if ($product->business && $product->business->businessProfile) {
                $businessName = $product->business->businessProfile->business_name ?? $product->business->name ?? 'Local Business';
            }
            
            $user = auth()->user();
            $userLiked = $user ? $product->likes()->where('user_id', $user->id)->exists() : false;
            $userRating = $user ? $product->ratings()->where('user_id', $user->id)->first() : null;
            
            $feedItems->push([
                'type' => 'product',
                'id' => $product->id,
                'title' => $product->name ?? 'Product',
                'location' => $businessName,
                'image' => $product->image ? Storage::url($product->image) : null,
                'profile_avatar' => null, // Products don't have profile avatars
                'rating' => (float)($product->average_rating ?? 0),
                'rating_count' => (int)($product->total_ratings ?? 0),
                'like_count' => $product->likes()->count(),
                'comment_count' => $product->comments()->count(),
                'user_liked' => $userLiked,
                'user_rating' => $userRating ? $userRating->rating : 0,
                'status' => 'Published',
                'url' => route('customer.product.show', $product->id),
                'created_at' => $product->created_at
            ]);
        }

        // Debug information
        \Log::info('Feed Data Debug', [
            'shop_businesses_count' => $shopBusinesses->count(),
            'hotels_count' => $hotels->count(),
            'resorts_count' => $resorts->count(),
            'tourist_spots_count' => $touristSpots->count(),
            'products_count' => $products->count(),
            'feed_items_count' => $feedItems->count()
        ]);


        // Shuffle the feed items for Instagram-like randomization
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
     * Show a published business and its products/rooms.
     */
    public function showBusiness(Business $business)
    {
        // Ensure the business is published
        if (!$business->is_published) {
            abort(404);
        }

        // Load business profile
        $business->load(['businessProfile']);

        // Check if it's a hotel/resort or regular business
        if ($business->businessProfile && in_array($business->businessProfile->business_type, ['hotel', 'resort'])) {
            // Load rooms for hotels/resorts (only available ones for customers)
            $business->load(['rooms.images', 'businessProfile.galleries']);
            $rooms = $business->rooms()->where('is_available', true)->get();
            
            // Hotels only have rooms, resorts have both rooms and cottages
            if ($business->businessProfile->business_type === 'hotel') {
                return view('customer.hotel-show', compact('business', 'rooms'));
            } else {
                // For resorts, also load cottages
                $cottages = $business->cottages()->with('galleries')->where('is_available', true)->get();
                return view('customer.resort-show', compact('business', 'rooms', 'cottages'));
            }
        } else {
            // Load products, galleries, and comments for regular businesses
            $business->load([
                'products', 
                'businessProfile.galleries',
                'comments' => function($query) {
                    $query->with('user.profile')->whereHas('user');
                }
            ]);
            $products = $business->products;
            
            return view('customer.business-show', compact('business', 'products'));
        }
    }

    /**
     * Search across businesses, products, and attractions.
     */
    /**
     * Get hotels feed data
     */
    public function getHotelsFeedData(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        // Get published hotels
        $hotels = Business::with(['businessProfile', 'rooms'])
            ->whereHas('businessProfile', function($query) {
                $query->where('business_type', 'hotel')
                      ->where('status', 'approved');
            })
            ->where('is_published', true)
            ->get()
            ->map(function($hotel) {
                $profile = $hotel->businessProfile;
                $user = auth()->user();
                $userLiked = $user ? $profile->hotelLikes()->where('user_id', $user->id)->exists() : false;
                $userRating = $user ? $profile->hotelRatings()->where('user_id', $user->id)->first() : null;
                
                return [
                    'type' => 'hotel',
                    'id' => $profile->id,
                    'title' => $profile->business_name ?? $hotel->name ?? 'Hotel',
                    'location' => $profile->address ?? 'Location not specified',
                    'description' => $profile->description ?? '',
                    'image' => $profile->cover_image ? Storage::url($profile->cover_image) : null,
                    'profile_avatar' => $profile->profile_avatar ? Storage::url($profile->profile_avatar) : null,
                    'rating' => (float)($profile->average_rating ?? 0),
                    'rating_count' => (int)($profile->total_ratings ?? 0),
                    'like_count' => $profile->hotelLikes()->count(),
                    'comment_count' => $profile->hotelComments()->count(),
                    'user_liked' => $userLiked,
                    'user_rating' => $userRating ? $userRating->rating : 0,
                    'status' => 'Published',
                    'url' => route('customer.hotels.show', $hotel->id),
                    'created_at' => $hotel->created_at->toIso8601String(),
                    'min_price' => $hotel->rooms->min('price_per_night') ?? 0,
                    'rooms_count' => $hotel->rooms->count()
                ];
            });

        return response()->json([
            'items' => $hotels->slice($offset, $perPage)->values(),
            'hasMore' => $hotels->count() > ($offset + $perPage)
        ]);
    }

    /**
     * Get resorts feed data
     */
    public function getResortsFeedData(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        // Get published resorts
        $resorts = BusinessProfile::with(['business', 'gallery'])
            ->where('business_type', 'resort')
            ->where('status', 'approved')
            ->whereHas('business', function($q) {
                $q->where('is_published', true);
            })
            ->get()
            ->map(function($resort) {
                $user = auth()->user();
                $userLiked = $user ? $resort->resortLikes()->where('user_id', $user->id)->exists() : false;
                $userRating = $user ? $resort->resortRatings()->where('user_id', $user->id)->first() : null;
                
                return [
                    'type' => 'resort',
                    'id' => $resort->id,
                    'title' => $resort->business_name ?? 'Resort',
                    'location' => $resort->address ?? 'Location not specified',
                    'description' => $resort->description ?? '',
                    'image' => $resort->cover_image ? Storage::url($resort->cover_image) : 
                             ($resort->gallery->first() ? Storage::url($resort->gallery->first()->image_path) : null),
                    'profile_avatar' => $resort->profile_avatar ? Storage::url($resort->profile_avatar) : null,
                    'rating' => (float)($resort->average_rating ?? 0),
                    'rating_count' => (int)($resort->total_ratings ?? 0),
                    'like_count' => $resort->resortLikes()->count(),
                    'comment_count' => $resort->resortComments()->count(),
                    'user_liked' => $userLiked,
                    'user_rating' => $userRating ? $userRating->rating : 0,
                    'status' => 'Published',
                    'url' => route('customer.resorts.show', $resort->id),
                    'created_at' => $resort->created_at->toIso8601String(),
                    'min_price' => $resort->business->rooms->min('price_per_night') ?? 0,
                    'rooms_count' => $resort->business->rooms->count(),
                    'cottages_count' => $resort->business->cottages->count()
                ];
            });

        return response()->json([
            'items' => $resorts->slice($offset, $perPage)->values(),
            'hasMore' => $resorts->count() > ($offset + $perPage)
        ]);
    }

    /**
     * Get attractions feed data
     */
    public function getAttractionsFeedData(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        // Get active tourist spots
        $attractions = TouristSpot::where('is_active', true)
            ->get()
            ->map(function($spot) {
                $user = auth()->user();
                $userLiked = $user ? $spot->likes()->where('user_id', $user->id)->exists() : false;
                $userRating = $user ? $spot->ratings()->where('user_id', $user->id)->first() : null;
                
                return [
                    'type' => 'attraction',
                    'id' => $spot->id,
                    'title' => $spot->name ?? 'Tourist Spot',
                    'location' => $spot->location ?? 'Location not specified',
                    'description' => $spot->description ?? '',
                    'image' => $spot->cover_image ? Storage::url($spot->cover_image) : 
                             ($spot->image ? Storage::url($spot->image) : null),
                    'profile_avatar' => $spot->profile_avatar ? Storage::url($spot->profile_avatar) : null,
                    'rating' => (float)($spot->average_rating ?? 0),
                    'rating_count' => (int)($spot->total_ratings ?? 0),
                    'like_count' => $spot->likes()->count(),
                    'comment_count' => $spot->comments()->count(),
                    'user_liked' => $userLiked,
                    'user_rating' => $userRating ? $userRating->rating : 0,
                    'status' => 'Published',
                    'url' => route('customer.attractions.show', $spot->id),
                    'created_at' => $spot->created_at->toIso8601String()
                ];
            });

        return response()->json([
            'items' => $attractions->slice($offset, $perPage)->values(),
            'hasMore' => $attractions->count() > ($offset + $perPage)
        ]);
    }

    public function search(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        if ($q === '') {
            return view('customer.search-results', [
                'query' => $q,
                'businesses' => collect(),
                'products' => collect(),
                'attractions' => collect(),
            ]);
        }

        $businesses = Business::where('is_published', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%$q%");
            })
            ->limit(12)
            ->get();

        $products = Product::with('business')
            ->whereHas('business', fn($b) => $b->where('is_published', true))
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                      ->orWhere('description', 'like', "%$q%");
            })
            ->limit(12)
            ->get();

        $attractions = TouristSpot::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                      ->orWhere('location', 'like', "%$q%")
                      ->orWhere('description', 'like', "%$q%")
                      ->orWhere('additional_info', 'like', "%$q%");
            })
            ->limit(12)
            ->get();

        return view('customer.search-results', compact('q', 'businesses', 'products', 'attractions'))
            ->with('query', $q);
    }

    /**
     * Show all approved resorts.
     */
    public function resorts(Request $request)
    {
        $query = BusinessProfile::with(['business', 'gallery'])
            ->where('business_type', 'resort')
            ->where('status', 'approved')
            ->whereHas('business', function($q) {
                $q->where('is_published', true);
            });

        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('address', 'like', "%{$request->get('location')}%");
        }

        // Price range filter
        if ($request->filled('price_range')) {
            $priceRange = $request->get('price_range');
            if ($priceRange !== '') {
                if ($priceRange === '10000+') {
                    $query->whereHas('rooms', function($q) {
                        $q->where('price_per_night', '>=', 10000);
                    })->orWhereHas('cottages', function($q) {
                        $q->where('price_per_night', '>=', 10000);
                    });
                } else {
                    [$min, $max] = explode('-', $priceRange);
                    $query->whereHas('rooms', function($q) use ($min, $max) {
                        $q->whereBetween('price_per_night', [$min, $max]);
                    })->orWhereHas('cottages', function($q) use ($min, $max) {
                        $q->whereBetween('price_per_night', [$min, $max]);
                    });
                }
            }
        }

        $resorts = $query->withCount(['rooms', 'cottages'])
            ->with(['rooms' => function($q) {
                $q->select('business_profile_id', 'price_per_night')->orderBy('price_per_night');
            }])
            ->paginate(12);

        // Add minimum price to each resort (ratings are already available from BusinessProfile)
        foreach ($resorts as $resort) {
            $minRoomPrice = $resort->rooms->min('price_per_night');
            $minCottagePrice = $resort->cottages()->min('price_per_night');
            
            $resort->min_price = collect([$minRoomPrice, $minCottagePrice])->filter()->min();
            // average_rating and total_ratings are already available from BusinessProfile model
        }

        // Get unique locations for filter dropdown
        $locations = BusinessProfile::where('business_type', 'resort')
            ->where('status', 'approved')
            ->whereHas('business', function($q) {
                $q->where('is_published', true);
            })
            ->distinct()
            ->pluck('address')
            ->map(function($address) {
                // Extract city/area from address
                $parts = explode(',', $address);
                return trim(end($parts));
            })
            ->unique()
            ->sort()
            ->values();

        return view('customer.resorts', compact('resorts', 'locations'));
    }

    /**
     * Show a specific resort.
     */
    public function showResort($id)
    {
        $businessProfile = BusinessProfile::with(['business', 'gallery'])
            ->where('business_type', 'resort')
            ->where('status', 'approved')
            ->whereHas('business', function($q) {
                $q->where('is_published', true);
            })
            ->findOrFail($id);

        // Create a business object for consistency with the view
        $business = $businessProfile->business;
        $business->businessProfile = $businessProfile;

        // Get rooms and cottages for this resort (only available ones for customers)
        $rooms = $businessProfile->rooms()
            ->with('images')
            ->where('is_available', true)
            ->get();

        $cottages = $businessProfile->cottages()
            ->with('galleries')
            ->where('is_available', true)
            ->get();

        return view('customer.resort-show', compact('business', 'rooms', 'cottages'));
    }

    /**
     * Show all tourist spots.
     */
    public function touristSpots(Request $request)
    {
        $searchQuery = $request->get('search');
        
        $touristSpotsQuery = TouristSpot::with(['uploader'])
            ->where('is_active', true);
        
        // Apply search filter if provided
        if ($searchQuery) {
            $touristSpotsQuery->where(function($query) use ($searchQuery) {
                $query->where('name', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('location', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('description', 'LIKE', "%{$searchQuery}%");
            });
        }
        
        $touristSpots = $touristSpotsQuery->orderBy('created_at', 'desc')->get();

        // Add like counts and ratings for each tourist spot
        foreach ($touristSpots as $spot) {
            // Get like count
            $spot->total_likes = \DB::table('tourist_spot_likes')
                ->where('tourist_spot_id', $spot->id)
                ->count();

            // Get rating data
            $ratings = \DB::table('tourist_spot_ratings')
                ->where('tourist_spot_id', $spot->id)
                ->get();

            $spot->total_ratings = $ratings->count();
            $spot->average_rating = $ratings->count() > 0 ? $ratings->avg('rating') : 0;
        }

        return view('customer.attractions', compact('touristSpots'));
    }

    /**
     * Show a specific tourist spot.
     */
    public function showTouristSpot($id)
    {
        $touristSpot = TouristSpot::with([
            'uploader', 
            'ratings.user', 
            'likes',
            'comments.user.profile'
        ])->findOrFail($id);
        
        $userRating = null;
        $userLike = null;
        if (Auth::check()) {
            $userRating = $touristSpot->ratings()
                ->where('user_id', Auth::id())
                ->first();
            $userLike = $touristSpot->likes()
                ->where('user_id', Auth::id())
                ->exists();
        }

        return view('customer.tourist-spot-show', compact('touristSpot', 'userRating', 'userLike'));
    }

    public function toggleLike($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $touristSpot = TouristSpot::findOrFail($id);
        $userId = Auth::id();
        $existingLike = $touristSpot->likes()->where('user_id', $userId)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $touristSpot->likes()->create(['user_id' => $userId]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $touristSpot->likes()->count()
        ]);
    }

    public function rateTouristSpot(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $touristSpot = TouristSpot::findOrFail($id);

        TouristSpotRating::updateOrCreate(
            [
                'tourist_spot_id' => $touristSpot->id,
                'user_id' => Auth::id()
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment
            ]
        );

        $touristSpot->updateRating();

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!',
            'average_rating' => $touristSpot->fresh()->average_rating,
            'total_ratings' => $touristSpot->fresh()->total_ratings
        ]);
    }

    public function getTouristSpotComments($id)
    {
        $touristSpot = TouristSpot::findOrFail($id);
        $comments = $touristSpot->comments()->with('user.profile')->whereHas('user')->latest()->get();

        return response()->json([
            'success' => true,
            'comments' => $comments->map(function ($comment) {
                if (!$comment->user) {
                    return null;
                }
                
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user_id' => $comment->user->id,
                    'created_at' => $comment->created_at->format('M j, Y g:i A'),
                    'created_at_human' => $comment->created_at->diffForHumans(),
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'profile_picture' => $comment->user->profile ? $comment->user->profile->profile_picture : null,
                        'profile_avatar' => null // Not using profile_avatar anymore
                    ]
                ];
            })->filter()->values()
        ]);
    }

    public function commentTouristSpot(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $touristSpot = TouristSpot::findOrFail($id);

        $touristSpot->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment posted successfully!'
        ]);
    }

    // Business Profile (Hotels, Products, Resorts) Methods
    public function toggleBusinessProfileLike($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $businessProfile = BusinessProfile::findOrFail($id);
        $userId = Auth::id();
        $existingLike = $businessProfile->likes()->where('user_id', $userId)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $businessProfile->likes()->create(['user_id' => $userId]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $businessProfile->likes()->count()
        ]);
    }

    public function rateBusinessProfile(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5'
        ]);

        $businessProfile = BusinessProfile::findOrFail($id);

        BusinessProfileRating::updateOrCreate(
            [
                'business_profile_id' => $businessProfile->id,
                'user_id' => Auth::id()
            ],
            [
                'rating' => $request->rating
            ]
        );

        $businessProfile->updateRating();

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!',
            'average_rating' => $businessProfile->fresh()->average_rating,
            'total_ratings' => $businessProfile->fresh()->total_ratings
        ]);
    }

    public function getBusinessProfileComments($id)
    {
        $businessProfile = BusinessProfile::findOrFail($id);
        $comments = $businessProfile->comments()->with('user')->latest()->get();

        return response()->json([
            'comments' => $comments->map(function ($comment) {
                return [
                    'user_name' => $comment->user->name,
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at->diffForHumans(),
                ];
            })
        ]);
    }

    public function commentBusinessProfile(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $businessProfile = BusinessProfile::findOrFail($id);

        $businessProfile->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment posted successfully!'
        ]);
    }

    // Product-specific Methods
    public function toggleProductLike($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $product = Product::findOrFail($id);
        $userId = Auth::id();
        $existingLike = $product->likes()->where('user_id', $userId)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $product->likes()->create(['user_id' => $userId]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $product->likes()->count()
        ]);
    }

    public function rateProduct(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5'
        ]);

        $product = Product::findOrFail($id);

        $product->ratings()->updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => Auth::id()
            ],
            [
                'rating' => $request->rating
            ]
        );

        $product->updateRating();

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!',
            'average_rating' => $product->fresh()->average_rating,
            'total_ratings' => $product->fresh()->total_ratings
        ]);
    }

    public function getProductComments($id)
    {
        $product = Product::findOrFail($id);
        $comments = $product->comments()->with('user.profile')->whereHas('user')->latest()->get();

        return response()->json([
            'comments' => $comments->map(function ($comment) {
                if (!$comment->user) {
                    return null;
                }
                
                return [
                    'id' => $comment->id,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'profile_picture' => $comment->user->profile ? $comment->user->profile->profile_picture : null
                    ],
                    'comment' => $comment->comment,
                    'created_at_human' => $comment->created_at->diffForHumans(),
                    'created_at' => $comment->created_at->toISOString()
                ];
            })->filter()->values()
        ]);
    }

    public function commentProduct(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $product = Product::findOrFail($id);

        $product->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment posted successfully!'
        ]);
    }
}