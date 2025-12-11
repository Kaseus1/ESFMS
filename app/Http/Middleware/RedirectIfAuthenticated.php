<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // Redirect based on user role and status
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                if ($user->role === 'guest') {
                    // Check guest approval status
                    if ($user->status === 'approved') {
                        return redirect()->route('dashboard');
                    } elseif (in_array($user->status, ['pending', 'rejected'])) {
                        return redirect()->route('guest.status');
                    }
                }

                // Default redirect
                return redirect(RouteServiceProvider::HOME ?? '/dashboard');
            }
        }

        return $next($request);
    }
}