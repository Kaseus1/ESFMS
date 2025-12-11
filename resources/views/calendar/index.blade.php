@extends('layouts.dashboard')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h2 class="text-xl font-semibold text-gray-800">Facility Reservations Calendar</h2>
    <a href="{{ route('reservations.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded shadow">
        Back to Reservations
    </a>
</div>

<div id="calendar" style="height: 700px;"></div>

<!-- Reservation Modal -->
<div id="reservationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
        <h3 class="text-lg font-semibold mb-4">Create Reservation</h3>
        <form id="reservationForm" method="POST" action="{{ route('reservations.store') }}">
            @csrf
            <input type="hidden" name="start_time" id="modal_start_time">
            <input type="hidden" name="end_time" id="modal_end_time">

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Facility</label>
                <select name="facility_id" class="w-full border-gray-300 rounded mt-1" required>
                    <option value="" disabled selected>Choose a facility</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Event Name</label>
                <input type="text" name="event_name" class="w-full border-gray-300 rounded mt-1" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Participants</label>
                <input type="number" name="participants" class="w-full border-gray-300 rounded mt-1" min="1">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" class="w-full border-gray-300 rounded mt-1" rows="3"></textarea>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Reserve</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        height: '100%',
        events: @json($events),

        select: function(info) {
            document.getElementById('modal_start_time').value = info.startStr;
            document.getElementById('modal_end_time').value = info.endStr;
            openModal();
        },

        eventClick: function(info) {
            alert('Facility: ' + info.event.title + '\nTime: ' + info.event.start.toLocaleString());
        }
    });

    calendar.render();
});

function openModal() {
    const modal = document.getElementById('reservationModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeModal() {
    const modal = document.getElementById('reservationModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}
</script>
@endpush
