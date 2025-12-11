@extends('layouts.dashboard')

@section('content')
<div class="bg-white shadow rounded-lg p-6 border-t-4 border-gray-700">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Reservation Details</h2>

    <div class="space-y-3">
        <div>
            <span class="font-semibold text-gray-600">Event Name:</span>
            <span class="text-gray-800">{{ $reservation->event_name }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-600">Facility:</span>
            <span class="text-gray-800">{{ $reservation->facility->name }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-600">Reserved By:</span>
            <span class="text-gray-800">{{ $reservation->user->name }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-600">Start Time:</span>
            <span class="text-gray-800">{{ \Carbon\Carbon::parse($reservation->start_time)->format('M d, Y H:i') }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-600">End Time:</span>
            <span class="text-gray-800">{{ \Carbon\Carbon::parse($reservation->end_time)->format('M d, Y H:i') }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-600">Participants:</span>
            <span class="text-gray-800">{{ $reservation->participants ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-600">Status:</span>
            <span class="uppercase font-bold 
                @if($reservation->status === 'pending') text-yellow-600
                @elseif($reservation->status === 'approved') text-green-600
                @elseif($reservation->status === 'rejected') text-red-600
                @endif">
                {{ $reservation->status }}
            </span>
        </div>
        <div>
            <span class="font-semibold text-gray-600">Notes:</span>
            <span class="text-gray-800">{{ $reservation->notes ?? 'None' }}</span>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('reservations.index') }}" class="px-4 py-2 bg-gray-700 text-white rounded shadow hover:bg-gray-800">
            ← Back to Reservations
        </a>
    </div>
</div>
@endsection
