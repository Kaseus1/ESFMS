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
        $status = $request->query('status', 'pending');
        $search = $request->query('search');
        
        $query = User::where('role', 'guest')
            ->with(['approvedBy', 'reservations']);

        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('organization', 'like', "%{$search}%");
            });
        }

        $guests = $query->latest('created_at')->paginate(20);

        // Statistics
        $stats = [
            'total' => User::guests()->count(),
            'pending' => User::guests()->pending()->count(),
            'approved' => User::guests()->approved()->count(),
            'rejected' => User::guests()->rejected()->count(),
            'active_today' => User::guests()->approved()
                ->whereDate('last_login_at', today())->count(),
        ];

        return view('admin.guests.index', compact('guests', 'status', 'stats', 'search'));
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
        Mail::to($guest->email)->send(new GuestApprovedMail($guest));

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
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $guest->update([
            'status' => 'rejected',
            'status_updated_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Send rejection email
        Mail::to($guest->email)->send(new GuestRejectedMail($guest));

        return back()->with('success', "Guest account for {$guest->name} has been rejected.");
    }

    /**
     * Reactivate rejected guest
     */
    public function reactivate(User $guest)
    {
        if ($guest->role !== 'guest' || $guest->status !== 'rejected') {
            return back()->with('error', 'Cannot reactivate this account.');
        }

        $guest->update([
            'status' => 'pending',
            'status_updated_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', "Guest account for {$guest->name} has been moved to pending status.");
    }

    /**
     * Suspend approved guest
     */
    public function suspend(Request $request, User $guest)
    {
        if ($guest->role !== 'guest' || $guest->status !== 'approved') {
            return back()->with('error', 'Cannot suspend this account.');
        }

        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $guest->update([
            'status' => 'rejected',
            'status_updated_at' => now(),
            'rejection_reason' => 'Account suspended by admin',
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        return back()->with('success', "Guest account for {$guest->name} has been suspended.");
    }

    /**
     * Update admin notes
     */
    public function updateNotes(Request $request, User $guest)
    {
        if ($guest->role !== 'guest') {
            return back()->with('error', 'Invalid user type.');
        }

        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $guest->update([
            'admin_notes' => $validated['admin_notes'],
        ]);

        return back()->with('success', 'Admin notes updated successfully.');
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
        $validated = $request->validate([
            'guest_ids' => ['required', 'array'],
            'guest_ids.*' => ['exists:users,id'],
        ]);

        $approved = 0;
        foreach ($validated['guest_ids'] as $guestId) {
            $guest = User::find($guestId);
            if ($guest && $guest->role === 'guest' && $guest->status === 'pending') {
                $guest->update([
                    'status' => 'approved',
                    'status_updated_at' => now(),
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);
                Mail::to($guest->email)->send(new GuestApprovedMail($guest));
                $approved++;
            }
        }

        return back()->with('success', "Successfully approved {$approved} guest account(s).");
    }

    /**
     * Bulk delete guests
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'guest_ids' => ['required', 'array'],
            'guest_ids.*' => ['exists:users,id'],
        ]);

        $deleted = 0;
        foreach ($validated['guest_ids'] as $guestId) {
            $guest = User::find($guestId);
            if ($guest && $guest->role === 'guest' && !$guest->reservations()->exists()) {
                $guest->delete();
                $deleted++;
            }
        }

        return back()->with('success', "Successfully deleted {$deleted} guest account(s).");
    }

    /**
     * Export guests to CSV
     */
    public function export(Request $request)
    {
        $status = $request->query('status', 'all');
        
        $query = User::where('role', 'guest');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $guests = $query->with('approvedBy')->get();
        
        $filename = 'guests_' . $status . '_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($guests) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Contact', 'Organization', 
                'Purpose', 'Status', 'Registered', 'Approved By', 'Approved At'
            ]);
            
            // Data
            foreach ($guests as $guest) {
                fputcsv($file, [
                    $guest->id,
                    $guest->name,
                    $guest->email,
                    $guest->contact_number ?? '',
                    $guest->organization ?? '',
                    $guest->purpose ?? '',
                    ucfirst($guest->status),
                    $guest->created_at->format('Y-m-d H:i'),
                    $guest->approvedBy->name ?? '',
                    $guest->approved_at ? $guest->approved_at->format('Y-m-d H:i') : '',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}