<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login'); // ✅ Correct path
    }

    // Handle login form submission
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:customer,business_owner,admin',
            // business_type is required only when logging in as business_owner without an existing business profile
            'business_type' => 'nullable|in:local_products,hotel,resort',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the selected role matches the user's registered role
            if ($user->role !== $request->input('role')) {
                Auth::logout();
                return back()->withErrors([
                    'role' => 'The selected role does not match your registered role.',
                ])->withInput($request->only('email', 'role'));
            }

            // Redirect based on user role
            if ($user->role === 'admin') {
                // Redirect admins to the welcome page with admin panel
                return redirect()->route('dashboard');
            }

            // For business owners, check if they have a business profile
            if ($user->role === 'business_owner') {
                // If no business profile exists, redirect to setup with the business type
                if (!$user->businessProfile) {
                    $businessType = $request->input('business_type');
                    if (!$businessType) {
                        return back()->withErrors([
                            'business_type' => 'Please choose a business type to continue.',
                        ])->withInput($request->only('email', 'role'));
                    }
                    // Persist selection and go to setup (BusinessController@setup reads from session('business_type'))
                    session(['business_type' => $businessType]);
                    return redirect()->route('business.setup')
                        ->with('info', 'Please complete your business setup.');
                }
            } else if (!$user->profile) {
                // For non-business users (customers), check if they have a profile
                return redirect()->route('profile.setup');
            }

            // Redirect based on user type
            if ($user->role === 'business_owner') {
                // If business profile exists, route by type
                if ($user->businessProfile) {
                    $type = $user->businessProfile->business_type;
                    switch ($type) {
                        case 'hotel':
                            return redirect()->route('business.my-hotel');
                        case 'resort':
                            return redirect()->route('business.my-resort');
                        default:
                            return redirect()->route('business.my-shop');
                    }
                }

                // No business profile yet: require a business_type from the login form
                $businessType = $request->input('business_type');
                if (!$businessType) {
                    return back()->withErrors([
                        'business_type' => 'Please choose a business type to continue.',
                    ])->withInput($request->only('email', 'role'));
                }

                // Persist selection and go to setup (BusinessController@setup reads from session('business_type'))
                session(['business_type' => $businessType]);
                return redirect()->route('business.setup')
                    ->with('info', 'Please complete your business setup.');
            }

            // Default redirect for customers
            return redirect()->route('customer.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'role'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    /**
     * Show the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a password reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}