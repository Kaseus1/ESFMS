@extends('layouts.student')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6">
    <!-- Total Reservations -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 border-t-4 border-gray-700 transform hover:scale-105 transition-transform duration-200">
        <h3 class="text-gray-800 text-xs sm:text-sm font-semibold">Total Reservations</h3>
        <p class="text-gray-900 text-xl sm:text-2xl font-bold mt-2">{{ $totalReservations }}</p>
    </div>

    <!-- Approved -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 border-t-4 border-green-500 transform hover:scale-105 transition-transform duration-200">
        <h3 class="text-gray-800 text-xs sm:text-sm font-semibold">Approved</h3>
        <p class="text-green-600 text-xl sm:text-2xl font-bold mt-2">{{ $approvedReservations }}</p>
    </div>

    <!-- Pending -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 border-t-4 border-yellow-500 transform hover:scale-105 transition-transform duration-200">
        <h3 class="text-gray-800 text-xs sm:text-sm font-semibold">Pending</h3>
        <p class="text-yellow-600 text-xl sm:text-2xl font-bold mt-2">{{ $pendingReservations }}</p>
    </div>

    <!-- Rejected -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 border-t-4 border-red-500 transform hover:scale-105 transition-transform duration-200">
        <h3 class="text-gray-800 text-xs sm:text-sm font-semibold">Rejected</h3>
        <p class="text-red-600 text-xl sm:text-2xl font-bold mt-2">{{ $rejectedReservations }}</p>
    </div>
</div>

<!-- Next Upcoming Reservation -->
<div class="mt-4 sm:mt-6 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 mb-4 sm:mb-6">
    @if ($nextReservation)
        <h3 class="text-base sm:text-lg font-semibold mb-2">Next Upcoming Reservation</h3>
        <p class="text-sm">
            <span class="font-medium">{{ $nextReservation->facility->name }}</span>
            on <span class="font-medium">{{ \Carbon\Carbon::parse($nextReservation->start_time)->format('F j, Y g:i A') }}</span>
        </p>
    @else
        <h3 class="text-base sm:text-lg font-semibold mb-2">No Upcoming Reservations</h3>
        <p class="text-sm">You currently have no upcoming reservations.</p>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
    <!-- Left Column: Calendar -->
    <div class="lg:col-span-2">
        <!-- FullCalendar -->
        <div class="bg-white shadow-lg rounded-lg sm:rounded-xl p-4 sm:p-5 border-t-4 border-gray-700">
            <h2 class="text-base sm:text-lg font-semibold mb-4 text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-calendar-alt text-indigo-600"></i>
                My Calendar
            </h2>
            <div id="calendar" class="min-h-[400px] sm:min-h-[500px]"></div>
        </div>
    </div>

    <!-- Right Column: Recent Reservations -->
    <div>
        <div class="bg-white shadow-lg rounded-lg sm:rounded-xl p-4 sm:p-6 border-t-4 border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-history text-indigo-600"></i>
                    Recent Activity
                </h2>
                <a href="{{ route('student.reservations.create') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition text-sm touch-manipulation">
                   <i class="fa-solid fa-plus"></i>
                   <span>Add Reservation</span>
                </a>
            </div>

            <div class="space-y-3 sm:space-y-4">
                @forelse($recentReservations as $res)
                <div class="border-l-4 {{ 
                    $res->status === 'approved' ? 'border-green-500 bg-green-50' : 
                    ($res->status === 'pending' ? 'border-yellow-500 bg-yellow-50' : 
                    'border-red-500 bg-red-50') 
                }} rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <h4 class="font-semibold text-gray-900 text-sm sm:text-base flex-1 break-words">
                            {{ $res->facility->name }}
                        </h4>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold uppercase whitespace-nowrap {{ 
                            $res->status === 'approved' ? 'bg-green-200 text-green-800' : 
                            ($res->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 
                            'bg-red-200 text-red-800') 
                        }}">
                            {{ $res->status }}
                        </span>
                    </div>
                    <div class="space-y-1 text-xs sm:text-sm text-gray-700">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-calendar text-gray-400 w-4"></i>
                            <span>{{ \Carbon\Carbon::parse($res->start_time)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-clock text-gray-400 w-4"></i>
                            <span>{{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 sm:py-8">
                    <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full mb-3">
                        <i class="fa-solid fa-inbox text-xl sm:text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-sm sm:text-base text-gray-600">No reservations yet</p>
                    <a href="{{ route('student.reservations.create') }}" 
                       class="mt-3 inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 font-semibold touch-manipulation">
                        <i class="fa-solid fa-plus"></i>
                        Create your first reservation
                    </a>
                </div>
                @endforelse
            </div>

            @if($recentReservations->count() > 0)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('student.reservations.index') }}" 
                   class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold hover:underline flex items-center justify-center gap-1 touch-manipulation">
                    View All Reservations
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Pass PHP events to JS --}}
<script>
    window.calendarEvents = @json($events);
</script>

{{-- FullCalendar + SweetAlert2 Scripts --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
            events: window.calendarEvents,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: window.innerWidth < 768 ? 'listWeek,dayGridMonth' : 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            height: 'auto',
            eventColor: '#378006',
            eventClick: function(info) {
                Swal.fire({
                    title: info.event.title,
                    html: `<p>Status: <strong>${info.event.extendedProps.status}</strong></p>
                           <p>Start: ${info.event.start.toLocaleString()}</p>
                           <p>End: ${info.event.end ? info.event.end.toLocaleString() : 'N/A'}</p>
                           <p>Participants: ${info.event.extendedProps.participants ?? 'N/A'}</p>
                           <p>Notes: ${info.event.extendedProps.notes ?? 'N/A'}</p>`,
                    icon: 'info',
                    confirmButtonColor: '#4f46e5'
                });
            }
        });

        calendar.render();
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth < 768) {
                calendar.changeView('listWeek');
            } else {
                calendar.changeView('dayGridMonth');
            }
        });
    }
});
</script>
@endpush

<style>
.touch-manipulation {
    touch-action: manipulation;
}

/* Mobile calendar optimizations */
@media (max-width: 640px) {
    .fc-header-toolbar {
        font-size: 0.875rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .fc-button {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .fc-toolbar-title {
        font-size: 1rem;
    }
    
    .fc-toolbar-chunk {
        display: flex;
        gap: 0.25rem;
    }
}

/* Ensure proper touch targets on mobile */
@media (max-width: 640px) {
    .fc-button {
        min-height: 36px;
        min-width: 36px;
    }
}
</style>
@endsection 