<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Facility;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class AdminExportController extends Controller
{
    /**
     * Display the export hub page.
     */
    public function index()
    {
        // You need to create this view: resources/views/admin/export/index.blade.php
        return view('admin.export.index'); 
    }

    /**
     * Export Users
     */
    public function exportUsers()
    {
        $fileName = 'users_export_' . now()->format('Y-m-d_His') . '.csv';
        $users = User::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Name', 'Email', 'Role', 'Status', 'Created At'];

        $callback = function() use($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->status,
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Reservations
     */
    public function exportReservations()
    {
        $fileName = 'reservations_export_' . now()->format('Y-m-d_His') . '.csv';
        $reservations = Reservation::with(['user', 'facility'])->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Event Name', 'User', 'Facility', 'Start Time', 'End Time', 'Status', 'Cost'];

        $callback = function() use($reservations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($reservations as $reservation) {
                fputcsv($file, [
                    $reservation->id,
                    $reservation->event_name,
                    $reservation->user->name ?? 'N/A',
                    $reservation->facility->name ?? 'N/A',
                    $reservation->start_time,
                    $reservation->end_time,
                    $reservation->status,
                    $reservation->cost,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Facilities
     */
    public function exportFacilities()
    {
        $fileName = 'facilities_export_' . now()->format('Y-m-d_His') . '.csv';
        $facilities = Facility::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Name', 'Location', 'Capacity', 'Price per Hour', 'Status'];

        $callback = function() use($facilities, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($facilities as $facility) {
                fputcsv($file, [
                    $facility->id,
                    $facility->name,
                    $facility->location,
                    $facility->capacity,
                    $facility->price_per_hour,
                    $facility->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Guest Requests
     */
    public function exportGuestRequests()
    {
        $fileName = 'guest_requests_' . now()->format('Y-m-d_His') . '.csv';
        $guests = User::where('role', 'guest')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Name', 'Email', 'Organization', 'Purpose', 'Status', 'Registered Date'];

        $callback = function() use($guests, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($guests as $guest) {
                fputcsv($file, [
                    $guest->id,
                    $guest->name,
                    $guest->email,
                    $guest->organization ?? 'N/A',
                    $guest->purpose ?? 'N/A',
                    $guest->status,
                    $guest->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}