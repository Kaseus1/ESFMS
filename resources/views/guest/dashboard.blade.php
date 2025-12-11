@extends('layouts.guest')

@section('title', 'Dashboard')

@push('head-scripts')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<style>
    :root {
        /* Updated color palette */
        --color-dark-navy: #172030;
        --color-royal-blue: #002366;
        --color-navy-blue: #00285C;
        --color-white: #FFFFFF;
        --color-off-white: #F8F9FA;
        --color-text-dark: #333C4D;
        --color-text-light: #1D2636;
        --color-dark-blue: #001A4A;
        --color-charcoal: #1D2636;
        --color-border: #e2e8f0;
        --color-border-light: #f1f5f9;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    @media (min-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 2fr 1fr;
        }
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    @media (min-width: 640px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    .actions-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (min-width: 640px) {
    .actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .actions-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-left: 4px solid;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    @media (min-width: 640px) {
        .stat-icon {
            width: 60px;
            height: 60px;
        }
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        margin-top: 0.25rem;
    }
    
    @media (min-width: 640px) {
        .stat-value {
            font-size: 2rem;
        }
    }
    
    .calendar-container {
        background: white;
        border-radius: 20px;
        padding: 1.25rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    @media (min-width: 640px) {
        .calendar-container {
            padding: 1.5rem;
        }
    }
    
    .fc {
        font-family: 'Inter', sans-serif;
    }
    
    .fc .fc-toolbar {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .fc .fc-toolbar-title {
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        color: var(--color-text-dark);
    }
    
    @media (min-width: 640px) {
        .fc .fc-toolbar-title {
            font-size: 1.25rem !important;
        }
    }
    
    .fc .fc-button {
        background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue)) !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 0.4rem 0.75rem !important;
        font-weight: 600 !important;
        font-size: 0.8rem !important;
        text-transform: capitalize !important;
        box-shadow: 0 2px 8px rgba(0, 35, 102, 0.3) !important;
        transition: all 0.2s !important;
    }
    
    .fc .fc-button:hover {
        background: linear-gradient(135deg, var(--color-navy-blue), var(--color-dark-blue)) !important;
        transform: translateY(-1px);
    }
    
    .fc .fc-button-active {
        background: linear-gradient(135deg, var(--color-navy-blue), var(--color-dark-blue)) !important;
    }
    
    .fc .fc-daygrid-day {
        transition: background 0.2s;
    }
    
    .fc .fc-daygrid-day:hover {
        background: var(--color-off-white);
    }
    
    .fc .fc-daygrid-day-number {
        font-weight: 600;
        color: var(--color-text-dark);
        padding: 0.5rem;
    }
    
    .fc .fc-day-today {
        background: linear-gradient(135deg, var(--color-off-white), #e8eaed) !important;
    }
    
    /* CRITICAL FIX: Calendar event text handling */
    .fc .fc-daygrid-event {
        border-radius: 6px !important;
        padding: 2px 6px !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        border: none !important;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        /* Prevent text truncation */
        white-space: normal !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        line-height: 1.3 !important;
        min-height: 18px !important;
    }
    
    .fc .fc-daygrid-event:hover {
        transform: scale(1.02);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    /* Improved calendar event content */
    .fc .fc-daygrid-event .fc-event-main {
        padding: 0 !important;
        font-size: inherit !important;
        line-height: inherit !important;
    }
    
    .fc .fc-daygrid-event .fc-event-time {
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        margin-right: 2px !important;
    }
    
    .fc .fc-daygrid-event .fc-event-title {
        font-size: 0.7rem !important;
        font-weight: 600 !important;
    }
    
    /* Status-specific calendar event colors */
    .fc .fc-event-approved {
        background: linear-gradient(135deg, #10b981, #059669) !important;
        color: white !important;
    }
    
    .fc .fc-event-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706) !important;
        color: white !important;
    }
    
    .fc .fc-event-rejected {
        background: linear-gradient(135deg, #ef4444, #dc2626) !important;
        color: white !important;
    }
    
    .fc .fc-event-cancelled {
        background: linear-gradient(135deg, #6b7280, #4b5563) !important;
        color: white !important;
    }

    /* Day view specific styles */
    .fc-dayGridDay-view .fc-timegrid-slot {
        height: 3rem !important;
    }
    
    .fc-dayGridDay-view .fc-timegrid-slot-label {
        font-size: 0.75rem !important;
        font-weight: 600 !important;
    }
    
    .fc-dayGridDay-view .fc-timegrid-event {
        margin: 1px 0 !important;
        border-radius: 6px !important;
        overflow: hidden;
    }
    
    .fc-dayGridDay-view .fc-timegrid-more-link {
        font-size: 0.7rem !important;
        font-weight: 600 !important;
    }

    /* Mobile responsive improvements for day view */
    @media (max-width: 768px) {
        .fc-dayGridDay-view .fc-timegrid-slot {
            height: 2.5rem !important;
        }
        
        .fc-dayGridDay-view .fc-timegrid-event {
            font-size: 0.7rem !important;
            padding: 1px 3px !important;
        }
        
        .fc-dayGridDay-view .fc-timegrid-more-link {
            font-size: 0.65rem !important;
        }
    }
    
    @media (max-width: 480px) {
        .fc-dayGridDay-view .fc-timegrid-slot {
            height: 2rem !important;
        }
        
        .fc-dayGridDay-view .fc-timegrid-event {
            font-size: 0.65rem !important;
        }
    }
    
    .sidebar-section {
        background: white;
        border-radius: 20px;
        padding: 1.25rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    @media (min-width: 640px) {
        .sidebar-section {
            padding: 1.5rem;
        }
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--color-text-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    @media (min-width: 640px) {
        .section-title {
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
        }
    }
    
    /* CRITICAL FIX: Improved upcoming reservations list */
    .upcoming-reservations-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    /* Enhanced reservation card styling */
    .reservation-card {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border: 1px solid var(--color-border-light);
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 1rem;
        /* Left border accent for status */
        border-left: 4px solid var(--color-royal-blue);
    }
    
    .reservation-card:hover {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-left-color: var(--color-navy-blue);
    }
    
    /* Status-specific border colors */
    .reservation-card.status-approved { border-left-color: #10b981; }
    .reservation-card.status-pending { border-left-color: #f59e0b; }
    .reservation-card.status-rejected { border-left-color: #ef4444; }
    .reservation-card.status-cancelled { border-left-color: #6b7280; }
    
    /* Enhanced date badge */
    .reservation-date-badge {
        background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue));
        color: white;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        text-align: center;
        min-width: 50px;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }
    
    .reservation-day {
        font-size: 1.25rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.125rem;
    }
    
    .reservation-month {
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
    }
    
    /* Reservation content layout */
    .reservation-content {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .reservation-main-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .reservation-title {
        font-weight: 700;
        color: var(--color-text-dark);
        font-size: 0.95rem;
        /* Better text handling */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .reservation-facility-activity {
        font-size: 0.8rem;
        color: var(--color-text-light);
        display: flex;
        align-items: center;
        gap: 0.25rem;
        /* Better text handling */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .reservation-time {
        font-size: 0.75rem;
        color: var(--color-text-light);
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-weight: 500;
    }
    
    /* Status indicator */
    .reservation-status-indicator {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
    }
    
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--color-royal-blue);
    }
    
    .reservation-status-indicator.status-approved .status-dot { background: #10b981; }
    .reservation-status-indicator.status-pending .status-dot { background: #f59e0b; }
    .reservation-status-indicator.status-rejected .status-dot { background: #ef4444; }
    .reservation-status-indicator.status-cancelled .status-dot { background: #6b7280; }
    
    /* Legacy styles for compatibility */
    .reservation-item {
        background: var(--color-off-white);
        border: 1px solid var(--color-border-light);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    
    /* Left border accent */
    .reservation-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--color-royal-blue);
        border-radius: 0 2px 2px 0;
    }
    
    .reservation-item:hover {
        background: #f8fafc;
        transform: translateX(2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .reservation-item:last-child {
        margin-bottom: 0;
    }
    
    /* Status-specific border colors */
    .reservation-item.status-approved::before { background: #10b981; }
    .reservation-item.status-pending::before { background: #f59e0b; }
    .reservation-item.status-rejected::before { background: #ef4444; }
    .reservation-item.status-cancelled::before { background: #6b7280; }
    
    .reservation-date {
        min-width: 50px;
        text-align: center;
        flex-shrink: 0;
    }
    
    .reservation-day {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--color-royal-blue);
        line-height: 1;
    }
    
    .reservation-month {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--color-text-light);
        text-transform: uppercase;
    }
    
    .reservation-details {
        flex: 1;
        min-width: 0;
    }
    
    .reservation-title {
        font-weight: 700;
        color: var(--color-text-dark);
        margin-bottom: 0.25rem;
        /* Better text handling */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }
    
    .reservation-facility {
        font-size: 0.875rem;
        color: var(--color-text-light);
        margin-bottom: 0.25rem;
        /* Better text handling */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }
    
    .reservation-time {
        font-size: 0.75rem;
        color: var(--color-text-light);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    /* CRITICAL FIX: Status badge alignment */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        /* Better positioning */
        flex-shrink: 0;
        white-space: nowrap;
    }
    
    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .status-cancelled {
        background: #f3f4f6;
        color: #4b5563;
    }
    
    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
        color: var(--color-text-light);
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    
    /* Enhanced View All Reservations button */
    .view-all-reservations-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.875rem 1rem;
        text-align: center;
        background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue));
        color: white;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        margin-top: 1.25rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        position: relative;
        overflow: hidden;
    }
    
    .view-all-reservations-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .view-all-reservations-btn:hover::before {
        left: 100%;
    }
    
    .view-all-reservations-btn:hover {
        background: linear-gradient(135deg, var(--color-navy-blue), #1e40af);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.35);
    }
    
    .view-all-reservations-btn i {
        font-size: 0.875rem;
        transition: transform 0.3s ease;
    }
    
    .view-all-reservations-btn:hover i {
        transform: translateX(4px);
    }
    
    /* Legacy view-all-btn for compatibility */
    .view-all-btn {
        display: block;
        width: 100%;
        padding: 0.75rem;
        text-align: center;
        background: linear-gradient(135deg, var(--color-off-white), #e8eaed);
        color: var(--color-royal-blue);
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        margin-top: 1rem;
        transition: all 0.2s;
        border: 1px solid var(--color-border-light);
    }
    
    .view-all-btn:hover {
        background: linear-gradient(135deg, #e8eaed, #d1d5db);
        transform: translateY(-2px);
    }
    
    /* CRITICAL FIX: Working legend with color indicators */
    .legend {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--color-border);
        justify-content: center;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: var(--color-text-light);
        font-weight: 600;
    }
    
    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        flex-shrink: 0;
        /* Real color indicators */
        box-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    
    .legend-dot.approved {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    
    .legend-dot.pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    
    .legend-dot.rejected {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    
    .legend-dot.cancelled {
        background: linear-gradient(135deg, #6b7280, #4b5563);
    }

    /* Enhanced mobile calendar adjustments */
    @media (max-width: 640px) {
        .fc .fc-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .fc .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        
        .fc .fc-daygrid-event {
            font-size: 0.65rem !important;
            padding: 1px 3px !important;
        }
        
        .fc .fc-col-header-cell-cushion {
            font-size: 0.75rem;
        }

        /* Improve dayGridDay view on mobile */
        .fc-dayGridDay-view .fc-toolbar-chunk {
            justify-content: center;
        }
        
        .fc-dayGridDay-view .fc-button {
            padding: 0.35rem 0.6rem !important;
            font-size: 0.75rem !important;
        }

        /* Mobile-specific grid adjustments */
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-value {
            font-size: 1.25rem;
        }

        /* Mobile reservation items */
        .reservation-item {
            padding: 0.75rem;
        }

        .reservation-date {
            min-width: 40px;
        }

        .reservation-day {
            font-size: 1.25rem;
        }

        /* Better text handling on mobile */
        .reservation-title,
        .reservation-facility {
            white-space: normal;
            overflow: visible;
            text-overflow: unset;
        }

        /* Mobile legend */
        .legend {
            gap: 0.75rem;
        }

        .legend-item {
            font-size: 0.7rem;
        }
    }

    @media (max-width: 480px) {
        .welcome-section {
            padding: 1.5rem 1rem;
            border-radius: 16px;
        }

        .welcome-title {
            font-size: clamp(1.25rem, 8vw, 1.75rem);
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .stat-card {
            padding: 0.75rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
        }

        .stat-value {
            font-size: 1.1rem;
        }

        .actions-grid {
            grid-template-columns: 1fr;
        }

        .action-card {
            padding: 1.25rem;
        }

        .calendar-container {
            padding: 1rem;
            border-radius: 16px;
        }

        .sidebar-section {
            padding: 1rem;
            border-radius: 16px;
        }

        .section-title {
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }

        .reservation-item {
            padding: 0.5rem;
        }

        .empty-state {
            padding: 1.5rem 0.5rem;
        }

        .legend {
            gap: 0.5rem;
        }

        .legend-item {
            font-size: 0.7rem;
        }
    }

    /* Very small screens */
    @media (max-width: 360px) {
        .stat-card {
            padding: 0.5rem;
        }

        .stat-value {
            font-size: 1rem;
        }

        .action-card {
            padding: 1rem;
        }

        .calendar-container,
        .sidebar-section {
            padding: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="welcome-section">
    <div class="welcome-content">
        <div style="font-size: clamp(0.875rem, 2.5vw, 1rem); font-weight: 600; margin-bottom: 0.5rem; opacity: 0.95;">
            <span style="margin-right: 0.5rem;">👋</span> Welcome back,
        </div>
        <h1 class="welcome-title" style="font-size: clamp(1.75rem, 5vw, 2.75rem);">{{ Auth::user()->name }}!</h1>
        <p style="font-size: clamp(0.9rem, 2vw, 1.15rem); opacity: 0.9; max-width: 650px; line-height: 1.6;">
            Manage your facility reservations at CPAC. Book spaces, track your requests, and explore available facilities.
        </p>
        <div style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.25rem; background: rgba(255,255,255,0.2); border-radius: 50px; font-size: 0.9rem; font-weight: 600; margin-top: 1.5rem; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
            <span style="width: 10px; height: 10px; border-radius: 50%; background: #10b981; animation: pulse 2s infinite;"></span>
            <span>Status: {{ ucfirst(Auth::user()->status ?? 'Active') }}</span>
        </div>
    </div>
</div>

<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card" style="border-color: var(--color-royal-blue);">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
            <div style="min-width: 0;">
                <p style="color: var(--color-text-light); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Bookings</p>
                <p class="stat-value" style="color: var(--color-royal-blue);">{{ $totalReservations }}</p>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue));">
                <i class="fa-solid fa-calendar-check" style="font-size: 1.25rem; color: white;"></i>
            </div>
        </div>
    </div>

    <div class="stat-card" style="border-color: #f59e0b;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
            <div style="min-width: 0;">
                <p style="color: var(--color-text-light); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Pending</p>
                <p class="stat-value" style="color: #f59e0b;">{{ $pendingReservations }}</p>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fa-solid fa-clock" style="font-size: 1.25rem; color: white;"></i>
            </div>
        </div>
    </div>

    <div class="stat-card" style="border-color: #10b981;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
            <div style="min-width: 0;">
                <p style="color: var(--color-text-light); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Approved</p>
                <p class="stat-value" style="color: #10b981;">{{ $approvedReservations }}</p>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fa-solid fa-check-circle" style="font-size: 1.25rem; color: white;"></i>
            </div>
        </div>
    </div>

    <div class="stat-card" style="border-color: #8b5cf6;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
            <div style="min-width: 0;">
                <p style="color: var(--color-text-light); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Facilities</p>
                <p class="stat-value" style="color: #8b5cf6;">{{ $totalFacilities }}</p>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                <i class="fa-solid fa-building" style="font-size: 1.25rem; color: white;"></i>
            </div>
        </div>
    </div>
</div>

<div class="actions-grid" style="margin-bottom: 2rem;">
    <a href="{{ route('guest.facilities.index') }}" class="action-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div class="action-icon" style="background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue)); width: 55px; height: 55px; font-size: 1.5rem;">
                <i class="fa-solid fa-building"></i>
            </div>
            <i class="fa-solid fa-arrow-right" style="font-size: 1.25rem; color: #cbd5e1;"></i>
        </div>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 0.35rem;">Browse Facilities</h3>
        <p style="color: var(--color-text-light); font-size: 0.875rem; line-height: 1.5;">Find the perfect space for your event.</p>
    </a>

    <a href="{{ route('guest.reservations.index') }}" class="action-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div class="action-icon" style="background: linear-gradient(135deg, var(--color-dark-blue), var(--color-royal-blue)); width: 55px; height: 55px; font-size: 1.5rem;">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <i class="fa-solid fa-arrow-right" style="font-size: 1.25rem; color: #cbd5e1;"></i>
        </div>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 0.35rem;">My Reservations</h3>
        <p style="color: var(--color-text-light); font-size: 0.875rem; line-height: 1.5;">View and manage your bookings.</p>
    </a>

    <a href="{{ route('guest.wallet.index') }}" class="action-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div class="action-icon" style="background: linear-gradient(135deg, #10b981, #059669); width: 55px; height: 55px; font-size: 1.5rem;">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <i class="fa-solid fa-arrow-right" style="font-size: 1.25rem; color: #cbd5e1;"></i>
        </div>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 0.35rem;">My Wallet</h3>
        <p style="color: var(--color-text-light); font-size: 0.875rem; line-height: 1.5;">Manage your payment balance.</p>
    </a>
</div>

<div class="dashboard-grid">
    <div class="calendar-container">
        <h3 class="section-title">
            <i class="fa-solid fa-calendar" style="color: var(--color-royal-blue);"></i>
            My Reservation Calendar
        </h3>
        <div id="calendar"></div>
        
        <!-- CRITICAL FIX: Working legend with real color indicators -->
        <div class="legend">
            <div class="legend-item">
                <div class="legend-dot approved"></div>
                <span>Approved</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot pending"></div>
                <span>Pending</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot rejected"></div>
                <span>Rejected</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot cancelled"></div>
                <span>Cancelled</span>
            </div>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="sidebar-section">
            <h3 class="section-title">
                <i class="fa-solid fa-clock" style="color: var(--color-royal-blue);"></i>
                Upcoming Reservations
            </h3>
            
            @if($upcomingReservations && $upcomingReservations->count() > 0)
                <div class="upcoming-reservations-list">
                    @foreach($upcomingReservations as $reservation)
                        <a href="{{ route('guest.reservations.show', $reservation) }}" class="reservation-card status-{{ $reservation->status }}" style="text-decoration: none;">
                            <!-- Date Badge -->
                            <div class="reservation-date-badge">
                                <div class="reservation-day">{{ \Carbon\Carbon::parse($reservation->start_time)->format('d') }}</div>
                                <div class="reservation-month">{{ \Carbon\Carbon::parse($reservation->start_time)->format('M') }}</div>
                            </div>
                            
                            <!-- Reservation Content -->
                            <div class="reservation-content">
                                <div class="reservation-main-info">
                                    <div class="reservation-title">{{ $reservation->event_name ?? 'Reservation' }}</div>
                                    <div class="reservation-facility-activity">
                                        <i class="fa-solid fa-building" style="font-size: 0.75rem; color: var(--color-text-light); margin-right: 0.25rem;"></i>
                                        {{ $reservation->facility->name ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="reservation-time">
                                    <i class="fa-regular fa-clock" style="font-size: 0.75rem;"></i>
                                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}
                                </div>
                            </div>
                            
                            <!-- Status Indicator -->
                            <div class="reservation-status-indicator status-{{ $reservation->status }}">
                                <span class="status-dot"></span>
                            </div>
                        </a>
                    @endforeach
                </div>
                
                <a href="{{ route('guest.reservations.index') }}" class="view-all-reservations-btn">
                    <span>View All Reservations</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            @else
                <div class="empty-state">
                    <i class="fa-regular fa-calendar-xmark"></i>
                    <p>No upcoming reservations</p>
                    <a href="{{ route('guest.facilities.index') }}" style="color: var(--color-royal-blue); font-weight: 600; text-decoration: none;">
                        Book a facility →
                    </a>
                </div>
            @endif
        </div>

        <div class="sidebar-section">
            <h3 class="section-title">
                <i class="fa-solid fa-history" style="color: var(--color-royal-blue);"></i>
                Recent Activity
            </h3>
            
            @if($recentReservations && $recentReservations->count() > 0)
                @foreach($recentReservations->take(3) as $reservation)
                    <div class="reservation-item status-{{ $reservation->status }}">
                        <div class="reservation-details" style="width: 100%;">
                            <!-- CRITICAL FIX: Better alignment for title and status -->
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.5rem; flex-wrap: wrap;">
                                <div class="reservation-title" style="flex: 1; min-width: 0; margin-bottom: 0;">{{ $reservation->event_name ?? 'Reservation' }}</div>
                                <span class="status-badge status-{{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span>
                            </div>
                            <div class="reservation-facility">{{ $reservation->facility->name ?? 'N/A' }}</div>
                            <div class="reservation-time">
                                {{ \Carbon\Carbon::parse($reservation->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fa-regular fa-folder-open"></i>
                    <p>No recent activity</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: window.innerWidth < 768 ? 'listMonth,dayGridMonth,dayGridDay' : 'dayGridMonth,dayGridWeek,listMonth,dayGridDay'
        },
        height: 'auto',
        nowIndicator: true,
        scrollTime: '08:00:00',
        slotMinTime: '06:00:00',
        slotMaxTime: '22:00:00',
        allDaySlot: false,
        dayMaxEventRows: true,
        // CRITICAL FIX: Better event display configuration
        eventDisplay: 'block',
        displayEventTime: true,
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short'
        },
        events: [
            @foreach($calendarReservations ?? [] as $reservation)
            {
                id: '{{ $reservation->id }}',
                title: '{{ addslashes($reservation->event_name ?? "Reservation") }}',
                start: '{{ $reservation->start_time->format("Y-m-d\TH:i:s") }}',
                end: '{{ $reservation->end_time->format("Y-m-d\TH:i:s") }}',
                className: 'fc-event-{{ $reservation->status }}',
                extendedProps: {
                    facility: '{{ addslashes($reservation->facility->name ?? "N/A") }}',
                    status: '{{ $reservation->status }}'
                }
            },
            @endforeach
        ],
        eventClick: function(info) {
            window.location.href = '{{ route("guest.reservations.index") }}/' + info.event.id;
        },
        eventDidMount: function(info) {
            // Enhanced tooltip with facility and status info
            info.el.title = info.event.title + ' - ' + info.event.extendedProps.facility + ' (' + info.event.extendedProps.status + ')';
        },
        dateClick: function(info) {
            // Switch to dayGridDay view when a date is clicked
            calendar.changeView('dayGridDay', info.dateStr);
        },
        windowResize: function(view) {
            if (window.innerWidth < 768) {
                if (['dayGridDay', 'timeGridDay'].includes(calendar.view.type)) {
                    calendar.changeView('dayGridDay', calendar.getDate());
                } else {
                    calendar.changeView('listMonth');
                }
            } else {
                if (['dayGridDay', 'timeGridDay'].includes(calendar.view.type)) {
                    calendar.changeView('dayGridMonth', calendar.getDate());
                } else {
                    calendar.changeView('dayGridMonth');
                }
            }
        }
    });
    
    calendar.render();
});
</script>
@endpush