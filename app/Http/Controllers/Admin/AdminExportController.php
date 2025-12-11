<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Facility;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Spatie\SimpleExcel\SimpleExcelWriter;

class AdminExportController extends Controller
{
    /**
     * Export Users (non-guests)
     */
    public function exportUsers(Request $request)
    {
        $format = $request->get('format', 'csv');

        if ($format === 'csv') {
            $users = User::where('role', '!=', 'guest')->get();

            $filename = 'users_' . now()->format('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function () use ($users) {
                $file = fopen('php://output', 'w');

                // UTF-8 BOM for Excel
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                // Headers
                fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Status', 'Department', 'School ID', 'Created At']);

                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->id,
                        $user->name,
                        $user->email,
                        ucfirst($user->role),
                        ucfirst($user->status ?? 'active'),
                        $user->department ?? 'N/A',
                        $user->school_id ?? 'N/A',
                        $user->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Invalid export format');
    }

    /**
     * Export Reservations
     */
    public function exportReservations(Request $request)
    {
        $format = $request->get('format', 'csv');

        if ($format === 'csv') {
            $reservations = Reservation::with(['user', 'facility'])->orderBy('created_at', 'desc')->get();

            $filename = 'reservations_' . now()->format('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function () use ($reservations) {
                $file = fopen('php://output', 'w');

                // UTF-8 BOM for Excel
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                // Headers
                fputcsv($file, [
                    'Reservation ID',
                    'Event Name',
                    'Facility Name',
                    'Facility Type',
                    'Facility Location',
                    'Reserved By',
                    'Email',
                    'User Role',
                    'Reservation Date',
                    'Start Time',
                    'End Time',
                    'Duration',
                    'Participants',
                    'Purpose',
                    'Notes',
                    'Status',
                    'Is Recurring',
                    'Requires Setup',
                    'Requires Equipment',
                    'Created At',
                    'Last Updated'
                ]);

                foreach ($reservations as $r) {
                    $startTime = $r->start_time ? Carbon::parse($r->start_time) : null;
                    $endTime = $r->end_time ? Carbon::parse($r->end_time) : null;

                    // Reservation date and formatted times
                    $reservationDate = $startTime ? $startTime->format('M d, Y') : 'N/A';
                    $startTimeFormatted = $startTime ? $startTime->format('h:i A') : 'N/A';
                    $endTimeFormatted = $endTime ? $endTime->format('h:i A') : 'N/A';

                    // Duration calculation
                    $duration = 'N/A';
                    if ($startTime && $endTime) {
                        $minutes = $startTime->diffInMinutes($endTime);
                        $hours = floor($minutes / 60);
                        $mins = $minutes % 60;
                        $duration = ($hours > 0 ? $hours . ' hour' . ($hours > 1 ? 's ' : ' ') : '') .
                                    ($mins > 0 ? $mins . ' min' : '');
                    }

                    fputcsv($file, [
                        $r->id,
                        $r->event_name ?? 'N/A',
                        $r->facility->name ?? 'N/A',
                        $r->facility->type ?? 'N/A',
                        $r->facility->location ?? 'N/A',
                        $r->user->name ?? 'N/A',
                        $r->user->email ?? 'N/A',
                        ucfirst($r->user->role ?? 'N/A'),
                        $reservationDate,
                        $startTimeFormatted,
                        $endTimeFormatted,
                        $duration,
                        $r->participants ?? 'N/A',
                        $r->purpose ?? 'No purpose specified',
                        $r->notes ?? 'No notes',
                        ucfirst($r->status),
                        $r->is_recurring ? 'Yes' : 'No',
                        $r->requires_setup ? 'Yes' : 'No',
                        $r->requires_equipment ? 'Yes' : 'No',
                        $r->created_at->format('M d, Y h:i A'),
                        $r->updated_at->format('M d, Y h:i A'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Invalid export format');
    }

    /**
     * Export Facilities
     */
    public function exportFacilities(Request $request)
    {
        $format = $request->get('format', 'csv');

        if ($format === 'csv') {
            $facilities = Facility::orderBy('name')->get();

            $filename = 'facilities_' . now()->format('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function () use ($facilities) {
                $file = fopen('php://output', 'w');

                // UTF-8 BOM
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                // Headers
                fputcsv($file, [
                    'ID',
                    'Name',
                    'Type',
                    'Location',
                    'Building',
                    'Floor',
                    'Capacity',
                    'Max Capacity',
                    'Description',
                    'Amenities',
                    'Status',
                    'Is Public',
                    'Created At'
                ]);

                foreach ($facilities as $f) {
                    fputcsv($file, [
                        $f->id,
                        $f->name,
                        $f->type ?? 'N/A',
                        $f->location ?? 'N/A',
                        $f->building ?? 'N/A',
                        $f->floor ?? 'N/A',
                        $f->capacity ?? 'N/A',
                        $f->max_capacity ?? 'N/A',
                        $f->description ?? 'N/A',
                        $f->amenities ?? 'N/A',
                        ucfirst($f->status ?? 'active'),
                        $f->is_public ? 'Yes' : 'No',
                        $f->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Invalid export format');
    }

    /**
     * Export Guest Requests
     */
    public function exportGuestRequests(Request $request)
    {
        $format = $request->get('format', 'csv');

        if ($format === 'csv') {
            $guests = User::where('role', 'guest')->orderBy('created_at', 'desc')->get();

            $filename = 'guest_requests_' . now()->format('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function () use ($guests) {
                $file = fopen('php://output', 'w');

                // UTF-8 BOM
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                // Headers
                fputcsv($file, [
                    'ID',
                    'Name',
                    'Email',
                    'Contact Number',
                    'Organization',
                    'Purpose',
                    'Status',
                    'Approved By',
                    'Approved At',
                    'Rejected At',
                    'Created At'
                ]);

                foreach ($guests as $g) {
                    fputcsv($file, [
                        $g->id,
                        $g->name,
                        $g->email,
                        $g->contact_number ?? 'N/A',
                        $g->organization ?? 'N/A',
                        $g->purpose ?? 'N/A',
                        ucfirst($g->status),
                        $g->approved_by ?? 'N/A',
                        $g->approved_at ? Carbon::parse($g->approved_at)->format('Y-m-d H:i:s') : 'N/A',
                        $g->rejected_at ? Carbon::parse($g->rejected_at)->format('Y-m-d H:i:s') : 'N/A',
                        $g->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Invalid export format');
    }
}
