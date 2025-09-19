<?php

namespace App\Http\Controllers;
/**
 * @mixin \Illuminate\Routing\Controller
 */
/**
 * @method void middleware(array $middlewares)
 */
class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        return view('dashboard.admin-dashboard');
    }
}
