@extends('layouts.guest')

@section('title', 'guest Dashboard')

@push('head-scripts')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<style>
    /* CPAC Brand Colors */
    :root {
        --color-royal-blue: #002366;
        --color-navy-blue: #00285C;
        --color-accent-blue: #001A4A;
        --color-text-dark: #172030;
        --color-text-gray: #333C4D;
        --color-bg-light: #F8F9FA;
        --color-border: #E2E8F0;
    }

    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

    /* Dashboard Layout */
    .dashboard-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding-bottom: 2rem;
    }

    @media (min-width: 1024px) {
        .dashboard-container {
            grid-template-columns: 2fr 1fr;
        }
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    @media (min-width: 640px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (min-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(4, 1fr); }
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid var(--color-border);
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0; width: 4px;
        background: currentColor;
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .stat-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748B;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--color-text-dark);
        line-height: 1;
    }

    /* Quick Actions */
    .actions-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    @media (min-width: 640px) {
        .actions-grid { grid-template-columns: repeat(3, 1fr); }
    }

    .action-btn {
        background: white;
        padding: 1.25rem;
        border-radius: 16px;
        border: 1px solid var(--color-border);
        text-decoration: none;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .action-btn:hover {
        border-color: var(--color-royal-blue);
        background: #F8FAFC;
        transform: translateY(-2px);
    }

    .action-btn-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .action-btn-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    /* Calendar Styling */
    .calendar-wrapper {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--color-border);
    }

    .fc-toolbar-title { font-size: 1.1rem !important; font-weight: 700 !important; color: var(--color-text-dark); }
    .fc-button-primary { 
        background-color: var(--color-royal-blue) !important; 
        border-color: var(--color-royal-blue) !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        padding: 0.4rem 0.8rem !important;
        border-radius: 8px !important;
    }
    .fc-button-primary:hover { background-color: var(--color-navy-blue) !important; }
    .fc-event { border: none !important; border-radius: 6px !important; padding: 2px 4px !important; font-size: 0.75rem !important; cursor: pointer; }
    .fc-daygrid-day-number { color: var(--color-text-dark); font-weight: 600; text-decoration: none !important; }
    .fc-col-header-cell-cushion { color: #64748B; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; text-decoration: none !important; }
    
    /* Lists */
    .list-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--color-border);
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .list-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--color-border);
        background: #F8FAFC;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .reservation-item {
        padding: 1rem;
        border-bottom: 1px solid var(--color-border);
        display: flex;
        gap: 1rem;
        align-items: center;
        transition: background 0.2s;
        text-decoration: none;
        color: inherit;
    }
    
    .reservation-item:last-child { border-bottom: none; }
    .reservation-item:hover { background: #F1F5F9; }

    .date-badge {
        background: #F1F5F9;
        border-radius: 10px;
        min-width: 50px;
        height: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid var(--color-border);
    }
</style>
@endpush

@section('content')

<div class="relative bg-gradient-to-r from-[#002366] to-[#001A4A] rounded-2xl p-6 md:p-8 mb-8 text-white shadow-xl overflow-hidden">
    <div class="relative z-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="text-blue-200 font-medium mb-1 text-sm md:text-base"> Welcome back,</p>
                <h1 class="text-2xl md:text-4xl font-extrabold mb-2">{{ Auth::user()->name }}</h1>
                <p class="text-blue-100/80 text-sm max-w-xl leading-relaxed">
                    Manage your facility bookings, check your wallet balance, and track requests all in one place.
                </p>
                <div class="mt-4 inline-flex items-center px-3 py-1 bg-white/10 rounded-full border border-white/20 backdrop-blur-sm text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-green-400 mr-2 animate-pulse"></span>
                    Account Status: {{ ucfirst(Auth::user()->status) }}
                </div>
            </div>
            <div class="flex gap-3">
                 <a href="{{ route('guest.facilities.index') }}" 
                   class="px-5 py-2.5 bg-white text-[#002366] text-sm font-bold rounded-xl hover:bg-blue-50 transition shadow-lg flex items-center justify-center">
                    <i class="fa-solid fa-plus mr-2"></i> New Booking
                </a>
            </div>
        </div>
    </div>
    <div class="absolute top-0 right-0 -mt-8 -mr-8 w-48 h-48 bg-white opacity-5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-blue-500 opacity-10 rounded-full blur-2xl"></div>
</div>

<div class="stats-grid">
    <div class="stat-card" style="color: var(--color-royal-blue);">
        <div class="stat-header">
            <span class="stat-title">Total Bookings</span>
            <div class="stat-icon" style="background: linear-gradient(135deg, #002366, #001A4A);">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
        <div class="stat-value">{{ $totalReservations }}</div>
    </div>

    <div class="stat-card" style="color: #F59E0B;">
        <div class="stat-header">
            <span class="stat-title">Pending</span>
            <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
        <div class="stat-value">{{ $pendingReservations }}</div>
    </div>

    <div class="stat-card" style="color: #10B981;">
        <div class="stat-header">
            <span class="stat-title">Approved</span>
            <div class="stat-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                <i class="fa-solid fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value">{{ $approvedReservations }}</div>
    </div>

    <div class="stat-card" style="color: #8B5CF6;">
        <div class="stat-header">
            <span class="stat-title">Facilities</span>
            <div class="stat-icon" style="background: linear-gradient(135deg, #8B5CF6, #7C3AED);">
                <i class="fa-solid fa-building"></i>
            </div>
        </div>
        <div class="stat-value">{{ $totalFacilities }}</div>
    </div>
</div>

<div class="actions-grid">
    <a href="{{ route('guest.facilities.index') }}" class="action-btn">
        <div class="action-btn-header">
            <div class="action-btn-icon bg-[#002366]">
                <i class="fa-solid fa-building"></i>
            </div>
            <i class="fa-solid fa-arrow-right text-gray-300"></i>
        </div>
        <h3 class="font-bold text-gray-800 text-lg">Browse Facilities</h3>
        <p class="text-sm text-gray-500 mt-1">Explore spaces for your next event.</p>
    </a>

    <a href="{{ route('guest.reservations.index') }}" class="action-btn">
        <div class="action-btn-header">
            <div class="action-btn-icon bg-[#00285C]">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <i class="fa-solid fa-arrow-right text-gray-300"></i>
        </div>
        <h3 class="font-bold text-gray-800 text-lg">My Reservations</h3>
        <p class="text-sm text-gray-500 mt-1">Track status and view history.</p>
    </a>

    <a href="{{ route('guest.wallet.index') }}" class="action-btn">
        <div class="action-btn-header">
            <div class="action-btn-icon bg-[#10B981]">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <i class="fa-solid fa-arrow-right text-gray-300"></i>
        </div>
        <h3 class="font-bold text-gray-800 text-lg">My Wallet</h3>
        <p class="text-sm text-gray-500 mt-1">Check balance: ₱{{ number_format(Auth::user()->wallet_balance, 2) }}</p>
    </a>
</div>

<div class="dashboard-container">
    <div class="calendar-wrapper">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-[#002366]">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <h3 class="text-xl font-bold text-[#172030]">Reservation Schedule</h3>
        </div>
        <div id="calendar" class="min-h-[400px]"></div>
    </div>

    <div class="flex flex-col gap-6">
        
        <div class="list-card">
            <div class="list-header">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-hourglass-start text-[#002366]"></i> Upcoming
                </h3>
                <a href="{{ route('guest.reservations.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">View All</a>
            </div>
            
            <div class="max-h-[350px] overflow-y-auto custom-scrollbar">
                @forelse($upcomingReservations as $reservation)
                    <a href="{{ route('guest.reservations.show', $reservation->id) }}" class="reservation-item group">
                        <div class="date-badge group-hover:border-blue-200 group-hover:bg-blue-50 transition">
                            <span class="text-[10px] font-bold text-gray-400 uppercase leading-none">{{ \Carbon\Carbon::parse($reservation->start_time)->format('M') }}</span>
                            <span class="text-lg font-bold text-[#002366] leading-none mt-1">{{ \Carbon\Carbon::parse($reservation->start_time)->format('d') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-800 truncate">{{ $reservation->event_name }}</h4>
                            <p class="text-xs text-gray-500 truncate mt-0.5">
                                {{ optional($reservation->facility)->name ?? 'Unknown Facility' }}
                            </p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide text-white
                                    {{ $reservation->status == 'approved' ? 'bg-green-500' : ($reservation->status == 'pending' ? 'bg-yellow-500' : 'bg-gray-400') }}">
                                    {{ $reservation->status }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-medium">
                                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-300 text-xs group-hover:text-blue-500 transition"></i>
                    </a>
                @empty
                    <div class="p-8 text-center">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                            <i class="fa-regular fa-calendar-xmark text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">No upcoming events</p>
                        <a href="{{ route('guest.facilities.index') }}" class="text-xs text-blue-600 font-bold mt-1 inline-block">Book Now</a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="list-card">
            <div class="list-header">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-[#002366]"></i> Recent Activity
                </h3>
            </div>
            <div class="p-4 space-y-4">
                @forelse($recentReservations as $res)
                    <div class="flex gap-3 relative">
                        @if(!$loop->last)
                            <div class="absolute left-[15px] top-8 bottom-[-16px] w-[2px] bg-gray-100"></div>
                        @endif
                        
                        <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center border-2 border-white shadow-sm flex-shrink-0
                            {{ $res->status == 'approved' ? 'bg-green-100 text-green-600' : ($res->status == 'rejected' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }}">
                            <i class="fa-solid {{ $res->status == 'approved' ? 'fa-check' : ($res->status == 'rejected' ? 'fa-xmark' : 'fa-hourglass') }} text-xs"></i>
                        </div>
                        
                        <div class="pb-1">
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Reservation for <span class="font-bold text-gray-800">{{ $res->event_name }}</span> was 
                                <span class="font-bold 
                                    {{ $res->status == 'approved' ? 'text-green-600' : ($res->status == 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">
                                    {{ ucfirst($res->status) }}
                                </span>.
                            </p>
                            <span class="text-[10px] text-gray-400 font-medium mt-1 block">
                                {{ $res->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-sm text-gray-500">No recent activity found.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    // Process events safely
    var events = [
        @foreach($calendarReservations ?? [] as $res)
        {
            id: '{{ $res->id }}',
            title: '{{ addslashes($res->event_name) }}',
            start: '{{ $res->start_time ? $res->start_time->format("Y-m-d\TH:i:s") : "" }}',
            end: '{{ $res->end_time ? $res->end_time->format("Y-m-d\TH:i:s") : "" }}',
            backgroundColor: '{{ $res->status == "approved" ? "#10B981" : ($res->status == "pending" ? "#F59E0B" : "#EF4444") }}',
            borderColor: 'transparent',
            extendedProps: {
                status: '{{ ucfirst($res->status) }}',
                facility: '{{ addslashes(optional($res->facility)->name ?? "Unknown") }}'
            },
            url: '{{ route("guest.reservations.show", $res->id) }}'
        },
        @endforeach
    ];

    if(calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: window.innerWidth < 768 ? 'today' : 'dayGridMonth,listMonth'
            },
            height: 'auto',
            buttonText: { today: 'Today', month: 'Month', list: 'List' },
            events: events,
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                    return false;
                }
            },
            eventDidMount: function(info) {
                // Add tooltip-like behavior or custom content
                info.el.title = `${info.event.title} (${info.event.extendedProps.status}) at ${info.event.extendedProps.facility}`;
            },
            windowResize: function(view) {
                if (window.innerWidth < 768) {
                    calendar.changeView('listMonth');
                    calendar.setOption('headerToolbar', {
                        left: 'prev,next',
                        center: 'title',
                        right: 'today'
                    });
                } else {
                    calendar.changeView('dayGridMonth');
                    calendar.setOption('headerToolbar', {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,listMonth'
                    });
                }
            }
        });
        calendar.render();
    }
});
</script>
@endpush