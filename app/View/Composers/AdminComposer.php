<?php

namespace App\View\Composers;

use App\Models\Reservation;
use App\Models\Facility;
use Illuminate\View\View;

class AdminComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Statistics
        $stats = [
            'total_facilities' => Facility::count(),
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'approved_reservations' => Reservation::where('status', 'approved')->count(),
            'rejected_reservations' => Reservation::where('status', 'rejected')->count(),
            'this_week' => Reservation::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'approved_today' => Reservation::where('status', 'approved')->whereDate('created_at', today())->count(),
        ];

        // Recent Reservations
        $recentReservations = Reservation::with(['facility', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Analytics Chart Data
        $labels = [];
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = Reservation::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // Share with all views
        $view->with([
            'stats' => $stats,
            'recentReservations' => $recentReservations,
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}