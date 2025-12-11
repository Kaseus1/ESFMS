<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGuestApproval
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // If user is a guest and not approved, redirect to status page
        if ($user->role === 'guest' && $user->status !== 'approved') {
            // Allow access to the status page itself and logout route
            if (!$request->routeIs('guest.status') && !$request->routeIs('logout')) {
                return redirect()->route('guest.status');
            }
        }

        return $next($request);
    }
}