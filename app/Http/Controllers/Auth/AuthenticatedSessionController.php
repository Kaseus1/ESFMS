<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view (generic - used by auth.php routes)
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate login credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->onlyInput('email');
        }

        // Get intended role from hidden field (if exists)
        $intendedRole = $request->input('intended_role');

        // ADMIN LOGIN VERIFICATION
        if ($intendedRole === 'admin') {
            if ($user->role !== 'admin') {
                return back()->withErrors([
                    'email' => 'Access Denied: This account does not have administrator privileges.',
                ])->onlyInput('email');
            }
        }

        // FACULTY LOGIN VERIFICATION (New Addition)
        if ($intendedRole === 'faculty') {
            if ($user->role !== 'faculty') {
                return back()->withErrors([
                    'email' => 'Access Denied: This login page is restricted to Faculty members only.',
                ])->onlyInput('email');
            }
        }
        
        // STUDENT LOGIN VERIFICATION (Optional but consistent)
        if ($intendedRole === 'student') {
             if ($user->role !== 'student') {
                return back()->withErrors([
                    'email' => 'Access Denied: This login page is restricted to Students only.',
                ])->onlyInput('email');
            }
        }

        // GUEST STATUS CHECK (only for guest users)
        if ($user->role === 'guest') {
            if ($user->status === 'pending') {
                return back()->withErrors([
                    'email' => '⏳ Your guest account is pending approval by an administrator.',
                ])->onlyInput('email');
            }

            if ($user->status === 'rejected') {
                return back()->withErrors([
                    'email' => '❌ Your guest account request has been rejected.',
                ])->onlyInput('email');
            }
        }

        // Attempt authentication
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Invalid credentials provided.',
            ])->onlyInput('email');
        }

        // Regenerate session for security
        $request->session()->regenerate();

        // Get authenticated user
        $authenticatedUser = Auth::user();

        // Role-based redirection
        return $this->redirectBasedOnRole($authenticatedUser);
    }

    /**
     * Redirect user to appropriate dashboard based on role
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->intended(route('admin.dashboard'));
            
            case 'student':
                return redirect()->intended(route('student.dashboard'));
            
            case 'faculty':
                return redirect()->intended(route('faculty.dashboard'));
            
            case 'staff':
                return redirect()->intended(route('dashboard'));
            
            case 'guest':
                if ($user->status === 'approved') {
                    return redirect()->intended(route('guest.dashboard'));
                }
                return redirect()->route('guest.status');
            
            default:
                return redirect()->intended(route('dashboard'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}