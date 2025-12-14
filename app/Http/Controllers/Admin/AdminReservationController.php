<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // Load wallet transactions for this reservation if user has that method
        $transactions = null;
        if (method_exists($reservation->user, 'walletTransactions')) {
            $transactions = $reservation->user->walletTransactions()
                ->where('reservation_id', $reservation->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.reservations.show', compact('reservation', 'transactions'));
    }

    /**
     * EDIT METHOD
     */
    public function edit(Reservation $reservation)
    {
        $reservation->load(['user', 'facility']);
        $facilities = Facility::orderBy('name')->get();
        
        return view('admin.reservations.edit', compact('reservation', 'facilities'));
    }

    /**
     * UPDATE METHOD
     */
    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'facility_id' => 'required|exists:facilities,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'participants' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'purpose' => 'nullable|string',
            'setup_requirements' => 'nullable|string',
            'equipment_needed' => 'nullable|string',
        ]);

        $reservation->update($request->all());

        return redirect()->route('admin.reservations.show', $reservation)
            ->with('success', 'Reservation updated successfully.');
    }

    /**
     * Approve a reservation - DEBUG VERSION
     */
    public function approve(Request $request, $id)
    {
        Log::info('=== DEBUG: APPROVAL PROCESS STARTED ===', [
            'reservation_id' => $id,
            'request_data' => $request->all(),
            'user_id' => auth()->id()
        ]);

        try {
            // Step 1: Find the reservation
            Log::info('Step 1: Finding reservation...');
            $reservation = Reservation::find($id);

            if (!$reservation) {
                Log::error('Step 1 FAILED: Reservation not found', ['reservation_id' => $id]);
                return back()->with('error', 'Reservation not found.');
            }

            Log::info('Step 1 SUCCESS: Reservation found', [
                'reservation_id' => $reservation->id,
                'current_status' => $reservation->status,
                'status_type' => gettype($reservation->status),
                'is_pending' => $reservation->status === 'pending'
            ]);

            // Step 2: Refresh from database
            Log::info('Step 2: Refreshing from database...');
            $reservation->refresh();
            Log::info('Step 2 SUCCESS: Refreshed from database', ['current_status' => $reservation->status]);

            // Step 3: Check status
            Log::info('Step 3: Checking reservation status...');
            $status = $reservation->status ? strtolower(trim($reservation->status)) : null;
            Log::info('Step 3: Status check details', [
                'original_status' => $reservation->status,
                'processed_status' => $status,
                'is_pending' => $status === 'pending'
            ]);

            if ($status !== 'pending') {
                Log::warning('Step 3 FAILED: Status is not pending', [
                    'current_status' => $reservation->status,
                    'expected' => 'pending',
                    'actual' => $status
                ]);
                return back()->with('error', 
                    "Only pending reservations can be approved. Current status: '{$reservation->status}'");
            }

            Log::info('Step 3 SUCCESS: Status is pending');

            // Step 4: Prepare update data
            Log::info('Step 4: Preparing update data...');
            $adminNotes = $request->admin_notes ? trim($request->admin_notes) : null;
            $updateData = [
                'status' => 'approved',
                'admin_notes' => $adminNotes,
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ];

            Log::info('Step 4 SUCCESS: Update data prepared', [
                'update_data' => $updateData,
                'current_user_id' => auth()->id()
            ]);

            // Step 5: Perform the update
            Log::info('Step 5: Starting database update...');
            
            $result = $reservation->update($updateData);
            
            Log::info('Step 5: Update result', [
                'update_result' => $result,
                'rows_affected' => $reservation->wasChanged(),
                'new_status' => $reservation->status,
                'approved_at' => $reservation->approved_at,
                'approved_by' => $reservation->approved_by
            ]);

            // Step 6: Verify the update worked
            Log::info('Step 6: Verifying update...');
            $reservation->refresh();
            
            if ($reservation->status !== 'approved') {
                Log::error('Step 6 FAILED: Update verification failed', [
                    'expected_status' => 'approved',
                    'actual_status' => $reservation->status
                ]);
                return back()->with('error', 'Failed to verify reservation update. Please try again.');
            }

            Log::info('Step 6 SUCCESS: Update verified');

            Log::info('=== APPROVAL COMPLETED SUCCESSFULLY ===', [
                'reservation_id' => $reservation->id,
                'final_status' => $reservation->status,
                'admin_notes' => $adminNotes
            ]);

            return back()->with('success', 'Reservation approved successfully.');

        } catch (\Exception $e) {
            Log::error('=== APPROVAL FAILED WITH EXCEPTION ===', [
                'reservation_id' => $id,
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'Failed to approve reservation: ' . $e->getMessage());
        }
    }

    /**
     * Reject a reservation - DEBUG VERSION
     */
    public function reject(Request $request, $id)
    {
        Log::info('=== DEBUG: REJECT PROCESS STARTED ===', [
            'reservation_id' => $id,
            'request_data' => $request->all()
        ]);

        try {
            $request->validate([
                'admin_notes' => 'nullable|string|max:500',
            ]);

            // Find the reservation
            $reservation = Reservation::find($id);
            
            if (!$reservation) {
                return back()->with('error', 'Reservation not found.');
            }

            // Force refresh from database to ensure we have the latest status
            $reservation->refresh();
            
            // More flexible status checking
            $status = strtolower(trim($reservation->status));
            
            if ($status !== 'pending') {
                return back()->with('error', "Only pending reservations can be rejected. Current status: '{$reservation->status}'");
            }

            DB::beginTransaction();

            // Refund the user if they paid
            if ($reservation->payment_status && $reservation->cost > 0 && method_exists($reservation->user, 'refundCredits')) {
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

            Log::info('=== REJECT COMPLETED SUCCESSFULLY ===', [
                'reservation_id' => $reservation->id,
                'message' => $message
            ]);

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== REJECT FAILED WITH EXCEPTION ===', [
                'reservation_id' => $id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to reject reservation: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a reservation and refund the user
     */
    public function cancel(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        
        if (!$reservation) {
            return back()->with('error', 'Reservation not found.');
        }

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
            if ($reservation->payment_status && $reservation->cost > 0 && method_exists($reservation->user, 'refundCredits')) {
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
            Log::error('Failed to cancel reservation', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to cancel reservation: ' . $e->getMessage());
        }
    }

    /**
     * Delete a reservation permanently
     */
    public function destroy($id)
    {
        $reservation = Reservation::find($id);
        
        if (!$reservation) {
            return back()->with('error', 'Reservation not found.');
        }

        if ($reservation->status !== 'cancelled') {
            return back()->with('error', 'Only cancelled reservations can be deleted.');
        }

        // Check if the 7-day grace period has passed (if method exists)
        if (method_exists($reservation, 'canBeDeleted') && !$reservation->canBeDeleted()) {
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
                // Refund if paid and method exists
                if ($reservation->payment_status && $reservation->cost > 0 && method_exists($reservation->user, 'refundCredits')) {
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
            Log::error('Failed to bulk reject reservations', [
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to reject reservations: ' . $e->getMessage());
        }
    }
}