<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // Needed for Auth::login()
use App\Models\User; // Needed to find the User model

class LoginController extends Controller

{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // AuthenticatesUsers trait removed because the class/trait is not present
    // in the current environment; restore the trait import when vendor
    // dependencies are available or implement required auth methods here.

    /**
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
 
     
    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'guestLogin');
    }

    /**
     * Logs in a pre-created Guest user.
     * This method bypasses the standard email/password attempt.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function guestLogin()
    {
        // 1. Find the predefined Guest user (created in the seeder)
        $guest = User::where('role', 'guest')->first();

        if (!$guest) {
            // Failsafe: Redirect if the guest account doesn't exist.
            return redirect('/login')->with('error', 'Guest service is temporarily unavailable.');
        }

        // 2. Log in the guest using the Auth facade
        Auth::login($guest);

        // 3. Regenerate the session ID for security
        // This is crucial to prevent session fixation attacks.
        session()->regenerate();

        // 4. Redirect the logged-in guest to the default dashboard
        return redirect($this->redirectTo)->with('success', 'Logged in as Guest.');
    }
}