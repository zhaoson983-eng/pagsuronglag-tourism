<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TermsController extends Controller
{
    public function show()
    {
        $from = request()->query('from', '');
        $user = Auth::user();

        // Determine the appropriate redirect based on user role and setup completion
        $redirectRoute = 'home'; // default fallback

        if ($user->role === 'customer' && $user->hasCompletedProfile()) {
            $redirectRoute = 'customer.dashboard';
        } elseif ($user->role === 'business_owner' && $user->businessProfile) {
            switch ($user->businessProfile->business_type) {
                case 'hotel':
                    $redirectRoute = 'business.my-hotel';
                    break;
                case 'resort':
                    $redirectRoute = 'business.my-resort';
                    break;
                default:
                    $redirectRoute = 'business.my-shop';
                    break;
            }
        } elseif ($user->role === 'customer' && !$user->hasCompletedProfile()) {
            $redirectRoute = 'profile.setup';
        } elseif ($user->role === 'business_owner' && !$user->businessProfile) {
            $redirectRoute = 'business.setup';
        }

        return view('terms', compact('from', 'redirectRoute'));
    }

    public function accept(Request $request)
    {
        $user = Auth::user();

        // Record that user has accepted terms
        // Note: Database column will be added later via migration
        // For now, just redirect to appropriate dashboard

        $from = $request->query('from', '');
        $redirectRoute = $request->query('redirect_route', 'home');

        // Redirect to appropriate dashboard based on user role
        if ($user->role === 'customer' && $user->hasCompletedProfile()) {
            return redirect()->route('customer.dashboard')
                ->with('success', 'Terms and Conditions accepted! Welcome to your dashboard.');
        } elseif ($user->role === 'business_owner' && $user->businessProfile) {
            switch ($user->businessProfile->business_type) {
                case 'hotel':
                    return redirect()->route('business.my-hotel')
                        ->with('success', 'Terms and Conditions accepted! Welcome to your hotel dashboard.');
                case 'resort':
                    return redirect()->route('business.my-resort')
                        ->with('success', 'Terms and Conditions accepted! Welcome to your resort dashboard.');
                default:
                    return redirect()->route('business.my-shop')
                        ->with('success', 'Terms and Conditions accepted! Welcome to your shop dashboard.');
            }
        }

        return redirect()->route('home')
            ->with('success', 'Terms and Conditions accepted!');
    }

    public function decline(Request $request)
    {
        $from = $request->query('from', '');
        $redirectRoute = $request->query('redirect_route', 'home');

        // For declining terms, redirect to landing page (home)
        if ($from === 'profile_setup' || $from === 'business_setup' || $from === 'business_profile' || $from === 'business_profile_update') {
            return redirect()->route('home')
                ->with('warning', 'You must accept the Terms and Conditions to continue using our platform.');
        }

        return redirect()->route('home')
            ->with('warning', 'Terms and Conditions must be accepted to use our services.');
    }
}
