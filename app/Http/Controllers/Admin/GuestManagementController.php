<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuestApprovedMail;
use App\Mail\GuestRejectedMail;

class GuestManagementController extends Controller
{
    /**
     * Display guest management dashboard
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'guest')->with(['approvedBy']);

        // 1. Search Logic
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('organization', 'like', "%{$search}%");
            });
        }

        // 2. Status Filter Logic
        // We use input() instead of query() to properly handle empty strings
        $status = $request->input('status');

        // Logic: 
        // - If URL has no ?status, default to 'pending'
        // - If URL has ?status= (empty/All), show all (don't filter)
        // - If URL has ?status=approved, filter by approved
        if (is_null($status)) {
            $status = 'pending';
        }

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        // 3. Execute Query with Pagination
        $guests = $query->latest()->paginate(10)->withQueryString();

        // 4. Calculate Stats for Dashboard Cards
        $stats = [
            'total' => User::where('role', 'guest')->count(),
            'pending' => User::where('role', 'guest')->where('status', 'pending')->count(),
            'approved' => User::where('role', 'guest')->where('status', 'approved')->count(),
            'rejected' => User::where('role', 'guest')->where('status', 'rejected')->count(),
        ];

        return view('admin.guests.index', compact('guests', 'stats'));
    }

    /**
     * Show detailed guest information
     */
    public function show(User $guest)
    {
        if ($guest->role !== 'guest') {
            abort(404);
        }

        $guest->load(['approvedBy', 'reservations' => function($query) {
            $query->latest()->limit(10);
        }]);

        // Calculate reservation stats for this specific guest
        $reservationStats = [
            'total' => $guest->reservations()->count(),
            'pending' => $guest->reservations()->where('status', 'pending')->count(),
            'approved' => $guest->reservations()->where('status', 'approved')->count(),
            'completed' => $guest->reservations()->where('status', 'completed')->count(),
            'cancelled' => $guest->reservations()->where('status', 'cancelled')->count(),
        ];

        return view('admin.guests.show', compact('guest', 'reservationStats'));
    }

    /**
     * Show edit guest form
     */
    public function edit(User $guest)
    {
        if ($guest->role !== 'guest') {
            abort(404);
        }

        return view('admin.guests.edit', compact('guest'));
    }

    /**
     * Update guest information
     */
    public function update(Request $request, User $guest)
    {
        if ($guest->role !== 'guest') {
            return back()->with('error', 'Invalid user type.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $guest->id],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'organization' => ['nullable', 'string', 'max:255'],
            'purpose' => ['nullable', 'string', 'max:1000'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $guest->update($validated);

        return redirect()->route('admin.guests.show', $guest)
            ->with('success', 'Guest information updated successfully.');
    }

    /**
     * Approve guest account
     */
    public function approve(User $guest)
    {
        if ($guest->role !== 'guest') {
            return back()->with('error', 'Invalid user type.');
        }

        if ($guest->status === 'approved') {
            return back()->with('info', 'Guest is already approved.');
        }

        $guest->update([
            'status' => 'approved',
            'status_updated_at' => now(),
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Send approval email
        try {
            Mail::to($guest->email)->send(new GuestApprovedMail($guest));
        } catch (\Exception $e) {
            // Log error but continue
        }

        return back()->with('success', "Guest account for {$guest->name} has been approved successfully.");
    }

    /**
     * Reject guest account
     */
    public function reject(Request $request, User $guest)
    {
        if ($guest->role !== 'guest') {
            return back()->with('error', 'Invalid user type.');
        }

        $validated = $request->validate([
            // admin_notes is used for rejection reason in view often, but distinct in DB
            'admin_notes' => ['nullable', 'string', 'max:500'], 
        ]);

        $guest->update([
            'status' => 'rejected',
            'status_updated_at' => now(),
            'rejection_reason' => $request->admin_notes, // Using admin_notes as reason if passed
        ]);

        // Send rejection email
        try {
            Mail::to($guest->email)->send(new GuestRejectedMail($guest));
        } catch (\Exception $e) {
            // Log error but continue
        }

        return back()->with('success', "Guest account for {$guest->name} has been rejected.");
    }

    /**
     * Delete guest account
     */
    public function destroy(User $guest)
    {
        if ($guest->role !== 'guest') {
            return back()->with('error', 'Invalid user type.');
        }

        // Check if guest has any reservations
        if ($guest->reservations()->exists()) {
            return back()->with('error', 'Cannot delete guest with existing reservations. Please cancel or complete all reservations first.');
        }

        $guestName = $guest->name;
        $guest->delete();

        return back()->with('success', "Guest account for {$guestName} has been deleted permanently.");
    }

    /**
     * Bulk approve guests
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'guest_ids' => ['required', 'array'],
            'guest_ids.*' => ['exists:users,id'],
        ]);

        $approvedCount = 0;
        
        foreach ($request->guest_ids as $id) {
            $guest = User::find($id);
            if ($guest && $guest->role === 'guest' && $guest->status !== 'approved') {
                $guest->update([
                    'status' => 'approved',
                    'status_updated_at' => now(),
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);
                
                try {
                    Mail::to($guest->email)->send(new GuestApprovedMail($guest));
                } catch (\Exception $e) {}
                
                $approvedCount++;
            }
        }

        return back()->with('success', "Successfully approved {$approvedCount} guest account(s).");
    }

    /**
     * Bulk reject guests
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'guest_ids' => ['required', 'array'],
            'guest_ids.*' => ['exists:users,id'],
        ]);

        $rejectedCount = 0;

        foreach ($request->guest_ids as $id) {
            $guest = User::find($id);
            if ($guest && $guest->role === 'guest' && $guest->status !== 'rejected') {
                $guest->update([
                    'status' => 'rejected',
                    'status_updated_at' => now(),
                ]);
                
                try {
                    Mail::to($guest->email)->send(new GuestRejectedMail($guest));
                } catch (\Exception $e) {}

                $rejectedCount++;
            }
        }

        return back()->with('success', "Successfully rejected {$rejectedCount} guest account(s).");
    }
}