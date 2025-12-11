<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Facility;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationManagementController extends Controller
{
    /**
     * Display full reservations management page
     * This handles ALL the heavy lifting for CRUD operations
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['facility', 'user']);

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('facility', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Facility filter
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('start_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('start_time', '<=', $request->date_to);
        }

        // Pagination
        $reservations = $query->latest()->paginate(20);

        // Stats for filter tabs
        $stats = [
            'total' => Reservation::count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'approved' => Reservation::where('status', 'approved')->count(),
            'rejected' => Reservation::where('status', 'rejected')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
            'this_week' => Reservation::whereBetween('start_time', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
        ];

        $facilities = Facility::all();

        return view('admin.reservations.index', compact(
            'reservations',
            'stats',
            'facilities'
        ));
    }

    /**
     * Display calendar/timeline view
     */
    public function calendar()
    {
        $stats = [
            'today' => Reservation::whereDate('start_time', today())->count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'approved' => Reservation::where('status', 'approved')->count(),
            'rejected' => Reservation::where('status', 'rejected')->count(),
            'this_week' => Reservation::whereBetween('start_time', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
        ];

        $facilities = Facility::all();

        return view('admin.reservations.calendar', compact('stats', 'facilities'));
    }

    /**
     * Bulk approve reservations
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:reservations,id'
        ]);

        $count = Reservation::whereIn('id', $request->ids)
            ->where('status', 'pending')
            ->update(['status' => 'approved', 'updated_at' => now()]);

        return redirect()->back()->with('success', "{$count} reservation(s) approved successfully!");
    }

    /**
     * Bulk reject reservations
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:reservations,id',
            'rejection_reason' => 'required|string|max:500'
        ]);

        $count = Reservation::whereIn('id', $request->ids)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', "{$count} reservation(s) rejected successfully!");
    }

    /**
     * Bulk delete reservations
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:reservations,id'
        ]);

        $count = Reservation::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', "{$count} reservation(s) deleted successfully!");
    }
}