<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\GuestRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Dashboard - OVERVIEW ONLY (Lightweight & Fast)
     * No heavy CRUD operations - just summaries
     */
    public function index()
    {
        // ==========================================
        // LIGHTWEIGHT STATS - Fast Loading
        // ==========================================
        $stats = [
            'total_facilities' => Facility::count(),
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'approved_reservations' => Reservation::where('status', 'approved')->count(),
            'rejected_reservations' => Reservation::where('status', 'rejected')->count(),
            'pending_guests' => User::where('role', 'guest')->where('status', 'pending')->count(),
            'approved_today' => Reservation::where('status', 'approved')
                ->whereDate('updated_at', today())
                ->count(),
            'this_week' => Reservation::whereBetween('start_time', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
        ];

        // ==========================================
        // RECENT ACTIVITY - Top 10 Only
        // ==========================================
        $recentReservations = Reservation::with(['facility', 'user'])
            ->latest()
            ->take(10)
            ->get();

        // ==========================================
        // UPCOMING RESERVATIONS - Next 5
        // ==========================================
        $upcomingReservations = Reservation::with(['facility', 'user'])
            ->where('status', 'approved')
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // ==========================================
        // CHART DATA - Last 12 Months
        // ==========================================
        $labels = [];
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = Reservation::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        $facilities = Facility::all();

        return view('admin.dashboard', compact(
            'stats',
            'recentReservations',
            'upcomingReservations',
            'labels',
            'data',
            'facilities'
        ));
    }

    /**
     * Calendar events JSON endpoint
     */
    public function calendarEvents()
{
    // Load ALL reservations with relationships (including soft-deleted facilities)
    $reservations = Reservation::with(['user', 'facility' => function($query) {
        $query->withTrashed(); // Include soft-deleted facilities
    }])->get();

    $events = $reservations->map(function ($reservation) {
        $statusColors = [
            'pending' => '#F59E0B',
            'approved' => '#10B981',
            'rejected' => '#EF4444',
            'cancelled' => '#6B7280'
        ];

        return [
            'id' => $reservation->id,
            'title' => $reservation->event_name ?? 'No Title',
            'start' => $reservation->start_time,
            'end' => $reservation->end_time,
            'backgroundColor' => $statusColors[$reservation->status] ?? '#6B7280',
            'borderColor' => 'transparent',
            'extendedProps' => [
                'status' => $reservation->status,
                'user' => $reservation->user->name ?? 'Unknown User',
                'facility' => $reservation->facility->name ?? 'Deleted Facility',
                'participants' => $reservation->participants,
                'notes' => $reservation->notes,
                'event_name' => $reservation->event_name,
            ],
        ];
    });

    return response()->json($events);
}
}