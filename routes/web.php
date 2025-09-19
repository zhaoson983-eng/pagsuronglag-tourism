<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Test routes for session and CSRF debugging
Route::get('/test-session', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'token' => csrf_token(),
        'session_data' => session()->all()
    ]);
});

Route::post('/test-csrf', function () {
    return response()->json([
        'message' => 'CSRF token is valid!',
        'session_id' => session()->getId()
    ]);
});
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ResortController;
use App\Http\Controllers\AttractionController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CommentController;

// =========================
// PUBLIC ROUTES
// =========================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Public browsing
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Comment count routes (accessible to all)
Route::get('/businesses/{business}/comment-count', [RatingController::class, 'getBusinessCommentCount'])->name('businesses.comment-count');

// Unified Rating and Like System
Route::middleware('auth')->group(function () {
    // Unified rating and like routes
    Route::post('/businesses/{business}/rate', [RatingController::class, 'rateBusiness'])->name('businesses.rate');
    Route::post('/businesses/{business}/like', [RatingController::class, 'toggleBusinessLike'])->name('businesses.like');
    Route::post('/businesses/{business}/comment', [RatingController::class, 'commentBusiness'])->name('businesses.comment');
    Route::get('/businesses/{business}/comments', [RatingController::class, 'getBusinessComments'])->name('businesses.comments');
    Route::delete('/comments/{comment}', [RatingController::class, 'deleteComment'])->name('comments.delete');
    
    Route::post('/products/{product}/rate', [RatingController::class, 'rateProduct'])->name('products.rate');
    Route::post('/products/{product}/like', [RatingController::class, 'toggleProductLike'])->name('products.like');
    Route::post('/products/{product}/comment', [RatingController::class, 'commentProduct'])->name('products.comment');
    Route::get('/products/{product}/comments', [RatingController::class, 'getProductComments'])->name('products.comments');
    
    Route::post('/hotels/{businessProfile}/rate', [RatingController::class, 'rateHotel'])->name('hotels.rate');
    Route::post('/hotels/{businessProfile}/like', [RatingController::class, 'toggleHotelLike'])->name('hotels.like');
    Route::post('/hotels/{businessProfile}/comment', [RatingController::class, 'commentHotel'])->name('hotels.comment');
    Route::get('/hotels/{businessProfile}/comments', [RatingController::class, 'getHotelComments'])->name('hotels.comments');
    Route::delete('/hotel-comments/{comment}', [RatingController::class, 'deleteHotelComment'])->name('hotel-comments.delete');
    
    Route::post('/resorts/{businessProfile}/rate', [RatingController::class, 'rateResort'])->name('resorts.rate');
    Route::post('/resorts/{businessProfile}/like', [RatingController::class, 'toggleResortLike'])->name('resorts.like');
    Route::post('/resorts/{businessProfile}/comment', [RatingController::class, 'commentResort'])->name('resorts.comment');
    Route::get('/resorts/{businessProfile}/comments', [RatingController::class, 'getResortComments'])->name('resorts.comments');
    Route::delete('/resort-comments/{comment}', [RatingController::class, 'deleteResortComment'])->name('resort-comments.delete');
    
    // Room and cottage ratings (legacy support)
    Route::post('/rooms/{room}/rate', [RatingController::class, 'rateRoom'])->name('room.rate');
    Route::post('/cottages/{cottage}/rate', [RatingController::class, 'rateCottage'])->name('cottage.rate');
    
    // Tourist spot routes
    Route::post('/tourist-spots/{touristSpot}/rate', [RatingController::class, 'rateTouristSpot'])->name('tourist-spot.rate');
    Route::post('/tourist-spots/{touristSpot}/like', [RatingController::class, 'toggleTouristSpotLike'])->name('tourist-spot.like');
    Route::get('/tourist-spots/{touristSpot}/like-status', [RatingController::class, 'getTouristSpotLikeStatus'])->name('tourist-spot.like-status');
    Route::post('/tourist-spots/{touristSpot}/comment', [RatingController::class, 'commentTouristSpot'])->name('tourist-spot.comment');
    Route::get('/tourist-spots/{touristSpot}/comments', [RatingController::class, 'getTouristSpotComments'])->name('tourist-spot.comments');
    Route::delete('/tourist-spots/comments/{comment}', [RatingController::class, 'deleteTouristSpotComment'])->name('tourist-spot.comment.delete');
    
    // Comment likes and deletes (unified)
    Route::post('/comments/{comment}/like', [RatingController::class, 'toggleCommentLike'])->name('comments.like');
    Route::delete('/comments/{comment}', [RatingController::class, 'deleteCommentUnified'])->name('comments.delete');
});

// --- Dynamic Tourism Pages ---
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');

// Resort routes handled by CustomerController below

// Removed duplicate attractions routes - using CustomerController instead

// Auth
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [LoginController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [LoginController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [LoginController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

// =========================
// AUTHENTICATED USERS
// =========================

Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/setup', [ProfileController::class, 'setup'])->name('profile.setup');
    Route::post('/profile/setup', [ProfileController::class, 'store'])->name('profile.store');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    // AJAX profile picture upload
    Route::post('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture.update');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Business viewing (accessible by both customers and admins)
    Route::get('/customer/business/{business}', [CustomerController::class, 'showBusiness'])->name('customer.business.show');

    // =========================
    // CUSTOMER ROUTES
    // =========================
    // Feed routes - accessible to authenticated users
    Route::get('/customer/feed', [CustomerController::class, 'getFeedData'])->name('customer.feed');
    Route::get('/customer/products/feed', [ProductController::class, 'getProductsFeedData'])->name('customer.products.feed');
    
    Route::middleware(['role:customer'])->group(function () {
        Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
        // Search
        Route::get('/customer/search', [CustomerController::class, 'search'])->name('customer.search');

        Route::get('/customer/products', [ProductController::class, 'index'])->name('customer.products');
        Route::get('/customer/hotels', [ProductController::class, 'hotels'])->name('customer.hotels');
        Route::get('/customer/hotels/{business}', [CustomerController::class, 'showBusiness'])->name('customer.hotels.show');
        Route::get('/customer/product/{product}', [ProductController::class, 'show'])->name('customer.product.show');

        // Orders
        Route::get('/customer/order-details/{product}', [OrderController::class, 'showOrderDetails'])->name('customer.order.details');
        Route::get('/customer/orders', [OrderController::class, 'myOrders'])->name('customer.orders');
        Route::get('/customer/orders/{order}', [OrderController::class, 'orderDetails'])->name('customer.orders.show');
        Route::post('/customer/orders/{order}/message', [OrderController::class, 'sendMessage'])->name('customer.orders.message');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my');
        Route::delete('/customer/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('customer.orders.cancel');

        // Feedback
        Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

        // Messaging
        Route::get('/customer/messages', [MessageController::class, 'index'])->name('customer.messages');

        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('customer.cart');
        Route::post('/cart/add', [CartController::class, 'add'])->name('customer.cart.add');
        Route::put('/cart/{cart}', [CartController::class, 'update'])->name('customer.cart.update');
        Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('customer.cart.remove');
        Route::delete('/cart', [CartController::class, 'clear'])->name('customer.cart.clear');
        Route::post('/cart/checkout/{business}', [OrderController::class, 'checkout'])->name('customer.cart.checkout');
    });

    // =========================
    // BUSINESS OWNER ROUTES
    // =========================
    Route::middleware(['role:business_owner'])
        ->prefix('business')
        ->name('business.')
        ->group(function () {
            // Setup routes
            Route::get('/setup', [\App\Http\Controllers\Business\BusinessController::class, 'setup'])->name('setup');
            Route::post('/setup', [\App\Http\Controllers\Business\BusinessController::class, 'storeSetup'])->name('setup.store');
            
            // Main business dashboard
            Route::get('/my-shop', [\App\Http\Controllers\Business\BusinessController::class, 'myShop'])->name('my-shop');
            Route::get('/my-hotel', [\App\Http\Controllers\Business\BusinessController::class, 'myHotel'])->name('my-hotel');
            Route::get('/my-resort', [\App\Http\Controllers\Business\BusinessController::class, 'myResort'])->name('my-resort');
            
            // Products
            Route::get('/products', [ProductController::class, 'index'])->name('products');
            Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::put('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.stock.update');
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
            
            // Profile routes
            Route::post('/update-profile-avatar', [\App\Http\Controllers\Business\BusinessController::class, 'updateProfileAvatar'])->name('updateProfileAvatar');
            
            // Gallery routes
            Route::post('/gallery', [\App\Http\Controllers\Business\BusinessController::class, 'storeGallery'])->name('gallery.store');
            Route::delete('/gallery/{id}', [\App\Http\Controllers\Business\BusinessController::class, 'destroyGallery'])->name('gallery.destroy');
            
            // Promotion routes
            Route::post('/promotions', [\App\Http\Controllers\Business\BusinessController::class, 'storePromotion'])->name('promotions.store');
            Route::delete('/promotions/{id}', [\App\Http\Controllers\Business\BusinessController::class, 'destroyPromotion'])->name('promotions.destroy');
            
            // Resort Room routes
            Route::post('/rooms', [\App\Http\Controllers\Business\ResortRoomController::class, 'store'])->name('rooms.store');
            Route::get('/rooms/{room}/edit', [\App\Http\Controllers\Business\ResortRoomController::class, 'edit'])->name('rooms.edit');
            Route::put('/rooms/{room}', [\App\Http\Controllers\Business\ResortRoomController::class, 'update'])->name('rooms.update');
            Route::put('/rooms/{room}/availability', [\App\Http\Controllers\Business\ResortRoomController::class, 'toggleAvailability'])->name('rooms.availability');
            Route::delete('/rooms/{room}', [\App\Http\Controllers\Business\ResortRoomController::class, 'destroy'])->name('rooms.destroy');
            
            // Cottage routes
            Route::post('/cottages', [\App\Http\Controllers\Business\CottageController::class, 'store'])->name('cottages.store');
            Route::get('/cottages/{cottage}/edit', [\App\Http\Controllers\Business\CottageController::class, 'edit'])->name('cottages.edit');
            Route::put('/cottages/{cottage}', [\App\Http\Controllers\Business\CottageController::class, 'update'])->name('cottages.update');
            Route::post('/cottages/{cottage}/toggle', [\App\Http\Controllers\Business\CottageController::class, 'toggleAvailability'])->name('cottages.toggle');
            Route::delete('/cottages/{cottage}', [\App\Http\Controllers\Business\CottageController::class, 'destroy'])->name('cottages.destroy');
            
            // Shop specific routes
            Route::post('/publish-shop', [BusinessController::class, 'publishShop'])->name('publish-shop');
            Route::post('/unpublish-shop', [BusinessController::class, 'unpublishShop'])->name('unpublish-shop');
            
            // Backward compatibility
            Route::post('/publish', [\App\Http\Controllers\Business\BusinessController::class, 'publish'])->name('publish');
            Route::post('/unpublish', [\App\Http\Controllers\Business\BusinessController::class, 'unpublish'])->name('unpublish');

            // Profile picture upload route
            Route::put('/profile/picture', [\App\Http\Controllers\Business\BusinessController::class, 'updateProfilePicture'])->name('profile.updatePicture');
            
            // Common routes for all businesses
            Route::post('/update-cover', [\App\Http\Controllers\Business\BusinessController::class, 'updateCover'])->name('updateCover');
            Route::post('/update-avatar', [\App\Http\Controllers\Business\BusinessController::class, 'updateProfileAvatar'])->name('updateAvatar');
            Route::post('/update-profile-avatar', [\App\Http\Controllers\Business\BusinessController::class, 'updateProfileAvatar'])->name('updateProfileAvatar');
            Route::get('/orders', [OrderController::class, 'businessOrders'])->name('orders');
            Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update.status');
            Route::get('/messages', [MessageController::class, 'indexOwner'])->name('messages');
            Route::post('/gallery/upload', [\App\Http\Controllers\Business\BusinessController::class, 'galleryUpload'])->name('gallery.upload');
            Route::post('/gallery/store', [\App\Http\Controllers\Business\BusinessController::class, 'storeGallery'])->name('gallery.store');
            Route::delete('/gallery/{id}', [\App\Http\Controllers\Business\BusinessController::class, 'destroyGallery'])->name('gallery.destroy');
            
            // Room management routes
            Route::post('/rooms', [\App\Http\Controllers\Business\RoomController::class, 'store'])->name('rooms.store');
            Route::get('/rooms/{room}/edit', [\App\Http\Controllers\Business\RoomController::class, 'edit'])->name('rooms.edit');
            Route::put('/rooms/{room}', [\App\Http\Controllers\Business\RoomController::class, 'update'])->name('rooms.update');
            Route::delete('/rooms/{room}', [\App\Http\Controllers\Business\RoomController::class, 'destroy'])->name('rooms.destroy');
            
            // Cottage management routes
            Route::post('/cottages', [\App\Http\Controllers\Business\CottageController::class, 'store'])->name('cottages.store');
            Route::put('/cottages/{cottage}', [\App\Http\Controllers\Business\CottageController::class, 'update'])->name('cottages.update');
            Route::delete('/cottages/{cottage}', [\App\Http\Controllers\Business\CottageController::class, 'destroy'])->name('cottages.destroy');
            
            // Publishing routes
            Route::post('/publish', [\App\Http\Controllers\Business\BusinessController::class, 'publish'])->name('publish');
            Route::post('/unpublish', [\App\Http\Controllers\Business\BusinessController::class, 'unpublish'])->name('unpublish');
        });

    // =========================
    // SHARED MESSAGING ROUTES
    // =========================
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('/thread/{user}', [MessageController::class, 'thread'])->name('thread');
        Route::post('/send', [MessageController::class, 'send'])->name('send');
    });

    // Business Profile Routes (commented out - using BusinessController instead)
    // Route::prefix('business/profile')->name('business.profile.')->group(function () {
    //     Route::get('/', [BusinessProfileController::class, 'edit'])->name('edit');
    //     Route::put('/', [BusinessProfileController::class, 'update'])->name('update');
    //     Route::post('/picture', [BusinessProfileController::class, 'updatePicture'])->name('updatePicture');
    // });

    // =========================
    // ADMIN ROUTES
    // =========================
    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Business Approvals
            Route::prefix('business-approvals')->name('business-approvals.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\BusinessApprovalController::class, 'index'])->name('index');
                Route::get('/{business}', [\App\Http\Controllers\Admin\BusinessApprovalController::class, 'show'])->name('show');
                Route::post('/{business}/approve', [\App\Http\Controllers\Admin\BusinessApprovalController::class, 'approve'])->name('approve');
                Route::post('/{business}/reject', [\App\Http\Controllers\Admin\BusinessApprovalController::class, 'reject'])->name('reject');
                Route::post('/{business}/toggle-publish', [\App\Http\Controllers\Admin\BusinessApprovalController::class, 'togglePublish'])->name('toggle-publish');
                Route::get('/{business}/download/{type}/{index?}', [\App\Http\Controllers\Admin\BusinessApprovalController::class, 'downloadDocument'])->name('download');
            });
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
            Route::get('/users', [AdminController::class, 'users'])->name('users');
            Route::get('/users/archived', [AdminController::class, 'archivedUsers'])->name('users.archived');
            Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
            
            // Business viewing for admins
            Route::get('/business/{business}', [AdminController::class, 'showBusiness'])->name('business.show');
            Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
            Route::post('/drop-business-owner/{user}', [AdminController::class, 'dropBusinessOwner'])->name('drop.business');
            Route::post('/users/{user}/archive', [AdminController::class, 'archiveUser'])->name('users.archive');
            Route::post('/users/{user}/restore', [AdminController::class, 'restoreUser'])->name('users.restore');

            // Upload Promotion Landing Page
            Route::get('/uploadpromotion', [AdminController::class, 'uploadPromotion'])->name('uploadpromotion');

            // --- HOTELS ---
            Route::get('/upload/hotels', [\App\Http\Controllers\Admin\HotelController::class, 'create'])->name('upload.hotels');
            Route::post('/upload/hotels', [\App\Http\Controllers\Admin\HotelController::class, 'store'])->name('hotels.store');
            Route::get('/hotels', [\App\Http\Controllers\Admin\HotelController::class, 'index'])->name('hotels.index');
            Route::get('/hotels/{hotel}/edit', [\App\Http\Controllers\Admin\HotelController::class, 'edit'])->name('hotels.edit');
            Route::put('/hotels/{hotel}', [\App\Http\Controllers\Admin\HotelController::class, 'update'])->name('hotels.update');
            Route::delete('/hotels/{hotel}', [\App\Http\Controllers\Admin\HotelController::class, 'destroy'])->name('hotels.destroy');

            // --- RESORTS ---
            Route::get('/upload/resorts', [\App\Http\Controllers\Admin\ResortController::class, 'create'])->name('upload.resorts');
            Route::post('/upload/resorts', [\App\Http\Controllers\Admin\ResortController::class, 'store'])->name('resorts.store');
            Route::get('/resorts', [\App\Http\Controllers\Admin\ResortController::class, 'index'])->name('resorts.index');
            Route::get('/resorts/{resort}/edit', [\App\Http\Controllers\Admin\ResortController::class, 'edit'])->name('resorts.edit');
            Route::put('/resorts/{resort}', [\App\Http\Controllers\Admin\ResortController::class, 'update'])->name('resorts.update');
            Route::delete('/resorts/{resort}', [\App\Http\Controllers\Admin\ResortController::class, 'destroy'])->name('resorts.destroy');

            // --- TOURIST SPOTS ---
            Route::get('/upload-spots', [\App\Http\Controllers\AdminController::class, 'uploadSpots'])->name('upload.spots');
            Route::post('/upload-spots', [\App\Http\Controllers\Admin\TouristSpotController::class, 'store'])->name('upload.spots.store');
            Route::post('/upload-spots/gallery', [\App\Http\Controllers\Admin\TouristSpotController::class, 'uploadGallery'])->name('upload.spots.gallery');
            Route::get('/tourist-spots/{touristSpot}/edit', [\App\Http\Controllers\Admin\TouristSpotController::class, 'edit'])->name('tourist.spots.edit');
            Route::put('/tourist-spots/{touristSpot}', [\App\Http\Controllers\Admin\TouristSpotController::class, 'update'])->name('tourist.spots.update');
            Route::delete('/tourist-spots/{touristSpot}', [\App\Http\Controllers\Admin\TouristSpotController::class, 'destroy'])->name('tourist.spots.destroy');
        });
});

// =========================
// CUSTOMER RESORT ROUTES (Public)
// =========================
Route::get('/resorts', [CustomerController::class, 'resorts'])->name('customer.resorts');
Route::get('/resorts/{id}', [CustomerController::class, 'showResort'])->name('customer.resorts.show');

// =========================
// CUSTOMER TOURIST SPOTS ROUTES (Public)
// =========================
Route::get('/attractions', [CustomerController::class, 'touristSpots'])->name('customer.attractions');
Route::get('/attractions/{id}', [CustomerController::class, 'showTouristSpot'])->name('customer.attractions.show');

// Legacy tourist spot routes (kept for compatibility)
Route::middleware('auth')->group(function () {
    Route::post('/tourist-spots/{id}/like', [CustomerController::class, 'toggleLike'])->name('tourist-spots.like');
    Route::get('/tourist-spots/{id}/comments', [CustomerController::class, 'getTouristSpotComments'])->name('tourist-spots.comments');
    Route::post('/tourist-spots/{id}/comment', [CustomerController::class, 'commentTouristSpot'])->name('tourist-spots.comment');
});
// Product interaction routes
Route::post('/products/{product}/like', [ProductController::class, 'toggleLike'])->name('products.like');
Route::get('/products/{product}/comments', [ProductController::class, 'getComments'])->name('products.comments');
Route::post('/products/{product}/rate', [ProductController::class, 'rateProduct'])->name('products.rate');
Route::post('/products/{product}/comment', [ProductController::class, 'addComment'])->name('products.comment');
