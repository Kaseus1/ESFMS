<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Facility;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Statistics
        $totalReservations = Reservation::where('user_id', $user->id)->count();
        $pendingReservations = Reservation::where('user_id', $user->id)->where('status', 'pending')->count();
        $approvedReservations = Reservation::where('user_id', $user->id)->where('status', 'approved')->count();
        $rejectedReservations = Reservation::where('user_id', $user->id)->where('status', 'rejected')->count();
        $totalFacilities = Facility::count();

        // 2. Upcoming Reservations (Next 5)
        // We filter out reservations where facility might be hard-deleted to prevent view crashes
        $upcomingReservations = Reservation::with('facility')
            ->where('user_id', $user->id)
            ->where('start_time', '>=', now())
            ->where('status', '!=', 'cancelled')
            ->whereHas('facility') // Only get if facility exists
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();

        // 3. Recent Activity (Last 5)
        $recentReservations = Reservation::with('facility')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 4. Calendar Events
        $calendarReservations = Reservation::with('facility')
            ->where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->get();

        return view('student.dashboard', compact(
            'totalReservations',
            'pendingReservations',
            'approvedReservations',
            'rejectedReservations',
            'totalFacilities',
            'upcomingReservations',
            'recentReservations',
            'calendarReservations'
        ));
    }
}