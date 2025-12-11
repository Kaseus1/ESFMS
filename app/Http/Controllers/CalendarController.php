<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Reservation;

class CalendarController extends Controller
{
    public function index()
    {
        $facilities = Facility::all();

        // Convert approved reservations into FullCalendar events
        $events = Reservation::where('status', 'approved')
            ->with('facility')
            ->get()
            ->map(function ($res) {
                return [
                    'title' => $res->facility->name . ' - ' . $res->event_name,
                    'start' => $res->start_time,
                    'end'   => $res->end_time,
                    'color' => match ($res->status) {
                        'approved' => '#16a34a',
                        'pending' => '#facc15',
                        'rejected' => '#dc2626',
                        default => '#3b82f6',
                    },
                ];
            });

        return view('calendar.index', compact('facilities', 'events'));
    }
}
