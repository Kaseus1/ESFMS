@extends('layouts.admin')

@section('title', 'Dashboard')

@push('head-scripts')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<style>
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 768px) {
        .dashboard-stats { grid-template-columns: repeat(4, 1fr); }
    }
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border-left: 4px solid;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #fff;
    }
    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        margin-top: 0.25rem;
    }
    .stat-card .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    @media (min-width: 1024px) {
        .dashboard-grid { grid-template-columns: 2fr 1fr; }
    }
    
    .calendar-container {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    @media (min-width: 640px) {
        .calendar-container { padding: 1.5rem; }
    }
    
    .calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .calendar-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .fc { font-family: 'Inter', sans-serif; }
    .fc .fc-toolbar { flex-wrap: wrap; gap: 0.5rem; }
    .fc .fc-toolbar-title {
        font-size: 1rem !important;
        font-weight: 700 !important;
        color: #1e293b;
    }
    .fc .fc-button {
        background: linear-gradient(135deg, #10b981, #059669) !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 0.4rem 0.75rem !important;
        font-weight: 600 !important;
        font-size: 0.8rem !important;
        box-shadow: 0 2px 8px rgba(16,185,129,0.3) !important;
        transition: all 0.2s !important;
    }
    .fc .fc-button:hover {
        background: linear-gradient(135deg, #059669, #047857) !important;
        transform: translateY(-1px);
    }
    .fc .fc-button-active {
        background: linear-gradient(135deg, #059669, #047857) !important;
    }
    .fc .fc-daygrid-day { transition: background 0.2s; }
    .fc .fc-daygrid-day:hover { background: #f0fdf4; }
    .fc .fc-daygrid-day-number { font-weight: 600; color: #334155; padding: 0.5rem; }
    .fc .fc-day-today { background: linear-gradient(135deg, #ecfdf5, #d1fae5) !important; }
    .fc .fc-event {
        border-radius: 6px !important;
        padding: 2px 6px !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        border: none !important;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .fc .fc-event:hover {
        transform: scale(1.02);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .fc .fc-event-approved { background: linear-gradient(135deg, #10b981, #059669) !important; }
    .fc .fc-event-pending { background: linear-gradient(135deg, #f59e0b, #d97706) !important; }
    .fc .fc-event-rejected { background: linear-gradient(135deg, #ef4444, #dc2626) !important; }
    .fc .fc-event-cancelled { background: linear-gradient(135deg, #6b7280, #4b5563) !important; }
    
    .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: #64748b;
    }
    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 4px;
    }
    
    @media (max-width: 640px) {
        .fc .fc-toolbar { flex-direction: column; align-items: stretch; }
        .fc .fc-toolbar-chunk { display: flex; justify-content: center; }
        .fc .fc-daygrid-event { font-size: 0.65rem !important; padding: 1px 3px !important; }
        .fc .fc-col-header-cell-cushion { font-size: 0.75rem; }
    }
    
    .sidebar-section {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .pending-item {
        display: flex;
        gap: 0.75rem;
        padding: 0.875rem;
        border-radius: 10px;
        background: #f8fafc;
        margin-bottom: 0.5rem;
        transition: all 0.2s;
        border-left: 3px solid #f59e0b;
        text-decoration: none;
        color: inherit;
    }
    .pending-item:hover {
        background: #f1f5f9;
        transform: translateX(4px);
    }
    .pending-item:last-child { margin-bottom: 0; }
    .pending-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }
    .pending-details { flex: 1; min-width: 0; }
    .pending-title {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.875rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .pending-meta {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.25rem;
    }
    
    .view-all-btn {
        display: block;
        width: 100%;
        padding: 0.75rem;
        text-align: center;
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        color: #059669;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        margin-top: 1rem;
        transition: all 0.2s;
    }
    .view-all-btn:hover {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        transform: translateY(-2px);
    }
    
    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
        color: #94a3b8;
    }
    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.3;
    }
</style>
@endpush

@section('content')
<!-- Stats Cards -->
<div class="dashboard-stats">
    <div class="stat-card" style="border-color: #3b82f6;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p class="stat-label">Total Users</p>
                <p class="stat-value" style="color: #3b82f6;">{{ $totalUsers ?? 0 }}</p>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="border-color: #8b5cf6;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p class="stat-label">Facilities</p>
                <p class="stat-value" style="color: #8b5cf6;">{{ $totalFacilities ?? 0 }}</p>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                <i class="fa-solid fa-building"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="border-color: #f59e0b;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p class="stat-label">Pending</p>
                <p class="stat-value" style="color: #f59e0b;">{{ $pendingReservationsCount ?? 0 }}</p>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="border-color: #10b981;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p class="stat-label">Approved</p>
                <p class="stat-value" style="color: #10b981;">{{ $approvedReservationsCount ?? 0 }}</p>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fa-solid fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Grid: Calendar + Sidebar -->
<div class="dashboard-grid">
    <!-- Calendar Section -->
    <div class="calendar-container">
        <div class="calendar-header">
            <h3 class="calendar-title">
                <i class="fa-solid fa-calendar" style="color: #10b981;"></i>
                Reservations Calendar
            </h3>
            <a href="{{ route('admin.reservations.index') }}" style="font-size: 0.875rem; color: #10b981; font-weight: 600; text-decoration: none;">
                View All →
            </a>
        </div>
        <div id="adminCalendar"></div>
        <div class="calendar-legend">
            <div class="legend-item">
                <div class="legend-dot" style="background: linear-gradient(135deg, #10b981, #059669);"></div>
                <span>Approved</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot" style="background: linear-gradient(135deg, #f59e0b, #d97706);"></div>
                <span>Pending</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot" style="background: linear-gradient(135deg, #ef4444, #dc2626);"></div>
                <span>Rejected</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot" style="background: linear-gradient(135deg, #6b7280, #4b5563);"></div>
                <span>Cancelled</span>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Pending Reservations -->
        <div class="sidebar-section">
            <h3 class="section-title">
                <i class="fa-solid fa-clock" style="color: #f59e0b;"></i>
                Pending Reservations
            </h3>
            
            @if(isset($pendingReservations) && $pendingReservations->count() > 0)
                @foreach($pendingReservations->take(5) as $reservation)
                    <a href="{{ route('admin.reservations.show', $reservation) }}" class="pending-item">
                        <div class="pending-icon" style="background: #fef3c7; color: #d97706;">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div class="pending-details">
                            <div class="pending-title">{{ $reservation->event_name ?? 'Reservation' }}</div>
                            <div class="pending-meta">
                                {{ $reservation->facility->name ?? 'N/A' }} • {{ $reservation->user->name ?? 'N/A' }}
                            </div>
                            <div class="pending-meta">
                                {{ $reservation->start_time->format('M d, Y g:i A') }}
                            </div>
                        </div>
                    </a>
                @endforeach
                
                <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}" class="view-all-btn">
                    View All Pending →
                </a>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-check-circle"></i>
                    <p>No pending reservations</p>
                </div>
            @endif
        </div>

        <!-- Pending Guests -->
        <div class="sidebar-section">
            <h3 class="section-title">
                <i class="fa-solid fa-user-clock" style="color: #3b82f6;"></i>
                Pending Guests
            </h3>
            
            @if(isset($pendingGuestsData) && $pendingGuestsData->count() > 0)
                @foreach($pendingGuestsData->take(5) as $guest)
                    <a href="{{ route('admin.guests.show', $guest) }}" class="pending-item" style="border-left-color: #3b82f6;">
                        <div class="pending-icon" style="background: #dbeafe; color: #2563eb;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="pending-details">
                            <div class="pending-title">{{ $guest->name }}</div>
                            <div class="pending-meta">{{ $guest->email }}</div>
                            <div class="pending-meta">{{ $guest->created_at->diffForHumans() }}</div>
                        </div>
                    </a>
                @endforeach
                
                <a href="{{ route('admin.guests.index', ['status' => 'pending']) }}" class="view-all-btn" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #2563eb;">
                    View All Pending →
                </a>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-user-check"></i>
                    <p>No pending guests</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('adminCalendar');
    
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: window.innerWidth < 768 ? 'listMonth,dayGridMonth' : 'dayGridMonth,dayGridWeek,listMonth'
            },
            height: 'auto',
            events: function(info, successCallback, failureCallback) {
                fetch('{{ route("admin.dashboard.events") }}?start=' + info.startStr + '&end=' + info.endStr)
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => {
                        console.error('Error fetching events:', error);
                        failureCallback(error);
                    });
            },
            eventClick: function(info) {
                Swal.fire({
                    title: info.event.title,
                    html: `
                        <div style="text-align: left; padding: 1rem 0;">
                            <p style="margin-bottom: 0.5rem;"><strong>Facility:</strong> ${info.event.extendedProps.facility}</p>
                            <p style="margin-bottom: 0.5rem;"><strong>Reserved by:</strong> ${info.event.extendedProps.user}</p>
                            <p style="margin-bottom: 0.5rem;"><strong>Status:</strong> <span style="text-transform: capitalize;">${info.event.extendedProps.status}</span></p>
                            <p style="margin-bottom: 0.5rem;"><strong>Start:</strong> ${info.event.start.toLocaleString()}</p>
                            <p><strong>End:</strong> ${info.event.end ? info.event.end.toLocaleString() : 'N/A'}</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'View Details',
                    cancelButtonText: 'Close',
                    confirmButtonColor: '#10b981',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("admin.reservations.index") }}/' + info.event.id;
                    }
                });
            },
            eventDidMount: function(info) {
                info.el.title = info.event.title + ' - ' + info.event.extendedProps.facility + ' (' + info.event.extendedProps.status + ')';
            },
            windowResize: function(view) {
                if (window.innerWidth < 768) {
                    calendar.changeView('listMonth');
                } else {
                    calendar.changeView('dayGridMonth');
                }
            }
        });
        
        calendar.render();
    }
});
</script>
@endpush