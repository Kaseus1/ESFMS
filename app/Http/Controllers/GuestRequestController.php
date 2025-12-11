<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\GuestRequest;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;

class GuestRequestController extends Controller
{
    public function create()
    {
        $facilities = Facility::where('is_public', true)->get();
        return view('reservations.guest_create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'nullable|string|max:50',
            'facility_id' => 'required|exists:facilities,id',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'purpose' => 'required|string|max:500',
        ]);

        $guest = GuestRequest::create($validated);

        try {
            Mail::raw("Your request has been received. We'll get back to you soon.", function ($msg) use ($guest) {
                $msg->to($guest->email)->subject("CPAC Facility Request Received");
            });
        } catch (\Throwable $e) {
            // ignore mail errors
        }

        return back()->with('success', 'Your request was submitted! Please check your email.');
    }

    public function adminIndex()
    {
        $requests = GuestRequest::with('facility')->orderBy('created_at', 'desc')->get();
        return view('admin.guest_requests.index', compact('requests'));
    }

    public function approve($id)
    {
        $req = GuestRequest::findOrFail($id);
        $req->status = 'approved';
        $req->save();

        $reservation = new Reservation();

        // map to your actual reservations columns
        $reservation->user_id        = null;
        $reservation->facility_id    = $req->facility_id;
        $reservation->event_name     = $req->purpose ?? $req->full_name;
        $reservation->participants   = null;
        $reservation->notes          = "Requested by {$req->full_name}" . ($req->contact_number ? " (tel: {$req->contact_number})" : '');
        $reservation->start_time     = $req->start_time;
        $reservation->end_time       = $req->end_time;
        $reservation->status         = 'approved';
        $reservation->is_recurring   = false;
        $reservation->recurring_type = null;

        $reservation->save();

        try {
            Mail::raw("Your booking request has been approved!", function ($msg) use ($req) {
                $msg->to($req->email)->subject("CPAC Facility Request Approved");
            });
        } catch (\Throwable $e) {
            // ignore mail errors
        }

        return back()->with('success', 'Guest request approved and reservation created.');
    }

    public function deny($id)
    {
        $req = GuestRequest::findOrFail($id);
        $req->status = 'denied';
        $req->save();

        try {
            Mail::raw("Your booking request has been denied.", function ($msg) use ($req) {
                $msg->to($req->email)->subject("CPAC Facility Request Denied");
            });
        } catch (\Throwable $e) {
            // ignore mail errors
        }

        return back()->with('error', 'Guest request denied.');
    }
}