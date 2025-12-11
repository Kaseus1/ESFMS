<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Access\AuthorizationException;

class VerifyEmailController extends Controller
{
    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('auth')->except(['verify']);
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Invokable handler so routes that reference the controller class directly work.
     * If an {id} route parameter is present it will attempt verification,
     * otherwise it will show the verification prompt view.
     */
    public function __invoke(Request $request, $id = null)
    {
        // If the route provided an id/hash, forward to verify
        if ($id !== null) {
            return $this->verify($request, $id);
        }

        // If no id provided, show the verification prompt (uses existing view/controller patterns)
        // If you have a custom view, adjust the view name accordingly.
        return view('auth.verify-email');
    }

    /**
     * Handle email verification link: /email/verify/{id}/{hash}
     */
    public function verify(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // validate signature/hash (compatible with Laravel's default signed URL)
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect($this->redirectPath())->with('verified', true);
    }

    /**
     * Resend verification email to the authenticated user.
     */
    public function resend(Request $request)
    {
        $user = $request->user();
        if ($user && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('status', 'verification-link-sent');
    }

    protected function redirectPath()
    {
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}
