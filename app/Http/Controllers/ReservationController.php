<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\Reservation;
use App\Models\Notification; // <--- ADDED for notifications
use App\Models\User;         // <--- ADDED to find admins
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Store a newly created reservation in storage.
     * This is where the Notification trigger belongs.
     */
    public function store(Request $request)
    {
        // 1. Validate the Request
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'event_name'  => 'required|string|max:255',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
            'participants'=> 'nullable|integer',
            'notes'       => 'nullable|string',
            // Add any recurring fields if necessary
        ]);

        // 2. Create the Reservation
        $reservation = new Reservation($validated);
        $reservation->user_id = auth()->id();
        $reservation->status = 'pending'; // Default status
        $reservation->save();

        // 3. TRIGGER NOTIFICATION (The Fix)
        // Find all users with the 'admin' role
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type'    => 'reservation',
                'title'   => 'New Reservation Request',
                'message' => auth()->user()->name . ' requested to book ' . $request->event_name,
                // Ensure you have a route named 'admin.reservations.show' or change to 'admin.reservations.index'
                'link'    => route('admin.reservations.show', $reservation->id), 
                'icon'    => 'fa-calendar-plus',
                'color'   => 'yellow',
                'is_read' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Reservation requested successfully!');
    }

    // ... [Keep your other methods like index, update, delete here] ...

    /**
     * Export reservations to Excel
     */
    public function exportReservations()
    {
        $fileName = 'reservations_' . now()->format('Y-m-d_His') . '.xlsx';

        // Use Laravel's streamDownload to handle the response stream
        return response()->streamDownload(function () {
            // Create the writer explicitly writing to the output stream
            $writer = SimpleExcelWriter::create('php://output', 'xlsx');

            Reservation::with(['user', 'facility'])->chunk(500, function ($chunk) use ($writer) {
                foreach ($chunk as $r) {
                    $startTime = $r->start_time ? Carbon::parse($r->start_time)->format('M d, Y h:i A') : 'N/A';
                    $endTime   = $r->end_time ? Carbon::parse($r->end_time)->format('M d, Y h:i A') : 'N/A';

                    $writer->addRow([
                        'ID'         => $r->id,
                        'User'       => $r->user->name ?? 'Unknown',
                        'Facility'   => $r->facility->name ?? 'Unknown',
                        'Start Time' => $startTime,
                        'End Time'   => $endTime,
                        'Status'     => ucfirst($r->status),
                        // Preserving your previous fix for recurrence_type
                        'Recurring'  => $r->is_recurring ? ($r->recurrence_type ?? 'Yes') : 'No',
                        'Created At' => $r->created_at->format('M d, Y h:i A'),
                        'Updated At' => $r->updated_at->format('M d, Y h:i A'),
                    ]);
                }
            });
            
            // Writer automatically closes and flushes on destruction
        }, $fileName);
    }
}