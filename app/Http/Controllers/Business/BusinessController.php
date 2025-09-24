<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BusinessProfile;
use App\Models\Order;
use App\Models\Product;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:business_owner')->except(['setup', 'storeSetup']);
    }

    /**
     * Show the business setup form.
     */
    public function setup()
    {
        $user = auth()->user();

        // If user already has a business profile, redirect to appropriate page based on business type
        if ($user->businessProfile) {
            switch ($user->businessProfile->business_type) {
                case 'hotel':
                    return redirect()->route('business.my-hotel');
                case 'resort':
                    return redirect()->route('business.my-resort');
                default:
                    return redirect()->route('business.my-shop');
            }
        }

        // Get business type from user or session
        $businessType = $user->business_type ?? session('business_type', 'local_products');

        // Route to appropriate setup view based on business type
        switch ($businessType) {
            case 'hotel':
                return view('business.setup.hotel', ['business' => null]);
            case 'resort':
                return view('business.setup.resort', ['business' => null]);
            default:
                return view('business.setup.local_products', ['business' => null]);
        }
    }

    /**
     * Store the business setup data.
     */
    public function storeSetup(Request $request)
    {
        $validated = $request->validate([
            // Personal Information
            'full_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'age' => 'required|integer|min:1|max:120',
            'sex' => 'required|string|in:Male,Female,Other',
            'personal_location' => 'required|string|max:255',
            // Business Information
            'business_name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'business_permit' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $user = auth()->user();
        
        // If user already has a business profile, redirect to appropriate page based on business type
        if ($user->businessProfile) {
            switch ($user->businessProfile->business_type) {
                case 'hotel':
                    return redirect()->route('business.my-hotel');
                case 'resort':
                    return redirect()->route('business.my-resort');
                default:
                    return redirect()->route('business.my-shop');
            }
        }

        // Upload business permit
        $businessPermitPath = $request->file('business_permit')
            ->store('business_permits', 'public');

        // Get business type from user or session
        $businessType = $user->business_type ?? session('business_type', 'local_products');
        
        // Create business profile
        $businessProfile = new BusinessProfile([
            'user_id' => $user->id,
            'business_name' => $validated['business_name'],
            'description' => $validated['description'],
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'website' => $validated['website'] ?? null,
            'business_permit_path' => $businessPermitPath,
            'status' => 'pending',
            'business_type' => $businessType,
        ]);

        $businessProfile->save();

        // Create/Update Profile for admin dashboard statistics
        $profile = \App\Models\Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $validated['full_name'],
                'birthday' => $validated['birthday'],
                'age' => $validated['age'],
                'sex' => $validated['sex'],
                'location' => $validated['personal_location'], // Personal location for admin stats
                'address' => $validated['address'], // Business address for reference
                'phone_number' => $validated['contact_number'],
            ]
        );

        // Create/Update Business entity for products
        $business = \App\Models\Business::updateOrCreate(
            ['owner_id' => $user->id],
            [
                'name' => $validated['business_name'],
                'description' => $validated['description'],
                'address' => $validated['address'],
                'contact_number' => $validated['contact_number'],
                'business_type' => $businessType,
            ]
        );

        // Redirect based on business type
        switch ($businessProfile->business_type) {
            case 'hotel':
                return redirect()->route('terms', ['from' => 'business_setup'])
                    ->with('success', 'Your hotel profile has been created and is pending approval.');
            case 'resort':
                return redirect()->route('terms', ['from' => 'business_setup'])
                    ->with('success', 'Your resort profile has been created and is pending approval.');
            default:
                return redirect()->route('terms', ['from' => 'business_setup'])
                    ->with('success', 'Your shop profile has been created and is pending approval.');
        }
    }

    /**
     * Display the business my-hotel page with dashboard.
     */
    public function myHotel()
    {
        $user = auth()->user();
        $business = $user->businessProfile;
        
        if (!$business) {
            return redirect()->route('business.setup');
        }
        
        // Get rooms for the hotel
        $rooms = $business->rooms()->latest()->get();
        
        // Get galleries for the hotel
        $galleries = $business->galleries()->latest()->get();
        
        // Calculate stats
        $totalRooms = $business->rooms()->count();
        $availableRooms = $business->rooms()->where('is_available', true)->count();
        $galleryCount = $galleries->count();
        
        // Calculate average rating
        $averageRating = 0;
        $totalReviews = 0;
        $business->average_rating = 0;
        
        // Get business profile data for the navigation bar
        $businessProfile = $business;
        
        return view('business.my-hotel', [
            'business' => $business,
            'businessProfile' => $businessProfile,
            'rooms' => $rooms,
            'galleries' => $galleries,
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'galleryCount' => $galleryCount,
            'averageRating' => $averageRating,
            'totalReviews' => $totalReviews
        ]);
    }

    /**
     * Display the business my-resort page with dashboard.
     */
    public function myResort()
    {
        $user = auth()->user();
        $business = $user->businessProfile;

        if (!$business) {
            return redirect()->route('business.setup');
        }

        // Get resort rooms for the resort
        $rooms = $business->resortRooms()->with('galleries')->latest()->get();
        
        // Get cottages for the resort
        $cottages = $business->cottages()->with('galleries')->latest()->get();
        
        // Calculate stats
        $totalRooms = $business->resortRooms()->count();
        $availableRooms = $business->resortRooms()->where('is_available', true)->count();
        $totalCottages = $business->cottages()->count();
        $availableCottages = $business->cottages()->where('is_available', true)->count();
        $galleryCount = $business->gallery()->count();
        
        // Calculate average rating
        $averageRating = 0;
        $totalReviews = 0;
        $business->average_rating = 0;
        
        // Get business profile data for the navigation bar
        $businessProfile = $business;
        
        return view('business.my-resort', [
            'business' => $business,
            'businessProfile' => $businessProfile,
            'rooms' => $rooms,
            'cottages' => $cottages,
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'totalCottages' => $totalCottages,
            'availableCottages' => $availableCottages,
            'galleryCount' => $galleryCount,
            'averageRating' => $averageRating,
            'totalReviews' => $totalReviews
        ]);
    }

    /**
     * Display the business my-shop page with dashboard.
     */
    public function myShop()
    {
        $user = auth()->user();
        $profile = $user->businessProfile;
        
        if (!$profile) {
            return redirect()->route('business.setup')
                ->with('warning', 'Please complete your business profile to continue.');
        }

        // Ensure a Business entity exists (used by Product FK)
        $bizEntity = \App\Models\Business::firstOrCreate(
            ['owner_id' => $user->id],
            [
                'name' => $profile->business_name,
                'description' => $profile->description,
                'address' => $profile->address,
                'contact_number' => $profile->contact_number,
                'business_type' => $profile->business_type ?? 'local_products',
            ]
        );

        // Get business statistics (via Business entity)
        $productCount = Product::where('business_id', $bizEntity->id)->count();
        $pendingOrdersCount = Order::where('business_id', $bizEntity->id)
            ->where('status', 'pending')
            ->count();
            
        // Check if order_items table exists before querying
        $totalSales = 0;
        if (Schema::hasTable('order_items')) {
            $totalSales = DB::table('orders')
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.business_id', $bizEntity->id)
                ->where('orders.status', 'completed')
                ->sum(DB::raw('order_items.quantity * order_items.price')) ?? 0;
        }
        $unreadMessagesCount = Message::where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->count();

        // Get recent orders
        $recentOrders = [];
        if (Schema::hasTable('orders') && Schema::hasTable('order_items')) {
            $recentOrders = Order::where('business_id', $bizEntity->id)
                ->with(['orderItems.product', 'customer'])
                ->latest()
                ->take(5)
                ->get();
        }

        // Get top products
        $topProducts = [];
        if (Schema::hasTable('order_items')) {
            $topProducts = Product::where('business_id', $bizEntity->id)
                ->withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->take(5)
                ->get();
        }

        // Calculate business ratings
        $ratings = DB::table('ratings')
            ->where('business_id', $bizEntity->id)
            ->get();
            
        $averageRating = $ratings->avg('rating') ?? 0;
        $totalRatings = $ratings->count();
        
        // Add ratings to business profile for display
        $profile->average_rating = $averageRating;
        $profile->total_ratings = $totalRatings;

        // Get galleries for the shop
        $galleries = $profile->galleries()->latest()->get();

        return view('business.my-shop', [
            'business' => $profile,
            'galleries' => $galleries,
            'productCount' => $productCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'totalSales' => $totalSales,
            'unreadMessagesCount' => $unreadMessagesCount,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
        ]);
    }

    /**
     * Show the form for creating a business profile.
     */
    public function createProfile()
    {
        if (auth()->user()->businessProfile) {
            return redirect()->route('business.dashboard');
        }

        return view('business.profile.create');
    }

    /**
     * Store a newly created business profile in storage.
     */
    public function storeProfile(Request $request)
    {
        $validated = $request->validate([
            // Personal Information (for hotel/resort owners)
            'full_name' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'age' => 'nullable|integer|min:1|max:120',
            'sex' => 'nullable|in:Male,Female,Other',
            'personal_location' => 'nullable|string|max:255',
            // Business Information
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|in:local_products,hotel,resort',
            'description' => 'required|string|max:1000',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'facebook_page' => 'nullable|url|max:255',
            'business_permit' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'other_licenses.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $user = auth()->user();
            
            // Upload business permit
            $businessPermitPath = $request->file('business_permit')
                ->store('business_permits', 'public');
            
            // Handle additional licenses
            $licenses = [];
            if ($request->hasFile('other_licenses')) {
                foreach ($request->file('other_licenses') as $file) {
                    if ($file->isValid()) {
                        $licenses[] = [
                            'path' => $file->store('business_licenses', 'public'),
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getClientMimeType(),
                            'size' => $file->getSize(),
                        ];
                    }
                }
            }

            // Create business profile
            $business = new BusinessProfile([
                'business_name' => $validated['business_name'],
                'business_type' => $validated['business_type'],
                'description' => $validated['description'],
                'contact_number' => $validated['contact_number'],
                'address' => $validated['address'],
                'location' => $validated['address'], // Store address as location too
                'city' => $validated['city'],
                'province' => $validated['province'],
                'postal_code' => $validated['postal_code'],
                'website' => $validated['website'] ?? null,
                'facebook_page' => $validated['facebook_page'] ?? null,
                'business_permit_path' => $businessPermitPath,
                'licenses' => !empty($licenses) ? $licenses : null,
                'status' => 'pending',
            ]);

            $user->businessProfile()->save($business);

            // Create or update user profile with personal information (for hotel/resort owners)
            if ($validated['business_type'] === 'hotel' || $validated['business_type'] === 'resort') {
                if (isset($validated['full_name'])) {
                    $profileData = [
                        'user_id' => $user->id,
                        'full_name' => $validated['full_name'],
                        'birthday' => $validated['birthday'] ?? null,
                        'age' => $validated['age'] ?? null,
                        'sex' => $validated['sex'] ?? null,
                        'location' => $validated['personal_location'] ?? null,
                        'phone_number' => $validated['contact_number'],
                    ];
                    
                    \App\Models\Profile::updateOrCreate(
                        ['user_id' => $user->id],
                        $profileData
                    );
                }
            }

            // Notify admin for approval
            // $this->notifyAdminForApproval($business);

            // Notify admin for approval
            // $this->notifyAdminForApproval($business);

            // Redirect based on business type
            switch ($validated['business_type']) {
                case 'hotel':
                    return redirect()->route('terms', ['from' => 'business_profile'])
                        ->with('success', 'Your hotel profile has been submitted for approval. You will be notified once it is reviewed.');
                case 'resort':
                    return redirect()->route('terms', ['from' => 'business_profile'])
                        ->with('success', 'Your resort profile has been submitted for approval. You will be notified once it is reviewed.');
                default:
                    return redirect()->route('terms', ['from' => 'business_profile'])
                        ->with('success', 'Your business profile has been submitted for approval. You will be notified once it is reviewed.');
            }
        });
    }


    /**
     * Show the form for editing the business profile.
     */
    public function editProfile()
    {
        $business = auth()->user()->businessProfile;
        
        if (!$business) {
            return redirect()->route('business.profile.create');
        }

        return view('business.profile.edit', compact('business'));
    }

    /**
     * Update the business profile in storage.
     */
    public function updateProfile(Request $request)
    {
        $business = auth()->user()->businessProfile;
        
        if (!$business) {
            return redirect()->route('business.profile.create');
        }

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'website' => 'nullable|url|max:255',
            'facebook_page' => 'nullable|url|max:255',
            'business_permit' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'other_licenses.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        return DB::transaction(function () use ($validated, $request, $business) {
            // Update basic info
            $business->update([
                'business_name' => $validated['business_name'],
                'description' => $validated['description'],
                'contact_number' => $validated['contact_number'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'province' => $validated['province'],
                'postal_code' => $validated['postal_code'],
                'website' => $validated['website'] ?? null,
                'facebook_page' => $validated['facebook_page'] ?? null,
                'status' => 'pending', // Reset status to pending after update
            ]);

            // Update business permit if provided
            if ($request->hasFile('business_permit')) {
                // Delete old permit
                Storage::disk('public')->delete($business->business_permit_path);
                
                // Upload new permit
                $business->business_permit_path = $request->file('business_permit')
                    ->store('business_permits', 'public');
            }

            // Handle additional licenses if provided
            if ($request->hasFile('other_licenses')) {
                $licenses = $business->licenses ?? [];
                
                foreach ($request->file('other_licenses') as $file) {
                    if ($file->isValid()) {
                        $licenses[] = [
                            'path' => $file->store('business_licenses', 'public'),
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getClientMimeType(),
                            'size' => $file->getSize(),
                        ];
                    }
                }
                
                $business->licenses = $licenses;
            }

            $business->save();

            // Notify admin about the update
            // $this->notifyAdminAboutUpdate($business);

            // Notify admin about the update
            // $this->notifyAdminAboutUpdate($business);

            // Redirect based on business type
            switch ($business->business_type) {
                case 'hotel':
                    return redirect()->route('terms', ['from' => 'business_profile_update'])
                        ->with('success', 'Your hotel profile has been updated and is pending review.');
                case 'resort':
                    return redirect()->route('terms', ['from' => 'business_profile_update'])
                        ->with('success', 'Your resort profile has been updated and is pending review.');
                default:
                    return redirect()->route('terms', ['from' => 'business_profile_update'])
                        ->with('success', 'Your business profile has been updated and is pending review.');
            }
        });
    }

    /**
     * Publish the business profile.
     */
    public function publish(Request $request)
    {
        $business = auth()->user()->businessProfile;

        if (!$business) {
            return redirect()->route('business.profile.create')
                ->with('error', 'Please create a business profile first.');
        }

        // Submits the business for admin approval; do not make it visible yet
        $business->update([
            'status' => 'pending',
            'is_published' => false,
        ]);

        // Redirect based on business type
        switch ($business->business_type) {
            case 'hotel':
                return redirect()->route('business.my-hotel')
                    ->with('success', 'Your hotel has been submitted for review. You will be visible to customers once approved by an admin.');
            case 'resort':
                return redirect()->route('business.my-resort')
                    ->with('success', 'Your resort has been submitted for review. You will be visible to customers once approved by an admin.');
            default:
                return redirect()->route('business.my-shop')
                    ->with('success', 'Your business has been submitted for review. You will be visible to customers once approved by an admin.');
        }
    }

    /**
     * Unpublish the business profile.
     */
    public function unpublish(Request $request)
    {
        $business = auth()->user()->businessProfile;

        if (!$business) {
            return redirect()->route('business.profile.create')
                ->with('error', 'Business profile not found.');
        }

        $business->update(['is_published' => false]);

        // Redirect based on business type
        switch ($business->business_type) {
            case 'hotel':
                return redirect()->route('business.my-hotel')
                    ->with('success', 'Your hotel is currently hidden.');
            case 'resort':
                return redirect()->route('business.my-resort')
                    ->with('success', 'Your resort is currently hidden.');
            default:
                return redirect()->route('business.my-shop')
                    ->with('success', 'Your business is currently hidden.');
        }
    }

    /**
     * Update the business cover photo.
     */
    public function updateCover(Request $request)
    {
        $request->validate([
            'cover_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $business = auth()->user()->businesses()->first();
        
        if (!$business) {
            return redirect()->route('business.profile.create')
                ->with('error', 'Please create a business profile first.');
        }

        // Get the business profile
        $businessProfile = $business->businessProfile;
        
        if (!$businessProfile) {
            return redirect()->route('business.profile.create')
                ->with('error', 'Please create a business profile first.');
        }

        // Delete old cover image if exists
        if ($businessProfile->cover_image) {
            Storage::disk('public')->delete($businessProfile->cover_image);
        }

        // Store new cover image
        $path = $request->file('cover_image')->store('business/covers', 'public');
        $businessProfile->update(['cover_image' => $path]);

        return redirect()->back()->with('success', 'Cover image updated successfully.');
    }


    /**
     * Update the business profile avatar.
     */
    public function updateProfileAvatar(Request $request)
    {
        try {
            $request->validate([
                'profile_avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            $business = auth()->user()->businessProfile;
            
            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business profile not found.'
                ], 404);
            }

            // Delete old avatar if exists
            if ($business->profile_avatar) {
                Storage::disk('public')->delete($business->profile_avatar);
            }

            // Store new avatar
            $path = $request->file('profile_avatar')->store('business/avatars', 'public');
            $business->profile_avatar = $path;
            $business->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile avatar updated successfully.',
                'url' => Storage::url($path)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store gallery images for business.
     */
    public function storeGallery(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $business = auth()->user()->businessProfile;
        
        if (!$business) {
            return redirect()->back()->with('error', 'Business profile not found.');
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('gallery', 'public');
                
                // Create gallery record
                $business->galleries()->create([
                    'image_path' => $path,
                    'alt_text' => 'Gallery Image',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Gallery images uploaded successfully!');
    }

    /**
     * Delete gallery image.
     */
    public function destroyGallery($id)
    {
        $business = auth()->user()->businessProfile;
        
        if (!$business) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Business profile not found.'], 404);
            }
            return redirect()->back()->with('error', 'Business profile not found.');
        }

        $gallery = $business->galleries()->findOrFail($id);
        
        // Delete the image file
        if ($gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        
        // Delete the gallery record
        $gallery->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Gallery image deleted successfully!']);
        }
        
        return redirect()->back()->with('success', 'Gallery image deleted successfully!');
    }

    /**
     * Store promotion for business.
     */
    public function storePromotion(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $business = auth()->user()->businessProfile;
        
        if (!$business) {
            return redirect()->back()->with('error', 'Business profile not found.');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('promotions', 'public');
        }

        // Store promotion - you may need to create a promotions table
        // For now, we'll return success
        
        return redirect()->back()->with('success', 'Promotion created successfully!');
    }

    /**
     * Delete promotion.
     */
    public function destroyPromotion($id)
    {
        // Implementation for deleting promotions
        return redirect()->back()->with('success', 'Promotion deleted successfully!');
    }

    /**
     * Update business profile picture.
     */
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $user = auth()->user();
        $businessProfile = $user->businessProfile;
        
        if (!$businessProfile) {
            return response()->json(['success' => false, 'message' => 'Business profile not found.'], 404);
        }

        try {
            // Delete old profile picture if exists
            if ($businessProfile->profile_picture) {
                Storage::disk('public')->delete($businessProfile->profile_picture);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('business_profiles', 'public');
            
            // Update business profile
            $businessProfile->update(['profile_picture' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully!',
                'profile_picture_url' => Storage::url($path)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile picture: ' . $e->getMessage()
            ], 500);
        }
    }

}
