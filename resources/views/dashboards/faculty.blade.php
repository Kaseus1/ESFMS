@extends('layouts.faculty')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- My Upcoming Reservations --}}
    <div class="bg-white shadow rounded-lg p-5">
        <h3 class="text-gray-500 text-sm">My Upcoming Reservations</h3>
        <ul class="mt-2">
            @foreach(\App\Models\Reservation::where('user_id', auth()->id())->orderBy('date','asc')->take(5)->get() as $res)
                <li class="border-b py-2">{{ $res->facility->name }} — {{ $res->date }} ({{ $res->status }})</li>
            @endforeach
        </ul>
    </div>

    {{-- Quick Stats --}}
    <div class="bg-white shadow rounded-lg p-5">
        <h3 class="text-gray-500 text-sm">Total My Reservations</h3>
        <p class="text-2xl font-bold">{{ \App\Models\Reservation::where('user_id', auth()->id())->count() }}</p>
    </div>
</div>

{{-- Calendar --}}
<div class="mt-6 bg-white shadow rounded-lg p-6">
    <h2 class="text-lg font-semibold mb-4">My Reservation Calendar</h2>
    <div id="calendar"></div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: "{{ route('reservations.events') }}",
            height: 600,
        });
        calendar.render();
    });
</script>
@endsection
