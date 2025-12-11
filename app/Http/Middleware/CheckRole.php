<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  The list of allowed roles (e.g., 'admin', 'student', 'guest')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Check if user is authenticated. 
        // Note: The 'auth' middleware should typically run before this.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // 2. Check if the user's role is in the list of allowed roles
        // We use in_array() to allow multiple roles (e.g., role:admin,student,guest)
        if (!in_array($user->role, $roles)) {
            // User is logged in but does not have the required role for this page.
            // Redirect them to their own dashboard.
            return $this->redirectToDashboard($user->role);
        }

        return $next($request);
    }

    /**
     * Redirect user to their appropriate dashboard based on their role.
     *
     * @param string $userRole
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToDashboard($userRole)
    {
        switch ($userRole) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'student':
                return redirect()->route('student.dashboard');
            case 'faculty':
            case 'staff':
                // Assuming these roles have the same redirect for unauthorized attempts
                return redirect()->route('student.dashboard')
                    ->with('info', 'Access Denied. Redirected to your primary area.');
            case 'guest':
                // Guests are usually directed to the main public/home page
                return redirect()->route('home') 
                    ->with('warning', 'You do not have permission to access that feature.');
            default:
                // Fallback for unhandled roles
                return redirect('/')->withErrors('Unauthorized access attempt.');
        }
    }
}