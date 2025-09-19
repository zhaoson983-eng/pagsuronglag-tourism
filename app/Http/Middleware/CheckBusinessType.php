<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBusinessType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$types
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        \Log::info('CheckBusinessType middleware executed', [
            'path' => $request->path(),
            'types' => $types,
            'user_id' => Auth::id()
        ]);

        $user = Auth::user();

        // If user is not authenticated, redirect to login
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        // Get the user's business profile
        $businessProfile = $user->businessProfile;
        
        // If no business profile, redirect to setup
        if (!$businessProfile) {
            return redirect()->route('business.setup')
                ->with('error', 'Please complete your business profile first.');
        }

        // Get the business type from the profile
        $businessType = $businessProfile->business_type ?? null;

        \Log::info('Business type check', [
            'user_id' => $user->id,
            'business_type' => $businessType,
            'allowed_types' => $types
        ]);

        // If no types provided or business type is in the allowed types, proceed
        if (empty($types) || in_array($businessType, $types)) {
            return $next($request);
        }

        // User doesn't have the required business type
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized. This area is restricted to specific business types.'], 403);
        }

        // Redirect based on business type
        switch ($businessType) {
            case 'hotel':
                return redirect()->route('business.my-hotel')
                    ->with('error', 'You do not have permission to access the shop dashboard.');
            case 'resort':
                return redirect()->route('business.my-resort')
                    ->with('error', 'You do not have permission to access the shop dashboard.');
            case 'shop':
            default:
                return redirect()->route('business.my-shop')
                    ->with('error', 'This area is restricted to hotel/resort businesses.');
        }
    }
}
