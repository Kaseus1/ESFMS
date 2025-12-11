
@extends('layouts.dashboard')

@section('content')
{{-- ====== Summary Cards ====== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    {{-- Total Reservations --}}
    <div class="bg-white shadow rounded-xl p-5 border-t-4 border-indigo-600 hover:shadow-md transition">
        <h3 class="text-gray-500 text-sm font-medium">Total Reservations</h3>
        <p class="text-gray-900 text-3xl font-bold mt-2">{{ $totalReservations }}</p>
    </div>

    {{-- Approved --}}
    <div class="bg-white shadow rounded-xl p-5 border-t-4 border-green-500 hover:shadow-md transition">
        <h3 class="text-gray-500 text-sm font-medium">Approved</h3>
        <p class="text-gray-900 text-3xl font-bold mt-2">{{ $approvedCount }}</p>
    </div>

    {{-- Pending --}}
    <div class="bg-white shadow rounded-xl p-5 border-t-4 border-yellow-500 hover:shadow-md transition">
        <h3 class="text-gray-500 text-sm font-medium">Pending</h3>
        <p class="text-gray-900 text-3xl font-bold mt-2">{{ $pendingCount }}</p>
    </div>

    {{-- Rejected --}}
    <div class="bg-white shadow rounded-xl p-5 border-t-4 border-red-500 hover:shadow-md transition">
        <h3 class="text-gray-500 text-sm font-medium">Rejected</h3>
        <p class="text-gray-900 text-3xl font-bold mt-2">{{ $rejectedCount }}</p>
    </div>
</div>

{{-- ====== Next Upcoming Reservation ====== --}}
<div class="mt-8 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl shadow-lg p-6">
    @if ($nextReservation)
        <h3 class="text-lg font-semibold mb-2">Next Upcoming Reservation</h3>
        <p class="text-sm">
            <span class="font-semibold">{{ $nextReservation->facility->name }}</span>
            on 
            <span class="font-semibold">
                {{ $nextReservation->start_time->format('F j, Y g:i A') }}
            </span>
        </p>
    @else
        <h3 class="text-lg font-semibold mb-2">No Upcoming Reservations</h3>
        <p class="text-sm opacity-90">There are currently no upcoming reservations scheduled.</p>
    @endif
</div>

{{-- ====== Action Buttons ====== --}}
<div class="mt-6 flex justify-between items-center">
    <h2 class="text-lg font-semibold text-gray-800">Recent Reservations</h2>
    <a href="{{ route('reservations.create') }}" 
       class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg shadow">
       + Create Reservation
    </a>
</div>

{{-- ====== Recent Reservations Table ====== --}}
<div class="mt-4 bg-white shadow rounded-xl p-6 border-t-4 border-indigo-600">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Facility</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Start</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">End</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($recentReservations as $res)
                <tr class="hover:bg-indigo-50 cursor-pointer transition"
                    onclick="window.location='{{ route('reservations.show', $res->id) }}'">
                    <td class="px-6 py-3 text-gray-700">{{ $res->facility->name }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ \Carbon\Carbon::parse($res->start_time)->format('M d, Y h:i A') }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ \Carbon\Carbon::parse($res->end_time)->format('M d, Y h:i A') }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($res->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($res->status === 'approved') bg-green-100 text-green-700
                            @elseif($res->status === 'rejected') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($res->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500">No recent reservations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ====== Reservation Calendar ====== --}}
<div class="mt-8 bg-white shadow rounded-xl p-6 border-t-4 border-indigo-600">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Facility Reservation Calendar</h2>
    <div id="calendar" style="height: 700px;"></div>
</div>

{{-- ====== FullCalendar Script ====== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: '100%',
            events: @json($events),
            selectable: true,
            select(info) {
                const start = info.startStr;
                const end = info.endStr;
                window.location.href = `/reservations/create?start_time=${start}&end_time=${end}`;
            },
            eventClick(info) {
                alert(`Reservation: ${info.event.title}\nStart: ${info.event.start}\nEnd: ${info.event.end}`);
            }
        });
        calendar.render();
    }
});
</script>
@endpush
@endsection
