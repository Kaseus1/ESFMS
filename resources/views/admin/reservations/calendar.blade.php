@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reservation Calendar</h1>
            <p class="text-gray-600 mt-1">Timeline view of all facility bookings</p>
        </div>
        <a href="{{ route('admin.reservations.index') }}" 
           class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            List View
        </a>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="text-sm text-gray-600">Today</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['today'] ?? 0 }}</div>
        </div>
        <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-4">
            <div class="text-sm text-yellow-700">Pending</div>
            <div class="text-2xl font-bold text-yellow-900">{{ $stats['pending'] ?? 0 }}</div>
        </div>
        <div class="bg-green-50 rounded-lg border border-green-200 p-4">
            <div class="text-sm text-green-700">Approved</div>
            <div class="text-2xl font-bold text-green-900">{{ $stats['approved'] ?? 0 }}</div>
        </div>
        <div class="bg-red-50 rounded-lg border border-red-200 p-4">
            <div class="text-sm text-red-700">Rejected</div>
            <div class="text-2xl font-bold text-red-900">{{ $stats['rejected'] ?? 0 }}</div>
        </div>
        <div class="bg-purple-50 rounded-lg border border-purple-200 p-4">
            <div class="text-sm text-purple-700">This Week</div>
            <div class="text-2xl font-bold text-purple-900">{{ $stats['this_week'] ?? 0 }}</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147]">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Facility</label>
                <select id="facilityFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147]">
                    <option value="all">All Facilities</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 flex items-end gap-2">
                <button onclick="calendar.today()" class="flex-1 px-4 py-2 bg-[#002147] text-white rounded-lg hover:bg-[#003366]">
                    Today
                </button>
                <button onclick="window.print()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Print
                </button>
            </div>
        </div>
    </div>

    {{-- Calendar --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div id="scheduleCalendar" style="min-height: 600px;"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.10/index.global.min.js"></script>
<script>
let calendar;

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('scheduleCalendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'resourceTimelineWeek',
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'resourceTimelineDay,resourceTimelineWeek,resourceTimelineMonth'
        },
        resourceAreaHeaderContent: 'Facilities',
        resources: @json($facilities->map(fn($f) => ['id' => $f->id, 'title' => $f->name])),
        events: "{{ route('admin.dashboard.events') }}",
        eventClick: function(info) {
            window.location.href = `/reservations/${info.event.id}`;
        }
    });
    
    calendar.render();
});
</script>
@endpush
@endsection