@extends('layouts.admin')

@section('title', 'Reservation Calendar')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-[#172030]">Reservation Calendar</h1>
            <p class="text-[#333C4D] mt-2 opacity-75">Timeline view of all facility bookings</p>
        </div>
        <a href="{{ route('admin.reservations.index') }}" 
           class="px-6 py-2 bg-[#FFFFFF] border border-[#333C4D] border-opacity-20 text-[#333C4D] rounded-lg hover:bg-[#F8F9FA] flex items-center justify-center transition shadow-sm font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            List View
        </a>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#002366] p-4">
            <div class="text-xs font-bold text-[#333C4D] uppercase opacity-70">Today</div>
            <div class="text-2xl font-bold text-[#172030] mt-1">{{ $stats['today'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#F59E0B] p-4">
            <div class="text-xs font-bold text-[#333C4D] uppercase opacity-70">Pending</div>
            <div class="text-2xl font-bold text-[#172030] mt-1">{{ $stats['pending'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#10B981] p-4">
            <div class="text-xs font-bold text-[#333C4D] uppercase opacity-70">Approved</div>
            <div class="text-2xl font-bold text-[#172030] mt-1">{{ $stats['approved'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#EF4444] p-4">
            <div class="text-xs font-bold text-[#333C4D] uppercase opacity-70">Rejected</div>
            <div class="text-2xl font-bold text-[#172030] mt-1">{{ $stats['rejected'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md border-l-4 border-[#8B5CF6] p-4 col-span-2 md:col-span-1">
            <div class="text-xs font-bold text-[#333C4D] uppercase opacity-70">This Week</div>
            <div class="text-2xl font-bold text-[#172030] mt-1">{{ $stats['this_week'] ?? 0 }}</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-[#333C4D] mb-2">Status Filter</label>
                <select id="statusFilter" class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] text-[#172030]">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#333C4D] mb-2">Facility Filter</label>
                <select id="facilityFilter" class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] text-[#172030]">
                    <option value="all">All Facilities</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 flex items-end gap-3">
                <button onclick="calendar.today()" class="flex-1 px-4 py-2 bg-[#002366] text-white rounded-lg hover:bg-[#001A4A] shadow-md transition font-medium">
                    Go to Today
                </button>
            </div>
        </div>
    </div>

    {{-- Calendar Container --}}
    <div class="bg-white rounded-lg shadow-lg border border-[#333C4D] border-opacity-10 p-4 md:p-6 overflow-hidden">
        <div id="scheduleCalendar" class="min-h-[600px] calendar-custom"></div>
    </div>
</div>

@push('head-scripts')
<style>
    /* FullCalendar Custom Overrides */
    .fc-toolbar-title { font-size: 1.25rem !important; color: #172030; font-weight: 700 !important; }
    .fc-button-primary { background-color: #002366 !important; border-color: #002366 !important; }
    .fc-button-primary:hover { background-color: #001A4A !important; border-color: #001A4A !important; }
    .fc-button-active { background-color: #1D2636 !important; border-color: #1D2636 !important; }
    
    .fc-event { 
        border: none !important; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
        padding: 2px 4px;
        font-size: 0.85rem;
    }
    
    .fc-day-today { background-color: #F8F9FA !important; }
    
    /* Mobile View Adjustments */
    @media (max-width: 768px) {
        .fc-toolbar { flex-direction: column; gap: 0.75rem; }
        .fc-toolbar-title { font-size: 1rem !important; }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
let calendar;

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('scheduleCalendar');
    const isMobile = window.innerWidth < 768;
    
    if (calendarEl) {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: isMobile ? 'listWeek' : 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: isMobile ? 'listWeek,timeGridDay' : 'dayGridMonth,timeGridWeek,listWeek'
            },
            height: 'auto',
            navLinks: true, // can click day/week names to navigate views
            editable: false,
            dayMaxEvents: true, // allow "more" link when too many events
            
            // Event Source (AJAX)
            events: function(info, successCallback, failureCallback) {
                // Get current filter values
                const status = document.getElementById('statusFilter').value;
                const facilityId = document.getElementById('facilityFilter').value;
                
                // Build URL with params
                const url = new URL("{{ route('admin.dashboard.events') }}");
                url.searchParams.append('start', info.startStr);
                url.searchParams.append('end', info.endStr);
                if(status !== 'all') url.searchParams.append('status', status);
                if(facilityId !== 'all') url.searchParams.append('facility_id', facilityId);

                fetch(url)
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => {
                        console.error('Error fetching events:', error);
                        failureCallback(error);
                    });
            },

            // Styling Events based on status
            eventDidMount: function(info) {
                const status = info.event.extendedProps.status || 'pending';
                
                // Color mapping
                const colors = {
                    'approved': '#10B981', // Green
                    'pending': '#F59E0B',  // Yellow
                    'rejected': '#EF4444', // Red
                    'cancelled': '#6B7280' // Gray
                };
                
                info.el.style.backgroundColor = colors[status] || '#3B82F6';
                info.el.style.borderColor = colors[status] || '#3B82F6';
                
                // Tooltip content
                const facilityName = info.event.extendedProps.facility || 'Unknown Facility';
                const userName = info.event.extendedProps.user || 'Unknown User';
                
                info.el.title = `${info.event.title}\nFacility: ${facilityName}\nUser: ${userName}\nStatus: ${status.toUpperCase()}`;
            },

            // Click action
            eventClick: function(info) {
                // Redirect to reservation detail page
                window.location.href = `/admin/reservations/${info.event.id}`;
            },

            // Responsive Window Resize Logic
            windowResize: function(view) {
                if (window.innerWidth < 768) {
                    calendar.changeView('listWeek');
                } else {
                    calendar.changeView('dayGridMonth');
                }
            }
        });
        
        calendar.render();

        // Attach Filter Listeners
        const refreshCalendar = () => calendar.refetchEvents();
        
        document.getElementById('statusFilter').addEventListener('change', refreshCalendar);
        document.getElementById('facilityFilter').addEventListener('change', refreshCalendar);
    }
});
</script>
@endpush
@endsection