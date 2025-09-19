<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @method void middleware(array $middlewares)
 */
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            // Redirect customers to their dashboard
            return redirect()->route('customer.dashboard');
        } elseif ($user->role === 'business_owner') {
            // Redirect business owners to their shop
            return redirect()->route('business.my-shop');
        }

        // For admins, show the main dashboard
        return view('dashboard');
    }
}