<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\GuestRegistrationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class GuestRegistrationController extends Controller
{
    /**
     * Show the guest registration form
     */
    public function show()
    {
        return view('auth.guest-register');
    }

    /**
     * Handle guest registration
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact_number' => ['required', 'string', 'max:20'],
            'purpose' => ['required', 'string', 'max:500'],
            'organization' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ]);

        // Create guest user with pending status
        $guest = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'],
            'purpose' => $validated['purpose'],
            'organization' => $validated['organization'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'guest',
            'status' => 'pending',
        ]);

        // Send registration notification email
        try {
            Mail::to($guest->email)->send(new GuestRegistrationMail($guest));
            Log::info('Guest registration email sent successfully to: ' . $guest->email);
        } catch (\Exception $e) {
            Log::warning('Guest registration email failed for ' . $guest->email . ': ' . $e->getMessage());
        }

        // Redirect to guest login with success message
        return redirect()->route('guest.login')
            ->with('success', 'Registration successful! Your account is pending admin approval. You will receive an email once approved.');
    }
}