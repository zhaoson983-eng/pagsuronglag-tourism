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
     * Simplified to fetch all content from business_profiles table
     */
    public function getFeedData(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        try {
            $page = $request->get('page', 1);
            $perPage = 10;
            $offset = ($page - 1) * $perPage;

            // Get all approved business profiles (this includes shops, hotels, resorts)
            $businessProfiles = BusinessProfile::with(['business', 'galleries'])
                ->where('status', 'approved')
                ->whereHas('business', function($q) {
                    $q->where('is_published', true);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            // Get active tourist spots (separate table)
            $touristSpots = TouristSpot::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get();

            // Combine all items into a single feed
            $feedItems = collect();

            // Add business profiles (shops, hotels, resorts)
            foreach ($businessProfiles as $profile) {
                $user = auth()->user();
                
                // Determine the type and route based on business_type
                $type = $profile->business_type ?? 'business';
                $route = 'customer.business.show';
                $routeParam = $profile->business ? $profile->business->id : $profile->id;
                
                if ($type === 'hotel') {
                    $route = 'customer.hotels.show';
                } elseif ($type === 'resort') {
                    $route = 'customer.resorts.show';
                    $routeParam = $profile->id;
                }
                
                // Get the cover image
                $image = null;
                if ($profile->cover_image) {
                    $image = Storage::url($profile->cover_image);
                } elseif ($profile->business && $profile->business->cover_image) {
                    $image = Storage::url($profile->business->cover_image);
                } elseif ($profile->galleries && $profile->galleries->isNotEmpty()) {
                    $image = Storage::url($profile->galleries->first()->image_path);
                }
                
                // Get actual counts based on business type
                $likeCount = 0;
                $commentCount = 0;
                $userLiked = false;
                $userRating = 0;
                $avgRating = 0;
                $ratingCount = 0;
                
                if ($type === 'hotel') {
                    $likeCount = $profile->hotelLikes()->count();
                    $commentCount = $profile->hotelComments()->count();
                    $userLiked = auth()->check() ? $profile->hotelLikes()->where('user_id', auth()->id())->exists() : false;
                    $avgRating = (float)($profile->hotelRatings()->avg('rating') ?? 0);
                    $ratingCount = (int)$profile->hotelRatings()->count();
                    if (auth()->check()) {
                        $rating = $profile->hotelRatings()->where('user_id', auth()->id())->first();
                        $userRating = $rating ? $rating->rating : 0;
                    }
                } elseif ($type === 'resort') {
                    $likeCount = $profile->resortLikes()->count();
                    $commentCount = $profile->resortComments()->count();
                    $userLiked = auth()->check() ? $profile->resortLikes()->where('user_id', auth()->id())->exists() : false;
                    $avgRating = (float)($profile->resortRatings()->avg('rating') ?? 0);
                    $ratingCount = (int)$profile->resortRatings()->count();
                    if (auth()->check()) {
                        $rating = $profile->resortRatings()->where('user_id', auth()->id())->first();
                        $userRating = $rating ? $rating->rating : 0;
                    }
                } else {
                    // For local_products/shops, use business relationship
                    $business = $profile->business;
                    $likeCount = $business ? $business->likes()->count() : 0;
                    $commentCount = $business ? $business->comments()->count() : 0;
                    $userLiked = $business && auth()->check() ? $business->isLikedBy(auth()->user()) : false;
                    $avgRating = (float)($business ? $business->average_rating ?? 0 : $profile->average_rating ?? 0);
                    $ratingCount = (int)($business ? $business->total_ratings ?? 0 : $profile->total_ratings ?? 0);
                    if ($business && auth()->check()) {
                        $rating = $business->ratings()->where('user_id', auth()->id())->first();
                        $userRating = $rating ? $rating->rating : 0;
                    }
                }

                $feedItems->push([
                    'type' => $profile->business_type, // Use actual business type (hotel/resort/shop)
                    'id' => $profile->id, // Use business profile ID for hotel/resort API calls
                    'title' => $profile->business_name ?? ($profile->business ? $profile->business->name : 'Business'),
                    'location' => $profile->address ?? 'Location not specified',
                    'description' => $profile->description ?? '',
                    'image' => $image,
                    'profile_avatar' => $profile->profile_avatar ? Storage::url($profile->profile_avatar) : null,
                    'rating' => $avgRating,
                    'rating_count' => $ratingCount,
                    'like_count' => $likeCount,
                    'comment_count' => $commentCount,
                    'user_liked' => $userLiked,
                    'user_rating' => $userRating,
                    'status' => 'Published',
                    'url' => route($route, $routeParam),
                    'created_at' => $profile->created_at->toIso8601String()
                ]);
            }

            // Add tourist spots
            foreach ($touristSpots as $spot) {
                $image = null;
                if ($spot->cover_image) {
                    $image = Storage::url($spot->cover_image);
                } elseif ($spot->image) {
                    $image = Storage::url($spot->image);
                }
                
                // Get actual counts for tourist spots
                $likeCount = $spot->likes()->count();
                $commentCount = $spot->comments ? $spot->comments()->count() : 0;
                $userLiked = auth()->check() ? $spot->likes()->where('user_id', auth()->id())->exists() : false;
                $userRating = 0;
                if (auth()->check()) {
                    $rating = $spot->ratings()->where('user_id', auth()->id())->first();
                    $userRating = $rating ? $rating->rating : 0;
                }

                $feedItems->push([
                    'type' => 'attraction',
                    'id' => $spot->id,
                    'title' => $spot->name ?? 'Tourist Spot',
                    'location' => $spot->location ?? 'Location not specified',
                    'description' => $spot->description ?? '',
                    'image' => $image,
                    'profile_avatar' => null,
                    'rating' => (float)($spot->average_rating ?? 0),
                    'rating_count' => (int)($spot->total_ratings ?? 0),
                    'like_count' => $likeCount,
                    'comment_count' => $commentCount,
                    'user_liked' => $userLiked,
                    'user_rating' => $userRating,
                    'status' => 'Published',
                    'url' => route('customer.attractions.show', $spot->id),
                    'created_at' => $spot->created_at->toIso8601String()
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
                'currentPage' => $page,
                'total' => $feedItems->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Feed Data Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to load feed data',
                'message' => $e->getMessage()
            ], 500);
        }
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
            // Load business profile with galleries only (skip comments for now)
            $business->load(['businessProfile.galleries']);
            
            // Get rooms through business profile relationship
            $rooms = collect(); // Initialize empty collection
            if ($business->businessProfile) {
                $rooms = $business->businessProfile->rooms()
                    ->with('images')
                    ->where('is_available', true)
                    ->get();
            }
            
            // Hotels only have rooms, resorts have both rooms and cottages
            if ($business->businessProfile->business_type === 'hotel') {
                return view('customer.hotel-show', compact('business', 'rooms'));
            } else {
                // For resorts, also load cottages
                $cottages = collect(); // Initialize empty collection
                if ($business->businessProfile) {
                    $cottages = $business->businessProfile->cottages()
                        ->with('galleries')
                        ->where('is_available', true)
                        ->get();
                }
                return view('customer.resort-show', compact('business', 'rooms', 'cottages'));
            }
        } else {
            // Load products and galleries for regular businesses (skip comments for now)
            $business->load([
                'products', 
                'businessProfile.galleries'
            ]);
            $products = $business->products;
            
            return view('customer.business-show', compact('business', 'products'));
        }
    }

    /**
     * Search across businesses, products, and attractions.
     */
    /**
     * Get hotels feed data - simplified to use business_profiles table
     */
    public function getHotelsFeedData(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $page = $request->get('page', 1);
            $perPage = 10;
            $offset = ($page - 1) * $perPage;

            // Get hotels from business_profiles table
            $hotels = BusinessProfile::with(['business', 'galleries'])
                ->where('business_type', 'hotel')
                ->where('status', 'approved')
                ->whereHas('business', function($q) {
                    $q->where('is_published', true);
                })
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($profile) {
                    // Get the cover image
                    $image = null;
                    if ($profile->cover_image) {
                        $image = Storage::url($profile->cover_image);
                    } elseif ($profile->business && $profile->business->cover_image) {
                        $image = Storage::url($profile->business->cover_image);
                    } elseif ($profile->galleries && $profile->galleries->isNotEmpty()) {
                        $image = Storage::url($profile->galleries->first()->image_path);
                    }
                    
                    // Get actual counts from hotel-specific tables
                    $likeCount = $profile->hotelLikes()->count();
                    $commentCount = $profile->hotelComments()->count();
                    $userLiked = auth()->check() ? $profile->hotelLikes()->where('user_id', auth()->id())->exists() : false;
                    $userRating = 0;
                    if (auth()->check()) {
                        $rating = $profile->hotelRatings()->where('user_id', auth()->id())->first();
                        $userRating = $rating ? $rating->rating : 0;
                    }

                    return [
                        'type' => 'hotel', // Use 'hotel' for API consistency
                        'id' => $profile->id, // Use business profile ID for hotel API calls
                        'title' => $profile->business_name ?? ($profile->business ? $profile->business->name : 'Hotel'),
                        'location' => $profile->address ?? 'Location not specified',
                        'description' => $profile->description ?? '',
                        'image' => $image,
                        'profile_avatar' => $profile->profile_avatar ? Storage::url($profile->profile_avatar) : null,
                        'rating' => (float)($profile->hotelRatings()->avg('rating') ?? 0),
                        'rating_count' => (int)$profile->hotelRatings()->count(),
                        'like_count' => $likeCount,
                        'comment_count' => $commentCount,
                        'user_liked' => $userLiked,
                        'user_rating' => $userRating,
                        'status' => 'Published',
                        'url' => route('customer.hotels.show', $profile->business ? $profile->business->id : $profile->id),
                        'created_at' => $profile->created_at->toIso8601String()
                    ];
                });

            return response()->json([
                'items' => $hotels->slice($offset, $perPage)->values(),
                'hasMore' => $hotels->count() > ($offset + $perPage),
                'total' => $hotels->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Hotels Feed Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load hotels'], 500);
        }
    }

    /**
     * Get resorts feed data - simplified to use business_profiles table
     */
    public function getResortsFeedData(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $page = $request->get('page', 1);
            $perPage = 10;
            $offset = ($page - 1) * $perPage;

            // Get resorts from business_profiles table
            $resorts = BusinessProfile::with(['business', 'galleries'])
                ->where('business_type', 'resort')
                ->where('status', 'approved')
                ->whereHas('business', function($q) {
                    $q->where('is_published', true);
                })
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($profile) {
                    // Get the cover image
                    $image = null;
                    if ($profile->cover_image) {
                        $image = Storage::url($profile->cover_image);
                    } elseif ($profile->business && $profile->business->cover_image) {
                        $image = Storage::url($profile->business->cover_image);
                    } elseif ($profile->galleries && $profile->galleries->isNotEmpty()) {
                        $image = Storage::url($profile->galleries->first()->image_path);
                    }
                    
                    // Get actual counts from resort-specific tables
                    $likeCount = $profile->resortLikes()->count();
                    $commentCount = $profile->resortComments()->count();
                    $userLiked = auth()->check() ? $profile->resortLikes()->where('user_id', auth()->id())->exists() : false;
                    $userRating = 0;
                    if (auth()->check()) {
                        $rating = $profile->resortRatings()->where('user_id', auth()->id())->first();
                        $userRating = $rating ? $rating->rating : 0;
                    }

                    return [
                        'type' => 'resort', // Use 'resort' for API consistency
                        'id' => $profile->id, // Use business profile ID for resort API calls
                        'title' => $profile->business_name ?? 'Resort',
                        'location' => $profile->address ?? 'Location not specified',
                        'description' => $profile->description ?? '',
                        'image' => $image,
                        'profile_avatar' => $profile->profile_avatar ? Storage::url($profile->profile_avatar) : null,
                        'rating' => (float)($profile->resortRatings()->avg('rating') ?? 0),
                        'rating_count' => (int)$profile->resortRatings()->count(),
                        'like_count' => $likeCount,
                        'comment_count' => $commentCount,
                        'user_liked' => $userLiked,
                        'user_rating' => $userRating,
                        'status' => 'Published',
                        'url' => route('customer.resorts.show', $profile->id),
                        'created_at' => $profile->created_at->toIso8601String()
                    ];
                });

            return response()->json([
                'items' => $resorts->slice($offset, $perPage)->values(),
                'hasMore' => $resorts->count() > ($offset + $perPage),
                'total' => $resorts->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Resorts Feed Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load resorts'], 500);
        }
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
        // Temporarily disabled - comment tables don't exist
        return response()->json([
            'comments' => []
        ]);
    }

    public function commentBusinessProfile(Request $request, $id)
    {
        // Temporarily disabled - comment tables don't exist
        return response()->json([
            'error' => 'Comments are temporarily disabled'
        ], 503);
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
        // Temporarily disabled - comment tables don't exist
        return response()->json([
            'comments' => []
        ]);
    }

    public function commentProduct(Request $request, $id)
    {
        // Temporarily disabled - comment tables don't exist
        return response()->json([
            'error' => 'Comments are temporarily disabled'
        ], 503);
    }
}