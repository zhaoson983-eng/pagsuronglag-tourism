<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Business;
use App\Models\BusinessProfile;
use App\Models\Order;
use App\Models\Profile;
use App\Models\TouristSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with key metrics and user analytics.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Top Selling Products (by total quantity sold)
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->with('product') // Eager load to avoid N+1
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Gender Distribution (from user profiles)
        $genderDistribution = Profile::select('sex', DB::raw('COUNT(*) as count'))
            ->whereNotNull('sex')
            ->groupBy('sex')
            ->get();

        // Age Distribution (grouped into ranges)
        $ageDistribution = Profile::whereNotNull('age')
            ->select(
                DB::raw('CASE 
                    WHEN age BETWEEN 12 AND 16 THEN "12-16"
                    WHEN age BETWEEN 17 AND 20 THEN "17-20"
                    WHEN age BETWEEN 21 AND 24 THEN "21-24"
                    WHEN age BETWEEN 25 AND 28 THEN "25-28"
                    WHEN age BETWEEN 29 AND 40 THEN "29-40"
                    ELSE "40+"
                END as age_group'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('age_group')
            ->orderByRaw('MIN(age)')
            ->get();

        // Location Distribution (top 5 locations by user count)
        $locationDistribution = Profile::select('location', DB::raw('COUNT(*) as count'))
            ->whereNotNull('location')
            ->groupBy('location')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Most Visited Shops (businesses with most unique visitors)
        $mostVisitedShops = DB::table('orders')
            ->select('business_id', DB::raw('COUNT(DISTINCT user_id) as visitor_count'))
            ->whereNotNull('business_id')
            ->groupBy('business_id')
            ->orderByDesc('visitor_count')
            ->get()
            ->map(function ($item) {
                $business = Business::find($item->business_id);
                return (object) [
                    'business_id' => $item->business_id,
                    'business_name' => optional($business)->name ?? 'Unknown Business',
                    'visitor_count' => $item->visitor_count,
                ];
            });

        // Top Business Owners (by number of products listed)
        $topBusinessOwners = Business::withCount('products')
            ->with('owner') // Load business owner
            ->orderByDesc('products_count')
            ->limit(5)
            ->get();

        // User counts by role
        $counts = [
            'customers' => User::where('role', 'customer')->count(),
            'businessOwners' => User::where('role', 'business_owner')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        // Product and Business counts
        $productCount = Product::count();
        $businessCount = Business::count();
        
        // Hotel, Resort, and Tourist Spot counts
        $hotelCount = BusinessProfile::where('business_type', 'hotel')->where('status', 'approved')->count();
        $resortCount = BusinessProfile::where('business_type', 'resort')->where('status', 'approved')->count();
        $touristSpotCount = TouristSpot::count();
        
        // Top Hotels by rating
        $topHotels = BusinessProfile::where('business_type', 'hotel')
            ->where('status', 'approved')
            ->whereNotNull('average_rating')
            ->where('average_rating', '>', 0)
            ->orderByDesc('average_rating')
            ->orderByDesc('total_ratings')
            ->limit(5)
            ->get();
            
        // Top Resorts by rating
        $topResorts = BusinessProfile::where('business_type', 'resort')
            ->where('status', 'approved')
            ->whereNotNull('average_rating')
            ->where('average_rating', '>', 0)
            ->orderByDesc('average_rating')
            ->orderByDesc('total_ratings')
            ->limit(5)
            ->get();
            
        // Top Tourist Spots by rating
        $topTouristSpots = TouristSpot::whereNotNull('average_rating')
            ->orderByDesc('average_rating')
            ->orderByDesc('total_ratings')
            ->limit(5)
            ->get();

        // Get 5 most recently registered users with profile data
        $recentUsers = User::with('profile')
            ->latest()
            ->take(5)
            ->get();

        // Get all businesses with owner, products, and product count
        $businesses = Business::with(['owner', 'products'])
            ->withCount('products')
            ->get();

        // Pass all data to the dashboard view
        return view('admin.dashboard', compact(
            'topProducts',
            'genderDistribution',
            'ageDistribution',
            'locationDistribution',
            'mostVisitedShops',
            'topBusinessOwners',
            'counts',
            'productCount',
            'businessCount',
            'hotelCount',
            'resortCount',
            'touristSpotCount',
            'topHotels',
            'topResorts',
            'topTouristSpots',
            'recentUsers',
            'businesses'
        ));
    }

    /**
     * Display and filter the list of users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\StreamedResponse
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Export to CSV
        if ($request->filled('export') && $request->export === 'csv') {
            return $this->exportUsers($query->get());
        }

        // Paginate results
        $users = $query->latest()->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Show archived users list with restore option.
     */
    public function archivedUsers(Request $request)
    {
        $users = User::onlyTrashed()->latest()->paginate(20);
        return view('admin.users-archived', compact('users'));
    }

    /**
     * Show the edit user form.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Update user details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:customer,business_owner,admin',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    /**
     * Archive any user (customer or business owner). Admins cannot be archived.
     */
    public function archiveUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Admins cannot be archived.');
        }

        if ($user->trashed()) {
            return back()->with('error', 'User is already archived.');
        }

        // If business owner, also archive their business
        if ($user->role === 'business_owner') {
            optional($user->business)->delete();
        }

        $user->delete();

        return back()->with('success', 'User archived successfully.');
    }

    /**
     * Restore an archived user.
     */
    public function restoreUser($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if (!$user->trashed()) {
            return back()->with('error', 'User is not archived.');
        }

        $user->restore();

        // If business owner, also restore their business
        if ($user->role === 'business_owner') {
            optional($user->business()->withTrashed()->first())->restore();
        }

        return back()->with('success', 'User restored successfully.');
    }

    /**
     * Show the upload promotion menu (Explore Lagonoy).
     *
     * @return \Illuminate\View\View
     */
    public function uploadPromotion()
    {
        return view('admin.uploadpromotion');
    }

    /**
     * Show the upload hotels form.
     *
     * @return \Illuminate\View\View
     */
    public function uploadHotels()
    {
        return view('admin.upload.hotels');
    }

    /**
     * Show the upload resorts form.
     *
     * @return \Illuminate\View\View
     */
    public function uploadResorts()
    {
        return view('admin.upload.resorts');
    }

    /**
     * Show the upload attractions form.
     *
     * @return \Illuminate\View\View
     */
    public function uploadAttractions()
    {
        return view('admin.upload.attractions');
    }

    /**
     * Show the upload tourist spots form with statistics.
     *
     * @return \Illuminate\View\View
     */
    public function uploadSpots()
    {
        // Get all tourist spots (remove is_active filter to show all spots)
        $touristSpots = TouristSpot::orderBy('created_at', 'desc')->get();

        // Most Popular (by likes count)
        $mostPopular = TouristSpot::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->first();

        // Top Rated (by average rating)
        $topRated = TouristSpot::where('total_ratings', '>', 0)
            ->orderBy('average_rating', 'desc')
            ->orderBy('total_ratings', 'desc')
            ->first();

        // Most Talked (by comments count)
        $mostTalked = TouristSpot::withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->first();

        return view('admin.upload-spots', compact(
            'touristSpots',
            'mostPopular',
            'topRated',
            'mostTalked'
        ));
    }

    /**
     * Export users list to CSV.
     *
     * @param  \Illuminate\Support\Collection  $users
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function exportUsers($users)
    {
        $fileName = 'users_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = ['ID', 'Name', 'Email', 'Role', 'Status', 'Created At'];

        $callback = function () use ($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    ucfirst($user->role),
                    $user->trashed() ? 'Archived' : 'Active',
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Show a business for admin viewing with ratings.
     */
    public function showBusiness(Business $business)
    {
        // Load business profile and products with ratings
        $business->load(['businessProfile', 'products.ratings']);
        $products = $business->products;

        return view('customer.business-show', compact('business', 'products'));
    }
}