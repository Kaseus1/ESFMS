<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the authenticated user's intended destination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): Response
    {
        $user = auth()->user();

        switch ($user->role) {
            case 'admin':
                return redirect()->intended('/admin/dashboard');
            case 'faculty':
                return redirect()->intended('/faculty/dashboard');
            case 'staff':
                return redirect()->intended('/staff/dashboard');
            default: // student or any other role
                return redirect()->intended('/student/dashboard');
        }
    }
}

