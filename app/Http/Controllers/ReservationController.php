<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function exportReservations()
    {
        $fileName = 'reservations_' . now()->format('Y-m-d_His') . '.xlsx';

        return SimpleExcelWriter::streamDownload($fileName, function (SimpleExcelWriter $writer) {
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
                        'Recurring'  => $r->is_recurring ? ($r->recurring_type ?? 'Yes') : 'No',
                        'Created At' => $r->created_at->format('M d, Y h:i A'),
                        'Updated At' => $r->updated_at->format('M d, Y h:i A'),
                    ]);
                }
            });
        });
    }
}
