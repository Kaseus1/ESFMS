<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminReservationController extends Controller
{
    /**
     * Display a listing of all reservations
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'facility'])
            ->whereHas('facility');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by facility
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        // Filter by payment status
        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment === 'paid');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('start_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('start_time', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $reservations = $query->orderBy('start_time', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Get stats
        $stats = [
            'total' => Reservation::whereHas('facility')->count(),
            'pending' => Reservation::where('status', 'pending')->whereHas('facility')->count(),
            'approved' => Reservation::where('status', 'approved')->whereHas('facility')->count(),
            'rejected' => Reservation::where('status', 'rejected')->whereHas('facility')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->whereHas('facility')->count(),
            'total_revenue' => Reservation::where('payment_status', true)
                ->whereIn('status', ['approved', 'pending'])
                ->sum('cost'),
        ];

        // Get facilities for filter dropdown
        $facilities = Facility::orderBy('name')->get();

        return view('admin.reservations.index', compact('reservations', 'stats', 'facilities'));
    }

    /**
     * Display the specified reservation
     */
    public function show(Reservation $reservation)
    {
        $reservation->load(['user', 'facility']);

        // Load wallet transactions for this reservation
        $transactions = $reservation->user->walletTransactions()
            ->where('reservation_id', $reservation->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reservations.show', compact('reservation', 'transactions'));
    }

    /**
     * Approve a reservation
     */
    public function approve(Request $request, Reservation $reservation)
    {
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Only pending reservations can be approved.');
        }

        $reservation->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Reservation approved successfully.');
    }

    /**
     * Reject a reservation and refund the user
     */
    public function reject(Request $request, Reservation $reservation)
    {
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Only pending reservations can be rejected.');
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Refund the user if they paid
            if ($reservation->payment_status && $reservation->cost > 0) {
                $reservation->user->refundCredits(
                    amount: $reservation->cost,
                    reservationId: $reservation->id,
                    reference: 'REJ-' . $reservation->id,
                    description: 'Refund for rejected reservation - ' . ($reservation->facility->name ?? 'Facility')
                );
            }

            $reservation->update([
                'status' => 'rejected',
                'admin_notes' => $request->admin_notes,
                'payment_status' => false,
            ]);

            DB::commit();

            $message = 'Reservation rejected.';
            if ($reservation->cost > 0) {
                $message .= ' ₱' . number_format($reservation->cost, 2) . ' has been refunded to ' . $reservation->user->name . "'s wallet.";
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject reservation: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a reservation and refund the user
     */
    public function cancel(Request $request, Reservation $reservation)
    {
        if ($reservation->status === 'cancelled') {
            return back()->with('error', 'This reservation is already cancelled.');
        }

        if ($reservation->status === 'rejected') {
            return back()->with('error', 'Rejected reservations cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $refundAmount = 0;

            // Refund the user if they paid
            if ($reservation->payment_status && $reservation->cost > 0) {
                $refundAmount = $reservation->cost;

                $reservation->user->refundCredits(
                    amount: $refundAmount,
                    reservationId: $reservation->id,
                    reference: 'CANCEL-' . $reservation->id,
                    description: 'Admin cancelled reservation - ' . ($reservation->facility->name ?? 'Facility')
                );
            }

            $reservation->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason ?? 'Cancelled by admin',
                'cancelled_at' => now(),
                'payment_status' => false,
            ]);

            DB::commit();

            $message = 'Reservation cancelled successfully.';
            if ($refundAmount > 0) {
                $message .= ' ₱' . number_format($refundAmount, 2) . ' has been refunded to ' . $reservation->user->name . "'s wallet.";
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel reservation: ' . $e->getMessage());
        }
    }

    /**
     * Delete a reservation permanently
     */
    public function destroy(Reservation $reservation)
    {
        if ($reservation->status !== 'cancelled') {
            return back()->with('error', 'Only cancelled reservations can be deleted.');
        }

        // Check if the 7-day grace period has passed
        if (!$reservation->canBeDeleted()) {
            return back()->with('error', 'Reservations can only be permanently deleted 7 days after cancellation.');
        }

        $reservation->delete();

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation deleted permanently.');
    }

    /**
     * Get today's reservations (for dashboard)
     */
    public function today()
    {
        $reservations = Reservation::with(['user', 'facility'])
            ->whereHas('facility')
            ->whereDate('start_time', today())
            ->where('status', 'approved')
            ->orderBy('start_time')
            ->get();

        return view('admin.reservations.today', compact('reservations'));
    }

    /**
     * Get upcoming reservations
     */
    public function upcoming()
    {
        $reservations = Reservation::with(['user', 'facility'])
            ->whereHas('facility')
            ->where('start_time', '>', now())
            ->where('status', 'approved')
            ->orderBy('start_time')
            ->paginate(20);

        return view('admin.reservations.upcoming', compact('reservations'));
    }

    /**
     * Bulk approve reservations
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'reservation_ids' => 'required|array|min:1',
            'reservation_ids.*' => 'exists:reservations,id',
        ]);

        $count = Reservation::whereIn('id', $request->reservation_ids)
            ->where('status', 'pending')
            ->update(['status' => 'approved']);

        return back()->with('success', "{$count} reservation(s) approved successfully.");
    }

    /**
     * Bulk reject reservations with refund
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'reservation_ids' => 'required|array|min:1',
            'reservation_ids.*' => 'exists:reservations,id',
        ]);

        DB::beginTransaction();

        try {
            $reservations = Reservation::with('user')
                ->whereIn('id', $request->reservation_ids)
                ->where('status', 'pending')
                ->get();

            $count = 0;
            $totalRefunded = 0;

            foreach ($reservations as $reservation) {
                // Refund if paid
                if ($reservation->payment_status && $reservation->cost > 0) {
                    $reservation->user->refundCredits(
                        amount: $reservation->cost,
                        reservationId: $reservation->id,
                        reference: 'BULK-REJ-' . $reservation->id,
                        description: 'Bulk reject refund - ' . ($reservation->facility->name ?? 'Facility')
                    );
                    $totalRefunded += $reservation->cost;
                }

                $reservation->update([
                    'status' => 'rejected',
                    'payment_status' => false,
                ]);

                $count++;
            }

            DB::commit();

            $message = "{$count} reservation(s) rejected.";
            if ($totalRefunded > 0) {
                $message .= " Total refunded: ₱" . number_format($totalRefunded, 2);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject reservations: ' . $e->getMessage());
        }
    }
}
