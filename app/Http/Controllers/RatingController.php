<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Room;
use App\Models\Cottage;
use App\Models\TouristSpot;
use App\Models\TouristSpotRating;
use App\Models\BusinessProfile;
use App\Models\ProductRating;
use App\Models\ProductLike;
use App\Models\ProductComment;
use App\Models\BusinessRating;
use App\Models\BusinessLike;
use App\Models\BusinessComment;
use App\Models\HotelRating;
use App\Models\HotelLike;
use App\Models\HotelComment;
use App\Models\ResortRating;
use App\Models\ResortLike;
use App\Models\ResortComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function rateBusiness(Request $request, Business $business)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already rated this business
        $existingRating = BusinessRating::where('user_id', Auth::id())
            ->where('business_id', $business->id)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        } else {
            // Create new rating
            BusinessRating::create([
                'business_id' => $business->id,
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        }

        // Update business average rating
        $business->updateRating();

        return response()->json([
            'success' => true,
            'average_rating' => (float)($business->fresh()->average_rating ?? 0),
            'total_ratings' => $business->ratings()->count(),
            'user_rating' => (int)$request->rating,
        ]);
    }

    public function rateProduct(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Check if user already rated this product
        $existingRating = ProductRating::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
            ]);
        } else {
            // Create new rating
            ProductRating::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'rating' => $request->rating,
            ]);
        }

        // Update product average rating
        $product->updateRating();

        return response()->json([
            'success' => true,
            'average_rating' => (float) $product->fresh()->average_rating,
            'total_ratings' => $product->ratings()->count(),
            'user_rating' => (int) $request->rating,
        ]);
    }

    private function updateBusinessRating(Business $business)
    {
        $rating = $business->ratings()
            ->select(DB::raw('AVG(rating) as average_rating, COUNT(*) as total_ratings'))
            ->first();

        $business->update([
            'average_rating' => $rating->average_rating,
            'total_ratings' => $rating->total_ratings,
        ]);
    }

    public function rateRoom(Request $request, Room $room)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already rated this room
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('room_id', $room->id)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        } else {
            // Create new rating
            $rating = new Rating([
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            $room->ratings()->save($rating);
        }

        // Update room average rating
        $this->updateRoomRating($room);

        return response()->json([
            'success' => true,
            'average_rating' => $room->fresh()->average_rating,
            'total_ratings' => $room->ratings()->count(),
        ]);
    }

    public function rateCottage(Request $request, Cottage $cottage)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already rated this cottage
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('cottage_id', $cottage->id)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        } else {
            // Create new rating
            $rating = new Rating([
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            $cottage->ratings()->save($rating);
        }

        // Update cottage average rating
        $this->updateCottageRating($cottage);

        return response()->json([
            'success' => true,
            'average_rating' => $cottage->fresh()->average_rating,
            'total_ratings' => $cottage->ratings()->count(),
        ]);
    }

    private function updateProductRating(Product $product)
    {
        $rating = $product->ratings()
            ->select(DB::raw('AVG(rating) as average_rating, COUNT(*) as total_ratings'))
            ->first();

        $product->update([
            'average_rating' => $rating->average_rating,
            'total_ratings' => $rating->total_ratings,
        ]);
    }

    private function updateRoomRating(Room $room)
    {
        $rating = $room->ratings()
            ->select(DB::raw('AVG(rating) as average_rating, COUNT(*) as total_ratings'))
            ->first();

        $room->update([
            'average_rating' => $rating->average_rating,
            'total_ratings' => $rating->total_ratings,
        ]);
    }

    private function updateCottageRating(Cottage $cottage)
    {
        $rating = $cottage->ratings()
            ->select(DB::raw('AVG(rating) as average_rating, COUNT(*) as total_ratings'))
            ->first();

        $cottage->update([
            'average_rating' => $rating->average_rating,
            'total_ratings' => $rating->total_ratings,
        ]);
    }

    public function rateTouristSpot(Request $request, TouristSpot $touristSpot)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Check if user already rated this tourist spot
        $existingRating = TouristSpotRating::where('user_id', Auth::id())
            ->where('tourist_spot_id', $touristSpot->id)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
            ]);
        } else {
            // Create new rating
            TouristSpotRating::create([
                'tourist_spot_id' => $touristSpot->id,
                'user_id' => Auth::id(),
                'rating' => $request->rating,
            ]);
        }

        // Update tourist spot average rating
        $touristSpot->updateRating();

        return response()->json([
            'success' => true,
            'average_rating' => $touristSpot->fresh()->average_rating,
            'total_ratings' => $touristSpot->ratings()->count(),
            'tourist_spot_id' => $touristSpot->id,
            'user_rating' => $request->rating,
        ]);
    }

    /**
     * Rate a hotel (business profile with hotel type)
     */
    public function rateHotel(Request $request, BusinessProfile $businessProfile)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already rated this hotel
        $existingRating = HotelRating::where('user_id', Auth::id())
            ->where('business_profile_id', $businessProfile->id)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        } else {
            // Create new rating
            HotelRating::create([
                'business_profile_id' => $businessProfile->id,
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        }

        // Update hotel average rating
        $businessProfile->updateRating();

        return response()->json([
            'success' => true,
            'average_rating' => $businessProfile->fresh()->average_rating,
            'total_ratings' => $businessProfile->hotelRatings()->count(),
            'user_rating' => $request->rating,
        ]);
    }

    /**
     * Rate a resort (business profile with resort type)
     */
    public function rateResort(Request $request, BusinessProfile $businessProfile)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already rated this resort
        $existingRating = ResortRating::where('user_id', Auth::id())
            ->where('business_profile_id', $businessProfile->id)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        } else {
            // Create new rating
            ResortRating::create([
                'business_profile_id' => $businessProfile->id,
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        }

        // Update resort average rating
        $businessProfile->updateRating();

        return response()->json([
            'success' => true,
            'average_rating' => $businessProfile->fresh()->average_rating,
            'total_ratings' => $businessProfile->resortRatings()->count(),
            'user_rating' => $request->rating,
        ]);
    }

    /**
     * Like/Unlike a product
     */
    public function toggleProductLike(Request $request, Product $product)
    {
        $existingLike = ProductLike::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            ProductLike::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $product->likes()->count(),
            'likes_count' => $product->likes()->count(), // Keep both for compatibility
        ]);
    }

    /**
     * Like/Unlike a business
     */
    public function toggleBusinessLike(Request $request, Business $business)
    {
        $existingLike = BusinessLike::where('user_id', Auth::id())
            ->where('business_id', $business->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            BusinessLike::create([
                'business_id' => $business->id,
                'user_id' => Auth::id(),
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $business->likes()->count(),
            'likes_count' => $business->likes()->count(), // Keep both for compatibility
        ]);
    }

    /**
     * Like/Unlike a hotel
     */
    public function toggleHotelLike(Request $request, BusinessProfile $businessProfile)
    {
        $existingLike = HotelLike::where('user_id', Auth::id())
            ->where('business_profile_id', $businessProfile->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            HotelLike::create([
                'business_profile_id' => $businessProfile->id,
                'user_id' => Auth::id(),
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $businessProfile->hotelLikes()->count(),
        ]);
    }

    /**
     * Like/Unlike a resort
     */
    public function toggleResortLike(Request $request, BusinessProfile $businessProfile)
    {
        $existingLike = ResortLike::where('user_id', Auth::id())
            ->where('business_profile_id', $businessProfile->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            ResortLike::create([
                'business_profile_id' => $businessProfile->id,
                'user_id' => Auth::id(),
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $businessProfile->resortLikes()->count(),
        ]);
    }

    /**
     * Add a comment to a product
     */
    public function commentProduct(Request $request, Product $product)
    {
        try {
            $request->validate([
                'comment' => 'required|string|max:1000',
            ]);

            $comment = $product->comments()->create([
                'user_id' => Auth::id(),
                'comment' => $request->comment,
            ]);
            
            // Load the user relationship with profile
            $comment->load(['user' => function($query) {
                $query->select('id', 'name', 'email')
                    ->with('profile');
            }]);
            
            $avatarUrl = null;
            
            if ($comment->user->relationLoaded('profile') && $comment->user->profile) {
                if (!empty($comment->user->profile->profile_picture)) {
                    $avatarPath = $comment->user->profile->profile_picture;
                    if (Storage::exists($avatarPath)) {
                        $avatarUrl = Storage::url($avatarPath);
                    } else {
                        \Log::warning('Profile picture file not found when adding comment:', [
                            'user_id' => $comment->user->id,
                            'profile_picture_path' => $avatarPath
                        ]);
                    }
                }
            }
            
            // Fallback to default avatar if no profile picture found
            if (!$avatarUrl) {
                $avatarUrl = asset('images/default-avatar.png');
            }
            
            $commentData = [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->diffForHumans(),
                'created_at_human' => $comment->created_at->diffForHumans(),
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'profile_picture' => $avatarUrl,
                ],
                'user_name' => $comment->user->name,
                'can_delete' => true
            ];
            
            // Log successful comment addition
            \Log::info('New comment added to product', [
                'product_id' => $product->id,
                'comment_id' => $comment->id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'comment' => $commentData
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error adding comment to product: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Add a comment to a business
     */
    public function commentBusiness(Request $request, Business $business)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        BusinessComment::create([
            'business_id' => $business->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
        ]);
    }

    /**
     * Add a comment to a hotel
     */
    public function commentHotel(Request $request, BusinessProfile $businessProfile)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        HotelComment::create([
            'business_profile_id' => $businessProfile->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
        ]);
    }

    /**
     * Add a comment to a resort
     */
    public function commentResort(Request $request, BusinessProfile $businessProfile)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        ResortComment::create([
            'business_profile_id' => $businessProfile->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
        ]);
    }

    /**
     * Get comments for a product
     */
    public function getProductComments(Product $product)
    {
        try {
            // Debug: Log the product ID
            \Log::info('Fetching comments for product ID: ' . $product->id);
            
            $comments = $product->comments()
                ->with(['user' => function($query) {
                    $query->select('id', 'name', 'email')
                        ->with('profile');
                }])
                ->whereHas('user')
                ->latest()
                ->get();

            // Debug: Log the number of comments found
            \Log::info('Found ' . $comments->count() . ' comments for product ID: ' . $product->id);

            $formattedComments = $comments->map(function ($comment) {
                if (!$comment->user) {
                    \Log::warning('Comment has no user:', ['comment_id' => $comment->id]);
                    return null;
                }
                
                try {
                    $avatarUrl = null;
                    
                    if ($comment->user->relationLoaded('profile') && $comment->user->profile) {
                        if (!empty($comment->user->profile->profile_picture)) {
                            $avatarPath = $comment->user->profile->profile_picture;
                            $avatarUrl = Storage::url($avatarPath);
                        }
                    }
                    
                    // Fallback to default avatar if no profile picture found
                    if (!$avatarUrl) {
                        $avatarUrl = asset('images/default-avatar.png');
                    }
                    
                    return [
                        'id' => $comment->id,
                        'comment' => $comment->comment,
                        'created_at' => $comment->created_at->format('M j, Y g:i A'),
                        'created_at_human' => $comment->created_at->diffForHumans(),
                        'user_id' => $comment->user->id,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                            'profile_picture' => $avatarUrl,
                        ],
                        'user_name' => $comment->user->name,
                        'can_delete' => Auth::id() === $comment->user_id || (Auth::user() && Auth::user()->hasRole('admin'))
                    ];
                } catch (\Exception $e) {
                    \Log::error('Error processing comment: ' . $e->getMessage(), [
                        'comment_id' => $comment->id,
                        'user_id' => $comment->user_id,
                        'exception' => $e
                    ]);
                    return null;
                }
            })->filter()->values();

            return response()->json([
                'success' => true,
                'comments' => $formattedComments
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getProductComments: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load comments. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get comments for a business
     */
    public function getBusinessComments(Business $business)
    {
        $comments = $business->comments()->with('user.profile')->whereHas('user')->latest()->get();
        
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
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'profile_picture' => $comment->user->profile && $comment->user->profile->profile_picture ? 
                            Storage::url($comment->user->profile->profile_picture) : null,
                    ],
                    'user_name' => $comment->user->name,
                    'can_delete' => Auth::id() === $comment->user->id,
                    'created_at' => $comment->created_at->format('M j, Y g:i A'),
                    'created_at_human' => $comment->created_at->diffForHumans(),
                ];
            })->filter()->values(),
        ]);
    }

    /**
     * Get comments for a hotel
     */
    public function getHotelComments(BusinessProfile $businessProfile)
    {
        $comments = $businessProfile->hotelComments()->with('user.profile')->whereHas('user')->latest()->get();
        
        return response()->json([
            'success' => true,
            'comments' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user_id' => $comment->user->id,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'profile_picture' => $comment->user->profile && $comment->user->profile->profile_picture ? 
                            Storage::url($comment->user->profile->profile_picture) : null,
                    ],
                    'user_name' => $comment->user->name,
                    'can_delete' => Auth::id() === $comment->user->id,
                    'created_at' => $comment->created_at->format('M j, Y g:i A'),
                    'created_at_human' => $comment->created_at->diffForHumans(),
                ];
            }),
        ]);
    }

    /**
     * Get comments for a resort
     */
    public function getResortComments(BusinessProfile $businessProfile)
    {
        $comments = $businessProfile->resortComments()->with('user.profile')->whereHas('user')->latest()->get();
        
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
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'profile_picture' => $comment->user->profile && $comment->user->profile->profile_picture ? 
                            Storage::url($comment->user->profile->profile_picture) : null,
                    ],
                    'user_name' => $comment->user->name,
                    'can_delete' => Auth::id() === $comment->user->id,
                    'created_at' => $comment->created_at->format('M j, Y g:i A'),
                    'created_at_human' => $comment->created_at->diffForHumans(),
                ];
            })->filter()->values(),
        ]);
    }

    /**
     * Delete a business comment
     */
    public function deleteComment(BusinessComment $comment)
    {
        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        
        return response()->json(['success' => true, 'message' => 'Comment deleted successfully']);
    }

    /**
     * Delete a hotel comment
     */
    public function deleteHotelComment(HotelComment $comment)
    {
        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        
        return response()->json(['success' => true, 'message' => 'Comment deleted successfully']);
    }

    /**
     * Delete a resort comment
     */
    public function deleteResortComment(ResortComment $comment)
    {
        if (Auth::id() !== $comment->user_id && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
    
    /**
     * Delete a product comment
     */
    public function deleteProductComment(ProductComment $comment)
    {
        if (Auth::id() !== $comment->user_id && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $productId = $comment->product_id;
        $comment->delete();

        // Get updated comments count
        $commentsCount = ProductComment::where('product_id', $productId)->count();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
            'comments_count' => $commentsCount
        ]);
    }

    /**
     * Toggle like for tourist spot
     */
    public function toggleTouristSpotLike(Request $request, TouristSpot $touristSpot)
    {
        $userId = Auth::id();
        
        $existingLike = DB::table('tourist_spot_likes')
            ->where('user_id', $userId)
            ->where('tourist_spot_id', $touristSpot->id)
            ->first();

        if ($existingLike) {
            DB::table('tourist_spot_likes')
                ->where('user_id', $userId)
                ->where('tourist_spot_id', $touristSpot->id)
                ->delete();
            $liked = false;
        } else {
            DB::table('tourist_spot_likes')->insert([
                'user_id' => $userId,
                'tourist_spot_id' => $touristSpot->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $liked = true;
        }

        $totalLikes = DB::table('tourist_spot_likes')
            ->where('tourist_spot_id', $touristSpot->id)
            ->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'total_likes' => $totalLikes,
        ]);
    }

    /**
     * Comment on tourist spot
     */
    public function commentTouristSpot(Request $request, TouristSpot $touristSpot)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = DB::table('tourist_spot_comments')->insertGetId([
            'user_id' => Auth::id(),
            'tourist_spot_id' => $touristSpot->id,
            'comment' => $request->comment,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'comment_id' => $comment,
        ]);
    }

    /**
     * Get comments for tourist spot
     */
    public function getTouristSpotComments(TouristSpot $touristSpot)
    {
        $comments = DB::table('tourist_spot_comments')
            ->join('users', 'tourist_spot_comments.user_id', '=', 'users.id')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('tourist_spot_comments.tourist_spot_id', $touristSpot->id)
            ->select(
                'tourist_spot_comments.*',
                'users.name as user_name',
                'profiles.profile_picture'
            )
            ->orderBy('tourist_spot_comments.created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user_id' => $comment->user_id,
                    'user' => [
                        'id' => $comment->user_id,
                        'name' => $comment->user_name,
                        'profile_picture' => $comment->profile_picture ? Storage::url($comment->profile_picture) : null,
                    ],
                    'user_name' => $comment->user_name,
                    'can_delete' => Auth::id() === $comment->user_id,
                    'created_at' => \Carbon\Carbon::parse($comment->created_at)->format('M j, Y g:i A'),
                    'created_at_human' => \Carbon\Carbon::parse($comment->created_at)->diffForHumans(),
                ];
            }),
        ]);
    }

    public function getTouristSpotLikeStatus(Request $request, TouristSpot $touristSpot)
    {
        $userId = Auth::id();
        $liked = DB::table('tourist_spot_likes')
            ->where('user_id', $userId)
            ->where('tourist_spot_id', $touristSpot->id)
            ->exists();

        $totalLikes = DB::table('tourist_spot_likes')
            ->where('tourist_spot_id', $touristSpot->id)
            ->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'total_likes' => $totalLikes
        ]);
    }

    /**
     * Delete a tourist spot comment
     */
    public function deleteTouristSpotComment($commentId)
    {
        $comment = DB::table('tourist_spot_comments')
            ->where('id', $commentId)
            ->first();

        if (!$comment) {
            return response()->json(['success' => false, 'message' => 'Comment not found'], 404);
        }

        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        DB::table('tourist_spot_comments')
            ->where('id', $commentId)
            ->delete();
        
        return response()->json(['success' => true, 'message' => 'Comment deleted successfully']);
    }

    /**
     * Get comment count for a business
     */
    public function getBusinessCommentCount(Business $business)
    {
        $count = $business->comments()->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Toggle like on a comment (unified for all comment types)
     */
    public function toggleCommentLike($commentId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $userId = Auth::id();
        
        // Check if it's a business comment
        $businessComment = BusinessComment::find($commentId);
        if ($businessComment) {
            $existingLike = DB::table('business_comment_likes')
                ->where('user_id', $userId)
                ->where('comment_id', $commentId)
                ->first();

            if ($existingLike) {
                DB::table('business_comment_likes')
                    ->where('user_id', $userId)
                    ->where('comment_id', $commentId)
                    ->delete();
                $liked = false;
            } else {
                DB::table('business_comment_likes')->insert([
                    'user_id' => $userId,
                    'comment_id' => $commentId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $liked = true;
            }

            $likeCount = DB::table('business_comment_likes')
                ->where('comment_id', $commentId)
                ->count();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'like_count' => $likeCount
            ]);
        }

        // Check other comment types (hotel, resort, product, tourist spot)
        // For now, return a generic response
        return response()->json([
            'success' => true,
            'liked' => false,
            'like_count' => 0
        ]);
    }

    /**
     * Delete a comment (unified for all comment types)
     */
    public function deleteCommentUnified($commentId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $userId = Auth::id();

        // Check if it's a business comment
        $businessComment = BusinessComment::where('id', $commentId)->first();
        if ($businessComment) {
            if ($businessComment->user_id !== $userId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            $businessComment->delete();
            return response()->json(['success' => true, 'message' => 'Comment deleted successfully']);
        }

        // Check if it's a hotel comment
        $hotelComment = HotelComment::where('id', $commentId)->first();
        if ($hotelComment) {
            if ($hotelComment->user_id !== $userId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            $hotelComment->delete();
            return response()->json(['success' => true, 'message' => 'Comment deleted successfully']);
        }

        // Check if it's a resort comment
        $resortComment = ResortComment::where('id', $commentId)->first();
        if ($resortComment) {
            if ($resortComment->user_id !== $userId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            $resortComment->delete();
            return response()->json(['success' => true, 'message' => 'Comment deleted successfully']);
        }

        // Check if it's a product comment
        $productComment = ProductComment::where('id', $commentId)->first();
        if ($productComment) {
            if ($productComment->user_id !== $userId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            $productComment->delete();
            return response()->json(['success' => true, 'message' => 'Comment deleted successfully']);
        }

        // Check if it's a tourist spot comment
        $touristSpotComment = DB::table('tourist_spot_comments')
            ->where('id', $commentId)
            ->first();
        if ($touristSpotComment) {
            if ($touristSpotComment->user_id !== $userId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            DB::table('tourist_spot_comments')->where('id', $commentId)->delete();
            return response()->json(['success' => true, 'message' => 'Comment deleted successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Comment not found'], 404);
    }
}
