<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FacultyDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get statistics
        $totalReservations = Reservation::where('user_id', $user->id)
            ->whereHas('facility')
            ->count();

        $approvedReservations = Reservation::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereHas('facility')
            ->count();

        $pendingReservations = Reservation::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereHas('facility')
            ->count();

        $rejectedReservations = Reservation::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->whereHas('facility')
            ->count();

        $totalFacilities = Facility::count();

        // Upcoming reservations
        $upcomingReservations = Reservation::with('facility')
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'pending'])
            ->where('start_time', '>', now())
            ->whereHas('facility')
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();

        // Recent reservations
        $recentReservations = Reservation::with('facility')
            ->where('user_id', $user->id)
            ->whereHas('facility')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Calendar reservations (for FullCalendar)
        $calendarReservations = Reservation::with('facility')
            ->where('user_id', $user->id)
            ->where('start_time', '>=', now()->startOfMonth())
            ->where('start_time', '<=', now()->addMonths(3)->endOfMonth())
            ->whereHas('facility')
            ->get();

        return view('faculty.dashboard', compact(
            'totalReservations',
            'approvedReservations',
            'pendingReservations',
            'rejectedReservations',
            'upcomingReservations',
            'recentReservations',
            'calendarReservations',
            'totalFacilities'
        ));
    }
}