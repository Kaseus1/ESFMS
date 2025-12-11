<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Facility;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display Admin Dashboard.
     */
    public function index()
    {
        $reservations = Reservation::with(['facility', 'user'])
            ->latest()
            ->paginate(8);

        $facilities = Facility::all();

        // Stats Summary
        $stats = [
            'total_facilities' => Facility::count(),
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'approved_reservations' => Reservation::where('status', 'approved')->count(),
        ];

        // Next upcoming reservation
        $nextReservation = Reservation::with(['facility', 'user'])
            ->where('start_time', '>', now())
            ->orderBy('start_time', 'asc')
            ->first();

        // Monthly analytics
        $monthlyData = Reservation::selectRaw('MONTH(start_time) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $labels = [];
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = date("F", mktime(0, 0, 0, $i, 1));
            $data[] = $monthlyData[$i] ?? 0;
        }

        return view('dashboards.admin', compact(
            'reservations', 'facilities', 'stats', 'nextReservation', 'labels', 'data'
        ));
    }

    /**
     * Calendar events for all roles.
     * Admins see all reservations, while students/faculty/staff see their own.
     */
    public function calendarEvents()
    {
        $user = Auth::user();

        // ✅ Admin sees all reservations, others see only their own
        if ($user->role === 'admin') {
            $reservations = Reservation::with(['facility', 'user'])->get();
        } else {
            $reservations = Reservation::with(['facility', 'user'])
                ->where('user_id', $user->id)
                ->get();
        }

        $events = $reservations->map(function ($r) {
            $color = match ($r->status) {
                'approved' => '#10B981', // green
                'pending'  => '#F59E0B', // yellow
                'rejected' => '#EF4444', // red
                default    => '#6B7280', // gray
            };

            return [
                'id' => $r->id,
                'title' => $r->facility->name . ' (' . ucfirst($r->status) . ')',
                'start' => Carbon::parse($r->start_time)->toIso8601String(),
                'end' => Carbon::parse($r->end_time)->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#fff',
                'status' => $r->status,
                'user' => $r->user->name ?? 'Unknown',
                'facility' => $r->facility->name ?? 'Unknown',
                'resourceId' => $r->facility->id ?? null,
            ];
        });

        return response()->json($events);
    }

    /**
     * Return monthly reservation data for analytics.
     */
    public function monthlyReservationsData()
    {
        $data = Reservation::selectRaw('MONTH(start_time) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return response()->json($data);
    }
}
