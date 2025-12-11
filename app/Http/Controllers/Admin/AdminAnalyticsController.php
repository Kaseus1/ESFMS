<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // --- Date range filter ---
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // --- Overview Stats ---
        $totalReservations = Reservation::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalFacilities = Facility::count();
        $totalUsers = User::whereIn('role', ['faculty','student','guest'])->count();
        $activeUsers = User::whereIn('role', ['faculty','student','guest'])
            ->where('status','approved')
            ->count();

        // --- Reservation Status ---
        $reservationStats = Reservation::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total','status')
            ->toArray();

        // Fill missing statuses with 0
        $statuses = ['pending','approved','rejected','cancelled'];
        foreach ($statuses as $status) {
            if (!isset($reservationStats[$status])) {
                $reservationStats[$status] = 0;
            }
        }

        // --- Monthly Reservations (Last 12 months) ---
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyLabels[] = $month->format('M Y');
            $monthlyData[] = Reservation::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // --- Top 5 Facilities ---
        $topFacilities = Facility::withCount(['reservations' => function($q) use ($startDate,$endDate) {
            $q->whereBetween('created_at', [$startDate,$endDate]);
        }])->orderBy('reservations_count','desc')
          ->limit(5)
          ->get();

        // --- Reservations by Role ---
        $reservationsByRole = DB::table('reservations')
            ->join('users','reservations.user_id','=','users.id')
            ->select('users.role', DB::raw('count(*) as total'))
            ->whereBetween('reservations.created_at', [$startDate,$endDate])
            ->groupBy('users.role')
            ->get();

        // Fill roles with 0 if missing
        $allRoles = ['faculty','student','guest'];
        $roleData = collect($allRoles)->map(function($role) use ($reservationsByRole) {
            $found = $reservationsByRole->firstWhere('role', $role);
            return (object)[
                'role' => $role,
                'total' => $found ? $found->total : 0
            ];
        });

        // --- Weekly Trend (Last 7 days) ---
        $weeklyLabels = [];
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyLabels[] = $date->format('D');
            $weeklyData[] = Reservation::whereDate('created_at', $date->toDateString())->count();
        }

        // Debug logging
        Log::info('Analytics Debug', [
            'totalReservations' => $totalReservations,
            'totalFacilities' => $totalFacilities,
            'monthlyData' => $monthlyData,
            'reservationStats' => $reservationStats,
            'topFacilitiesCount' => $topFacilities->count()
        ]);

        // Check if view exists
        if (!view()->exists('admin.analytics.index')) {
            dd('View does not exist at: resources/views/admin/analytics/index.blade.php');
        }

        return view('admin.analytics.index', compact(
            'totalReservations',
            'totalFacilities',
            'totalUsers',
            'activeUsers',
            'reservationStats',
            'monthlyLabels',
            'monthlyData',
            'topFacilities',
            'reservationsByRole',
            'weeklyLabels',
            'weeklyData',
            'startDate',
            'endDate'
        ))->with([
            // Add these explicitly in case compact() isn't working
            'reservationsByRole' => $roleData
        ]);
    }
}